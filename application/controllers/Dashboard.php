<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $this->load->model('Stats_model');
        $this->load->model('Audit_model');
        $this->load->model('College_model');
        $this->load->model('Auth_model');
        $this->load->model('Asset_model');

        $data['title'] = 'AZAM IT | Dashboard';

        $role_id = $this->session->userdata('role_id');

        // System Admin
        if ($role_id == 1) {
            // Analytics Data for Admin
            $filters = [
                'start_date' => $this->input->get('start_date'),
                'end_date' => $this->input->get('end_date'),
                'college_id' => $this->input->get('college_id'),
                'status' => $this->input->get('status')
            ];
            $data['analytics'] = $this->Stats_model->get_issue_analytics($filters);
            $data['colleges'] = $this->College_model->get_all(); // For filter dropdown

            $data['page_title'] = 'Dashboard Overview';
            $data['counts'] = $this->Stats_model->get_counts();
            $data['categories'] = $this->Stats_model->get_assets_by_category();
            $data['recent_assets'] = $this->Stats_model->get_recent_assets(5);
            $data['recent_logs'] = $this->Audit_model->get_all_logs();
            $data['due_soon'] = $this->Stats_model->get_loans_due_soon(7);

            $this->load->view('layout/header', $data);
            $this->load->view('layout/sidebar', $data);
            $this->load->view('dashboard/overview', $data);
            $this->load->view('layout/footer');
        }
        // College Admin / Staff
        else {
            // Need to know WHICH college. 
            // Since we don't have college_id in session yet, let's fetch it from user profile
            // assuming we added it or will add it. 
            // For now, if college_id is null, it might be a generic staff. 
            // But let's assume filtering by the user's assigned college.

            $user_id = $this->session->userdata('user_id');
            $user = $this->Auth_model->get_user_by_id($user_id);
            $college_id = isset($user->college_id) ? $user->college_id : null;

            if ($college_id) {
                $college = $this->College_model->get_by_id($college_id);
                $data['college_name'] = $college ? $college->name : 'My Department';

                $data['counts'] = $this->Stats_model->get_college_stats($college_id);
                $data['recent_assets'] = $this->Asset_model->get_recent_college_assets($college_id, 5);
                $data['categories'] = $this->Stats_model->get_college_category_distribution($college_id);
            } else {
                $data['college_name'] = 'General';
                $data['counts'] = ['total_assets' => 0, 'good_condition' => 0, 'due_soon' => 0];
                $data['recent_assets'] = [];
                $data['categories'] = [];
            }

            $data['page_title'] = 'Department Dashboard';

            $this->load->view('layout/header', $data);
            $this->load->view('layout/sidebar', $data);
            $this->load->view('dashboard/college', $data);
            $this->load->view('layout/footer');
        }
    }
}
