<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $this->load->model('Auth_model');
        $this->load->model('Audit_model');
    }

    public function index()
    {
        $data['title'] = 'AZAM IT | My Profile';
        $data['page_title'] = 'My Profile';
        $data['user'] = $this->Auth_model->get_user_by_id($this->session->userdata('user_id'));

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('profile/index', $data);
        $this->load->view('layout/footer');
    }

    public function update_details()
    {
        $user_id = $this->session->userdata('user_id');

        $this->form_validation->set_rules('full_name', 'Full Name', 'required');

        // Only validate email uniqueness if it changed
        $current_user = $this->Auth_model->get_user_by_id($user_id);
        if ($this->input->post('email') != $current_user->email) {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        } else {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'full_name' => $this->input->post('full_name'),
                'email' => $this->input->post('email'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            if ($this->Auth_model->update_user($user_id, $data)) {
                $this->Audit_model->log_action('Updated Profile', 'User', $user_id, "Updated details");
                $this->session->set_flashdata('success', 'Profile updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update profile.');
            }
        }
        redirect('profile');
    }

    public function update_password()
    {
        $user_id = $this->session->userdata('user_id');

        $this->form_validation->set_rules('current_password', 'Current Password', 'required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('profile');
        }

        // Verify current password
        $current_user = $this->Auth_model->get_user_by_id($user_id);

        // Manually verifying since we don't have a check_password model method strictly for this, 
        // but we can use password_verify directly here as we have the hash.
        if (!password_verify($this->input->post('current_password'), $current_user->password_hash)) {
            $this->session->set_flashdata('error', 'Incorrect current password.');
            redirect('profile');
        }

        // Update password
        $data = array(
            'password_hash' => password_hash($this->input->post('new_password'), PASSWORD_BCRYPT),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($this->Auth_model->update_user($user_id, $data)) {
            $this->Audit_model->log_action('Changed Password', 'User', $user_id, "Password updated securely");
            $this->session->set_flashdata('success', 'Password changed successfully. Please login again.');
            redirect('auth/logout');
        } else {
            $this->session->set_flashdata('error', 'Failed to update password.');
            redirect('profile');
        }
    }
}
