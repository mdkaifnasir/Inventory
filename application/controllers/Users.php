<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        // In Phase 1, only Role ID 1 (System Admin) should access this
        if ($this->session->userdata('role_id') != 1) {
            $this->session->set_flashdata('error', 'Unauthorized access! You do not have permission to manage users.');
            redirect('dashboard');
        }
        $this->load->model('Auth_model');
        $this->load->model('Audit_model');
        $this->load->model('College_model');
    }

    public function index()
    {
        $data['title'] = 'AZAM IT | User Management';
        $data['page_title'] = 'User Management';
        $data['users'] = $this->Auth_model->get_all_users();
        $data['roles'] = $this->Auth_model->get_roles();
        $data['colleges'] = $this->College_model->get_all();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('users/index', $data);
        $this->load->view('layout/footer');
    }

    public function store()
    {
        log_message('debug', 'Users: store() method called');
        $account_type = $this->input->post('account_type');

        // Standard Validation
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('role_id', 'Role', 'required');

        if ($account_type == 'individual') {
            $this->form_validation->set_rules('full_name', 'Full Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('employee_id', 'Employee ID', 'required|is_unique[users.employee_id]');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('users');
        } else {
            $username = $this->input->post('username');
            $college_id = $this->input->post('college_id') ?: NULL;

            if ($account_type == 'college' && $college_id) {
                $college = $this->College_model->get_by_id($college_id);
                $full_name = $college->name;
                $email = $username . "@" . strtolower(str_replace(' ', '', $college->name)) . ".local";
                $employee_id = "COL-" . $college_id . "-" . $username;
            } else {
                $full_name = $this->input->post('full_name');
                $email = $this->input->post('email');
                $employee_id = $this->input->post('employee_id');
            }

            $user_data = array(
                'username' => $username,
                'full_name' => $full_name,
                'employee_id' => $employee_id,
                'email' => $email,
                'password_hash' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'role_id' => $this->input->post('role_id'),
                'college_id' => $college_id,
                'status' => 'Active'
            );

            $inserted_id = $this->Auth_model->register($user_data);
            log_message('debug', "Users: User registered with ID: $inserted_id");
            if ($inserted_id) {
                $email_status = '';
                log_message('debug', "Users: Checking send_email: " . $this->input->post('send_email') . ", college_id: " . $college_id);
                // Send Email if requested
                if ($this->input->post('send_email') && $college_id) {
                    $this->load->library('email');

                    $college = $this->College_model->get_by_id($college_id);
                    // Use the first email if multiple are present
                    $recipient_email = explode('/', $college->email)[0];
                    log_message('debug', "Users: Recipient: $recipient_email for College: " . ($college ? $college->name : 'N/A'));

                    if ($recipient_email) {
                        // Get current admin email for "From" address
                        $admin_id = $this->session->userdata('user_id');
                        $admin_user = $this->Auth_model->get_user_by_id($admin_id);
                        $from_email = $this->config->item('smtp_user');
                        if (empty($from_email)) {
                            $from_email = $admin_user ? $admin_user->email : 'admin@azaminventory.local';
                        }

                        $this->email->from($from_email, 'AZAM IT Inventory System');
                        $this->email->to(trim($recipient_email));
                        $this->email->subject('Your Inventory System Login Credentials');

                        $message = "
                        <h2>Welcome to Azam Inventory System</h2>
                        <p>Dear {$college->name},</p>
                        <p>An account has been created for your institution to manage assets.</p>
                        <p><strong>Login Details:</strong></p>
                        <ul>
                            <li><strong>URL:</strong> " . site_url('auth/login') . "</li>
                            <li><strong>Username:</strong> {$username}</li>
                            <li><strong>Password:</strong> " . $this->input->post('password') . "</li>
                        </ul>
                        <p>Please change your password after your first login.</p>
                        <p>Regards,<br>AZAM IT</p>
                        ";

                        $this->email->message($message);

                        // Try to send email
                        $email_sent = false;
                        try {
                            if ($this->email->send()) {
                                $email_sent = true;
                                $email_status = ' Credentials emailed to college.';
                            } else {
                                $email_status = ' (Email sending failed. See logs for credentials.)';
                                log_message('error', 'Email sending failed. Full Debugger: ' . $this->email->print_debugger());
                            }
                        } catch (Exception $e) {
                            $email_status = ' (Email error. See logs.)';
                            log_message('error', 'Email Exception: ' . $e->getMessage());
                        }

                        // FALLBACK: Log credentials to a dedicated file for local development access
                        if (!$email_sent) {
                            $log_path = FCPATH . 'application/logs/email_credentials.log';
                            $log_entry = "--------------------------------------------------\n";
                            $log_entry .= "Date: " . date('Y-m-d H:i:s') . "\n";
                            $log_entry .= "To: " . trim($recipient_email) . "\n";
                            $log_entry .= "Subject: Your Inventory System Login Credentials\n";
                            $log_entry .= "Username: {$username}\n";
                            $log_entry .= "Password: " . $this->input->post('password') . "\n";
                            $log_entry .= "--------------------------------------------------\n\n";
                            file_put_contents($log_path, $log_entry, FILE_APPEND);

                            $email_status .= " Credentials saved to log file.";
                        }
                    } else {
                        $email_status = ' (No email found for this college).';
                    }
                }

                $this->Audit_model->log_action('Added User', 'User', $inserted_id, "User: {$user_data['username']}, Role: {$user_data['role_id']}");
                $this->session->set_flashdata('success', 'User added successfully!' . $email_status);
            } else {
                $this->session->set_flashdata('error', 'Failed to add user.');
            }
            redirect('users');
        }
    }

    public function toggle_status($id)
    {
        $user = $this->db->get_where('users', ['id' => $id])->row();
        if ($user) {
            $new_status = ($user->status == 'Active') ? 'Inactive' : 'Active';
            $this->db->where('id', $id);
            $this->db->update('users', ['status' => $new_status]);
            $this->Audit_model->log_action('Updated User Status', 'User', $id, "New Status: $new_status");
            $this->session->set_flashdata('success', 'User status updated successfully.');
        }
        redirect('users');
    }

    public function edit($id)
    {
        $data['user'] = $this->Auth_model->get_user_by_id($id);
        if (!$data['user']) {
            show_404();
        }
        $data['title'] = 'AZAM IT | Edit User';
        $data['page_title'] = 'Edit User Management';
        $data['roles'] = $this->Auth_model->get_roles();
        $data['colleges'] = $this->College_model->get_all();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('users/edit_modal', $data);
        $this->load->view('layout/footer');
    }

    public function update($id)
    {
        $this->form_validation->set_rules('full_name', 'Full Name', 'required');

        // Custom validation for username to ignore current user
        $original_user = $this->Auth_model->get_user_by_id($id);
        $username_rule = 'required';
        if ($this->input->post('username') != $original_user->username) {
            $username_rule .= '|is_unique[users.username]';
        }
        $this->form_validation->set_rules('username', 'Username', $username_rule);

        // Custom validation for email to ignore current user
        $email_rule = 'required|valid_email';
        if ($this->input->post('email') != $original_user->email) {
            $email_rule .= '|is_unique[users.email]';
        }
        $this->form_validation->set_rules('email', 'Email', $email_rule);

        $this->form_validation->set_rules('role_id', 'Role', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'full_name' => $this->input->post('full_name'),
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'role_id' => $this->input->post('role_id'),
                'college_id' => $this->input->post('college_id') ?: NULL,
                'employee_id' => $this->input->post('employee_id'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            // Only update password if provided
            if ($this->input->post('password')) {
                $data['password_hash'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
            }

            if ($this->Auth_model->update_user($id, $data)) {
                $this->Audit_model->log_action('Updated User', 'User', $id, "Updated details for user: {$data['username']}");
                $this->session->set_flashdata('success', 'User updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update user.');
            }
        }
        redirect('users');
    }

    public function delete($id)
    {
        // Prevent deleting self
        if ($this->session->userdata('user_id') == $id) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
            redirect('users');
        }

        // Prevent deleting Root Admin (ID 1)
        if ($id == 1) {
            $this->session->set_flashdata('error', 'The Root Administrator account cannot be deleted.');
            redirect('users');
        }

        $user = $this->Auth_model->get_user_by_id($id);
        if ($user) {
            if ($this->Auth_model->delete_user($id)) {
                $this->Audit_model->log_action('Deleted User', 'User', $id, "Deleted user: {$user->username}");
                $this->session->set_flashdata('success', 'User deleted successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to delete user.');
            }
        } else {
            $this->session->set_flashdata('error', 'User not found.');
        }
        redirect('users');
    }
}
