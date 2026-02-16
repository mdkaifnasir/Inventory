<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Colleges extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access! You do not have permission to manage institutions.');
            redirect('dashboard');
        }
        $this->load->model('College_model');
        $this->load->model('Audit_model');
    }

    public function index()
    {
        $data['title'] = 'AZAM IT | Colleges';
        $data['page_title'] = 'Colleges & Institutions';
        $data['colleges'] = $this->College_model->get_all();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('colleges/index', $data);
        $this->load->view('layout/footer');
    }

    public function view($id)
    {
        $college = $this->College_model->get_by_id($id);
        if (!$college) {
            $this->session->set_flashdata('error', 'Institution not found.');
            redirect('dashboard');
        }

        $this->load->model('Asset_model');
        $this->load->model('Category_model');

        // Capture filters from GET
        $search = $this->input->get('search');
        $category_id = $this->input->get('category_id');
        $status = $this->input->get('status');
        $condition = $this->input->get('condition');

        $data['filters'] = [
            'search' => $search,
            'category_id' => $category_id,
            'status' => $status,
            'condition' => $condition
        ];

        $data['title'] = "AZAM IT | {$college->name}";
        $data['page_title'] = "Institutional Possession: {$college->name}";
        $data['college'] = $college;
        $data['categories'] = $this->Category_model->get_all();

        // Use the model's filtering method
        $data['assets'] = $this->Asset_model->get_filtered_assets($search, $category_id, $condition, $status, $id);

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('colleges/view', $data);
        $this->load->view('layout/footer');
    }

    public function print_qrs($id)
    {
        $college = $this->College_model->get_by_id($id);
        if (!$college) {
            $this->session->set_flashdata('error', 'Institution not found.');
            redirect('dashboard');
        }

        $this->load->model('Asset_model');
        $data['college'] = $college;
        $data['assets'] = $this->Asset_model->get_filtered_assets(null, null, null, null, $id);

        $this->load->view('colleges/print_qrs', $data);
    }

    public function store()
    {
        $data = [
            'name' => $this->input->post('name'),
            'code' => strtoupper($this->input->post('code')),
            'description' => $this->input->post('description'),
            'principal_name' => $this->input->post('principal_name'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'mobile' => $this->input->post('mobile'),
            'fax' => $this->input->post('fax'),
            'website' => $this->input->post('website')
        ];

        if ($this->College_model->insert($data)) {
            $inserted_id = $this->db->insert_id();
            $this->Audit_model->log_action('Created College', 'College', $inserted_id, "Name: {$data['name']}");
            $this->session->set_flashdata('success', 'College created successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to create college.');
        }

        redirect('colleges');
    }

    public function update($id)
    {
        $data = [
            'name' => $this->input->post('name'),
            'code' => strtoupper($this->input->post('code')),
            'description' => $this->input->post('description'),
            'principal_name' => $this->input->post('principal_name'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'mobile' => $this->input->post('mobile'),
            'fax' => $this->input->post('fax'),
            'website' => $this->input->post('website')
        ];

        if ($this->College_model->update($id, $data)) {
            $this->Audit_model->log_action('Updated College', 'College', $id, "Name: {$data['name']}");
            $this->session->set_flashdata('success', 'College updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update college.');
        }

        redirect('colleges');
    }

    public function delete($id)
    {
        $college = $this->College_model->get_by_id($id);
        if ($this->College_model->delete($id)) {
            if ($college) {
                $this->Audit_model->log_action('Deleted College', 'College', $id, "Name: {$college->name}");
            }
            $this->session->set_flashdata('success', 'College deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete college.');
        }

        redirect('colleges');
    }

    public function get_college_json($id)
    {
        $college = $this->College_model->get_by_id($id);
        echo json_encode($college);
    }

}
