<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        // Ideally restrict to admin
        // if ($this->session->userdata('role_id') != 1) redirect('dashboard');

        $this->load->model('Settings_model');
        $this->load->model('Audit_model');
    }

    public function index()
    {
        $data['title'] = 'AZAM IT | System Settings';
        $data['page_title'] = 'System Configuration';
        $data['settings'] = $this->Settings_model->get_all_settings();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('settings/index', $data);
        $this->load->view('layout/footer');
    }

    public function update()
    {
        $post_data = $this->input->post();

        // Remove CI specific keys if any (like crsf) - though input->post() cleans some, 
        // we mainly want known keys, but bulk update is flexible.
        unset($post_data['submit']);

        if ($this->Settings_model->bulk_update($post_data)) {
            $this->Audit_model->log_action('Updated Settings', 'System', 0, 'Updated system configuration');
            $this->session->set_flashdata('success', 'System settings updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update settings.');
        }

        redirect('settings');
    }
}
