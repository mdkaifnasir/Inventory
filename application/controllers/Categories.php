<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access! You do not have permission to manage categories.');
            redirect('dashboard');
        }
        $this->load->model('Category_model');
        $this->load->model('Audit_model');
    }

    public function index()
    {
        $data['title'] = 'AZAM IT | Asset Categories';
        $data['page_title'] = 'Asset Categories';
        $data['categories'] = $this->Category_model->get_all_with_counts();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('categories/index', $data);
        $this->load->view('layout/footer');
    }

    public function view($id)
    {
        $category = $this->Category_model->get_by_id($id);
        if (!$category) {
            $this->session->set_flashdata('error', 'Category not found.');
            redirect('categories');
        }

        $this->load->model('Asset_model');
        $data['title'] = 'AZAM IT | ' . $category->name;
        $data['page_title'] = $category->name . ' Assets';
        $data['category'] = $category;
        $data['assets'] = $this->Asset_model->get_filtered_assets(null, $id);

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('categories/view', $data);
        $this->load->view('layout/footer');
    }

    public function store()
    {
        $this->form_validation->set_rules('name', 'Category Name', 'required|is_unique[categories.name]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'icon' => $this->input->post('icon') ? $this->input->post('icon') : 'category'
            );
            $inserted_id = $this->Category_model->insert($data);
            if ($inserted_id) {
                $this->Audit_model->log_action('Added Category', 'Category', $inserted_id, "Category: {$data['name']}");
                $this->session->set_flashdata('success', 'Category added successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to add category.');
            }
        }
        redirect('categories');
    }

    public function update($id)
    {
        $this->form_validation->set_rules('name', 'Category Name', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'icon' => $this->input->post('icon') ? $this->input->post('icon') : 'category'
            );
            if ($this->Category_model->update($id, $data)) {
                $this->Audit_model->log_action('Updated Category', 'Category', $id, "Category: {$data['name']}");
                $this->session->set_flashdata('success', 'Category updated successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to update category.');
            }
        }
        redirect('categories');
    }

    public function get_category_json($id)
    {
        $category = $this->db->get_where('categories', array('id' => $id))->row();
        echo json_encode($category);
    }

    public function delete($id)
    {
        if ($this->Category_model->delete($id)) {
            $this->Audit_model->log_action('Deleted Category', 'Category', $id);
            $this->session->set_flashdata('success', 'Category deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete category.');
        }
        redirect('categories');
    }
}
