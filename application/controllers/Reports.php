<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        // Access Control: Only System Admin (1) and Inventory Admin (2)
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Access Denied. Reports are for Administrators only.');
            redirect('dashboard');
        }

        $this->load->model('Reports_model');
        $this->load->model('Category_model');
        $this->load->model('College_model');
    }

    public function index()
    {
        $data['title'] = 'AZAM IT | Reports Dashboard';
        $data['page_title'] = 'Reports Dashboard';
        $data['stats'] = $this->Reports_model->get_summary_stats();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('reports/index', $data);
        $this->load->view('layout/footer');
    }

    public function assets()
    {
        $data['title'] = 'AZAM IT | Asset Reports';
        $data['page_title'] = 'Asset Reports';

        $filters = $this->input->get();
        $data['assets'] = $this->Reports_model->get_asset_report($filters);
        $data['categories'] = $this->Category_model->get_all();
        $data['colleges'] = $this->College_model->get_all();
        $data['filters'] = $filters;

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('reports/assets', $data);
        $this->load->view('layout/footer');
    }

    public function issues()
    {
        $data['title'] = 'AZAM IT | Issue Reports';
        $data['page_title'] = 'Issue Reports';

        $filters = $this->input->get();
        $data['issues'] = $this->Reports_model->get_issue_report($filters);
        $data['filters'] = $filters;

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('reports/issues', $data);
        $this->load->view('layout/footer');
    }

    public function export_assets()
    {
        $filters = $this->input->get();
        $data = $this->Reports_model->get_asset_report($filters);

        $filename = 'asset_report_' . date('Ymd_His') . '.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        $file = fopen('php://output', 'w');

        $header = ['Asset Name', 'Tag', 'Category', 'College', 'Condition', 'Status', 'Date Added'];
        fputcsv($file, $header);

        foreach ($data as $row) {
            fputcsv($file, [
                $row['name'],
                $row['asset_tag'],
                isset($row['category_name']) ? $row['category_name'] : '',
                isset($row['college_name']) ? $row['college_name'] : '',
                $row['asset_condition'],
                $row['status'],
                $row['created_at']
            ]);
        }

        fclose($file);
        exit;
    }

    public function export_issues()
    {
        $filters = $this->input->get();
        $data = $this->Reports_model->get_issue_report($filters);

        $filename = 'issue_report_' . date('Ymd_His') . '.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        $file = fopen('php://output', 'w');

        $header = ['Asset', 'Tag', 'Reported By', 'Technician', 'Status', 'Priority', 'Reported Date'];
        fputcsv($file, $header);

        foreach ($data as $row) {
            fputcsv($file, [
                $row['asset_name'],
                $row['asset_tag'],
                isset($row['reported_by']) ? $row['reported_by'] : '',
                isset($row['technician_name']) ? $row['technician_name'] : '',
                $row['status'],
                $row['priority'],
                $row['created_at']
            ]);
        }

        fclose($file);
        exit;
    }
}
