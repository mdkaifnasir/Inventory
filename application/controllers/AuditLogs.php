<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuditLogs extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('user_id')) {
            redirect('auth-login');
        }
        // Restrict to admins if needed
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Access denied.');
            redirect('dashboard');
        }
        $this->load->model('Audit_model');
    }

    public function index()
    {
        $data['title'] = 'AZAM IT | Audit Logs';
        $data['page_title'] = 'System Audit Logs';
        $data['logs'] = $this->Audit_model->get_all_logs();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('audit_logs/index', $data);
        $this->load->view('layout/footer');
    }
}
