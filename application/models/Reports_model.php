<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_summary_stats()
    {
        return [
            'total_assets' => $this->db->count_all('assets'),
            'total_issues' => $this->db->count_all('asset_issues'),
            'open_issues' => $this->db->where_in('status', ['Pending', 'In Progress'])->count_all_results('asset_issues'),
            'resolved_issues' => $this->db->where('status', 'Resolved')->count_all_results('asset_issues'),
            'assets_value' => $this->db->select_sum('purchase_price')->get('assets')->row()->purchase_price,
            'assets_by_status' => $this->db->select('status, count(*) as count')->group_by('status')->get('assets')->result_array()
        ];
    }

    public function get_asset_report($filters = [])
    {
        $this->db->select('assets.*, categories.name as category_name, colleges.name as college_name');
        $this->db->from('assets');
        $this->db->join('categories', 'categories.id = assets.category_id', 'left');
        $this->db->join('colleges', 'colleges.id = assets.college_id', 'left');

        if (!empty($filters['category_id'])) {
            $this->db->where('assets.category_id', $filters['category_id']);
        }

        if (!empty($filters['status'])) {
            $this->db->where('assets.status', $filters['status']);
        }

        if (!empty($filters['college_id'])) {
            $this->db->where('assets.college_id', $filters['college_id']);
        }

        if (!empty($filters['start_date'])) {
            $this->db->where('assets.created_at >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $this->db->where('assets.created_at <=', $filters['end_date'] . ' 23:59:59');
        }

        $this->db->order_by('assets.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_issue_report($filters = [])
    {
        $this->db->select('asset_issues.*, assets.name as asset_name, assets.asset_tag, users.username as reported_by, technician.username as technician_name');
        $this->db->from('asset_issues');
        $this->db->join('assets', 'assets.id = asset_issues.asset_id');
        $this->db->join('users', 'users.id = asset_issues.reported_by_user_id', 'left');
        $this->db->join('users as technician', 'technician.id = asset_issues.technician_id', 'left');

        if (!empty($filters['status'])) {
            $this->db->where('asset_issues.status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $this->db->where('asset_issues.priority', $filters['priority']);
        }

        if (!empty($filters['start_date'])) {
            $this->db->where('asset_issues.created_at >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $this->db->where('asset_issues.created_at <=', $filters['end_date'] . ' 23:59:59');
        }

        $this->db->order_by('asset_issues.created_at', 'DESC');
        return $this->db->get()->result_array();
    }
}
