<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Session $session
 * @property CI_DB_query_builder $db
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Loader $load
 * @property Asset_model $Asset_model
 * @property Audit_model $Audit_model
 * @property Category_model $Category_model
 * @property College_model $College_model
 * @property Issue_model $Issue_model
 */
class Issues extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $redirect_url = current_url() . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '');
            $this->session->set_userdata('redirect_url', $redirect_url);
            redirect('auth/login');
        }
        $this->load->model('Asset_model');
        $this->load->model('Audit_model');
        $this->load->model('Auth_model'); // Load Auth_model for ownership validation
        // Load Issue_model when created, for now we can use direct DB or create model inline
        // $this->load->model('Issue_model'); 
    }

    public function index()
    {
        $data['title'] = 'AZAM IT | Reported Issues';
        $data['page_title'] = 'Issue Reports';

        // Fetch issues joined with assets and technician
        $this->db->select('asset_issues.*, assets.name as asset_name, assets.asset_tag, assets.serial_number, tech.username as technician_name');
        $this->db->from('asset_issues');
        $this->db->join('assets', 'assets.id = asset_issues.asset_id');
        $this->db->join('users as tech', 'tech.id = asset_issues.technician_id', 'left');

        // Data Isolation: If not Admin (1, 2), filter by college
        $role_id = $this->session->userdata('role_id');
        $user_id = $this->session->userdata('user_id');

        if (!in_array($role_id, [1, 2])) {
            // Get college_id from user if not in session
            $user = $this->Auth_model->get_user_by_id($user_id);
            $college_id = isset($user->college_id) ? $user->college_id : null;

            if ($college_id) {
                $this->db->where('assets.college_id', $college_id);
            }
            // Soft Delete: Hide deleted issues from non-admins
            $this->db->where('asset_issues.is_deleted', 0);
        }

        $this->db->order_by('asset_issues.created_at', 'DESC');
        $data['issues'] = $this->db->get()->result();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('issues/index', $data);
        $this->load->view('layout/footer');
    }

    public function report()
    {
        $data['title'] = 'AZAM IT | Report an Issue';
        $data['page_title'] = 'Report Asset Issue';

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('issues/report', $data);
        $this->load->view('layout/footer');
    }

    // AJAX Endpoint for QR Scanner
    public function get_asset_details($tag)
    {
        $asset = $this->Asset_model->get_by_tag($tag);

        if ($asset) {
            // Ownership Validation
            $user_id = $this->session->userdata('user_id');
            $role_id = $this->session->userdata('role_id');

            // Log for debugging
            log_message('error', "Asset Found: ID {$asset->id}, College: {$asset->college_id}. User: $user_id, Role: $role_id");

            // If not System Admin (1) or Inventory Admin (2), enforce ownership
            // Assuming Role 1 & 2 are global admins. If Role 2 is college-specific, add it to check.
            if (!in_array($role_id, [1, 2])) {
                $user = $this->Auth_model->get_user_by_id($user_id);

                log_message('error', "User College: {$user->college_id}");

                if ($asset->college_id != $user->college_id) {
                    echo json_encode(['status' => 'error', 'message' => 'This item does not belong to your institute inventory.']);
                    return;
                }
            }

            echo json_encode(['status' => 'success', 'asset' => $asset]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Asset not found']);
        }
    }

    public function store()
    {
        $this->form_validation->set_rules('asset_tag', 'Asset Tag', 'required');
        $this->form_validation->set_rules('staff_name', 'Staff Name', 'required');
        $this->form_validation->set_rules('issue_description', 'Description', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('issues/report');
        }

        $tag = $this->input->post('asset_tag');
        $asset = $this->Asset_model->get_by_tag($tag);

        if (!$asset) {
            $this->session->set_flashdata('error', 'Invalid Asset Tag. Please scan a valid code.');
            redirect('issues/report');
        }

        // Security: Identify Ownership Validation again
        $user_id = $this->session->userdata('user_id');
        $role_id = $this->session->userdata('role_id');

        if (!in_array($role_id, [1, 2])) {
            $user = $this->Auth_model->get_user_by_id($user_id);
            if ($asset->college_id != $user->college_id) {
                $this->Audit_model->log_action('Security Alert', 'Issue', 0, "Unauthorized issue report attempt by User $user_id for Asset {$asset->asset_tag}");
                $this->session->set_flashdata('error', 'Security Violation: You cannot report issues for assets not assigned to your institute.');
                redirect('issues/report');
            }
        }

        // Handle Photo Upload
        $photo_path = null;
        if (!empty($_FILES['issue_photo']['name'])) {
            $config['upload_path'] = './uploads/issues/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 5120; // 5MB
            $config['encrypt_name'] = TRUE;

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('issue_photo')) {
                $upload_data = $this->upload->data();
                $photo_path = 'uploads/issues/' . $upload_data['file_name'];
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('issues/report');
            }
        }

        $data = [
            'asset_id' => $asset->id,
            'reported_by_user_id' => $user_id,
            'staff_name' => $this->input->post('staff_name'),
            'issue_description' => $this->input->post('issue_description'),
            'photo_path' => $photo_path,
            'status' => 'Pending',
            'priority' => 'Medium'
        ];

        if ($this->db->insert('asset_issues', $data)) {
            $this->Audit_model->log_action('Reported Issue', 'Asset', $asset->id, "Issue reported by {$data['staff_name']}");
            $this->session->set_flashdata('success', 'Issue reported successfully! We will look into it.');
            redirect('issues/report');
        } else {
            $this->session->set_flashdata('error', 'Failed to submit report.');
            redirect('issues/report');
        }
    }

    // Technician/Admin Actions
    public function take_issue($id)
    {
        // Check Role: Only Admin (1,2) or Technician (3)
        $role_id = $this->session->userdata('role_id');
        if (!in_array($role_id, [1, 2, 3])) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('issues');
        }

        $user_id = $this->session->userdata('user_id');

        // Update Issue
        $data = [
            'status' => 'In Progress',
            'technician_id' => $user_id,
            'assigned_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $id);
        if ($this->db->update('asset_issues', $data)) {
            $this->Audit_model->log_action('Taken Issue', 'Issue', $id, "Issue #$id taken by User $user_id");
            $this->session->set_flashdata('success', 'Issue assigned to you successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update issue status.');
        }

        redirect('issues');
    }

    public function submit_resolution()
    {
        // Check Role
        $role_id = $this->session->userdata('role_id');
        if (!in_array($role_id, [1, 2, 3])) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('issues');
        }

        $id = $this->input->post('issue_id');

        $this->form_validation->set_rules('resolution_description', 'Resolution Description', 'required');
        $this->form_validation->set_rules('condition_after_fix', 'Condition', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('issues');
        }

        // Handle Resolution Photo
        $photo_path = null;
        if (!empty($_FILES['resolution_photo']['name'])) {
            $config['upload_path'] = './uploads/resolutions/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 5120; // 5MB
            $config['encrypt_name'] = TRUE;

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('resolution_photo')) {
                $upload_data = $this->upload->data();
                $photo_path = 'uploads/resolutions/' . $upload_data['file_name'];
            } else {
                $this->session->set_flashdata('error', 'Photo Upload Error: ' . $this->upload->display_errors());
                redirect('issues');
            }
        } else {
            $this->session->set_flashdata('error', 'Resolution photo is mandatory.');
            redirect('issues');
        }

        $data = [
            'status' => 'Resolved',
            'resolution_description' => $this->input->post('resolution_description'),
            'condition_after_fix' => $this->input->post('condition_after_fix'),
            'resolution_photo' => $photo_path,
            'resolved_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $id);
        if ($this->db->update('asset_issues', $data)) {
            $this->Audit_model->log_action('Resolved Issue', 'Issue', $id, "Issue #$id resolved by Technician");
            $this->session->set_flashdata('success', 'Issue marked as Resolved.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update issue.');
        }

        redirect('issues');
    }
    public function delete($id)
    {
        $user_id = $this->session->userdata('user_id');
        $role_id = $this->session->userdata('role_id');

        $issue = $this->db->where('id', $id)->get('asset_issues')->row();

        if (!$issue) {
            $this->session->set_flashdata('error', 'Issue not found.');
            redirect('issues');
        }

        // Check ownership/permissions
        // Admins can delete any, Users can delete own reporting
        $can_delete = false;

        if (in_array($role_id, [1, 2])) {
            $can_delete = true;
        } elseif ($issue->reported_by_user_id == $user_id) {
            $can_delete = true;
        }

        if (!$can_delete) {
            $this->session->set_flashdata('error', 'Unauthorized deletion.');
            redirect('issues');
        }

        // Soft Delete
        $data = [
            'is_deleted' => 1,
            'status' => 'Deleted' // Optional: Update status text too for clarity
        ];

        $this->db->where('id', $id);
        if ($this->db->update('asset_issues', $data)) {
            $this->Audit_model->log_action('Deleted Issue', 'Issue', $id, "Issue #$id soft deleted by User $user_id");
            $this->session->set_flashdata('success', 'Issue deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete issue.');
        }

        redirect('issues');
    }
}
