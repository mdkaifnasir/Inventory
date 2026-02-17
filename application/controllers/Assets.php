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
 */
class Assets extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $allowed_methods = array('view_details');
        if (!$this->session->userdata('logged_in') && !in_array($this->router->fetch_method(), $allowed_methods)) {
            redirect('auth/login');
        }
        $this->load->model('Asset_model');
        $this->load->model('Category_model');
        $this->load->model('College_model');
        $this->load->model('Audit_model');
        $this->load->model('Catalog_model');
        $this->load->model('Auth_model');
    }

    public function get_items_by_category()
    {
        // Prevent PHP warnings from corrupting JSON
        error_reporting(0);
        ini_set('display_errors', 0);

        $cat_id = $this->input->get('category_id');
        $category = $this->Category_model->get_by_id($cat_id);

        if (!$category) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([]));
            return;
        }

        $items = $this->Catalog_model->get_items_by_category($category->name);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($items));
    }

    public function index()
    {
        $data['title'] = 'AZAM IT | Master Inventory';
        $data['page_title'] = 'Master Inventory';

        // Capture filters
        $search = $this->input->get('q');
        $category_id = $this->input->get('category');
        $condition = $this->input->get('condition');
        $status = $this->input->get('status');
        $college_id = $this->input->get('college');

        // Enforce College Access Control
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $user = $this->Auth_model->get_user_by_id($this->session->userdata('user_id'));
            $college_id = isset($user->college_id) ? $user->college_id : -1;
        }

        $data['assets'] = $this->Asset_model->get_filtered_assets($search, $category_id, $condition, $status, $college_id);
        $data['categories'] = $this->Category_model->get_all();
        $data['colleges'] = $this->College_model->get_all();

        // Stats for Analysis Dashboard
        $data['stats'] = $this->Asset_model->get_stats_summary();

        // Pass current filter values back to view
        $data['filters'] = [
            'q' => $search,
            'category' => $category_id,
            'condition' => $condition,
            'status' => $status,
            'college' => $college_id
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('assets/index', $data);
        $this->load->view('layout/footer');
    }

    public function summary()
    {
        $data['title'] = 'AZAM IT | Possession Overview';
        $data['page_title'] = 'Staff Possession Summary';

        // Capture filters from GET
        $search = $this->input->get('search');
        $category_id = $this->input->get('category_id');
        $condition = $this->input->get('condition');
        $status = $this->input->get('status');
        $college_id = $this->input->get('college_id');

        $data['filters'] = [
            'search' => $search,
            'category_id' => $category_id,
            'condition' => $condition,
            'status' => $status,
            'college_id' => $college_id
        ];

        // Load models if not autoloaded
        $this->load->model('Category_model');
        $this->load->model('College_model');

        $data['summary'] = $this->Asset_model->get_distribution_summary($search, $category_id, $condition, $status, $college_id);
        $data['categories'] = $this->Category_model->get_all();
        $data['colleges'] = $this->College_model->get_all();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('assets/summary', $data);
        $this->load->view('layout/footer');
    }

    public function add()
    {
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access!');
            redirect('dashboard');
        }
        $data['title'] = 'AZAM IT | Add Item';
        $data['page_title'] = 'Add New Inventory Item';
        $data['categories'] = $this->Category_model->get_all();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('assets/add', $data);
        $this->load->view('layout/footer');
    }

    public function store()
    {
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access!');
            redirect('dashboard');
        }
        $this->form_validation->set_rules('name', 'Item Name', 'required');
        $this->form_validation->set_rules('category_id', 'Category', 'required');
        $this->form_validation->set_rules('asset_tag', 'Asset Tag', 'required|is_unique[assets.asset_tag]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('assets/add');
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'category_id' => $this->input->post('category_id'),
                'brand_model' => $this->input->post('brand_model'),
                'serial_number' => $this->input->post('serial_number'),
                'asset_tag' => $this->input->post('asset_tag'),
                'asset_condition' => $this->input->post('asset_condition'),
                'purchase_date' => $this->input->post('purchase_date'),
                'vendor' => $this->input->post('vendor'),
                'warranty_expiry' => $this->input->post('warranty_expiry'),
                'location' => $this->input->post('location'),
                'status' => $this->input->post('status'),
                'quantity' => $this->input->post('quantity'),
                'processor' => $this->input->post('processor'),
                'ram' => $this->input->post('ram'),
                'hard_disk' => $this->input->post('hard_disk'),
                'os' => $this->input->post('os'),
                'amc_details' => $this->input->post('amc_details'),
                'assigned_to' => $this->input->post('assigned_to'),
                'remarks' => $this->input->post('remarks'),
                'created_by' => $this->session->userdata('user_id')
            );

            $inserted_id = $this->Asset_model->insert($data);
            if ($inserted_id) {
                $this->Audit_model->log_action('Added Asset', 'Asset', $inserted_id, "Item: {$data['name']}, Tag: {$data['asset_tag']}");
                $this->session->set_flashdata('success', 'Asset registered successfully!');
                redirect('assets');
            } else {
                $this->session->set_flashdata('error', 'Failed to register asset.');
                redirect('assets/add');
            }
        }
    }

    public function import()
    {
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access!');
            redirect('dashboard');
        }
        $data['title'] = 'AZAM IT | Import Assets';
        $data['page_title'] = 'Bulk Import';

        // Fetch Colleges for Dropdown
        $data['colleges'] = $this->College_model->get_all();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('assets/import', $data);
        $this->load->view('layout/footer');
    }

    public function download_template()
    {
        $this->load->helper('download');
        // Structure based on user image
        // Row 1: M.C.E Society
        // Row 2: Institute Name
        // Row 3: Headers
        $csv = "M.C.E Society,,,,,,,,,,,,,,\n";
        $csv .= "Institute Name,,,,,,,,,,,,,,\n";
        $csv .= "Sr. No.,Monitor,Computer Type (Desktop/Laptop),Make / Brand,Model,Processor,RAM (With Size),Hard Disk / SSD (with Size),Operating System,Serial Number (If Available),AMC Details,Location (Dept/Room),Assigned To (Staff/Lab),Condition (Working/Faulty),Remarks\n";
        $csv .= "1,-,Desktop,Dell,Optiplex 3050,i5 7th Gen,8GB,512GB SSD,Windows 10,SN-DFS-434,AMC-2024,Lab 101,John Doe,Working,Good Condition\n";
        force_download('asset_template.csv', $csv);
    }

    public function export_csv()
    {
        $this->load->helper('download');
        $assets = $this->Asset_model->get_all();

        $filename = 'AZAM IT_Inventory_Export_' . date('Y-m-d') . '.csv';
        $header = ['Item Name', 'Category', 'Brand/Model', 'Serial Number', 'Asset Tag', 'Condition', 'Status', 'Location', 'Quantity', 'Purchase Date', 'Vendor', 'Created At'];

        $csv_data = fopen('php://temp', 'r+');
        fputcsv($csv_data, $header);

        foreach ($assets as $asset) {
            fputcsv($csv_data, [
                $asset->name,
                $asset->category_name,
                $asset->brand_model,
                $asset->serial_number,
                $asset->asset_tag,
                $asset->asset_condition,
                $asset->status,
                $asset->location,
                $asset->quantity,
                $asset->purchase_date,
                $asset->vendor,
                $asset->created_at
            ]);
        }

        rewind($csv_data);
        $csv_string = stream_get_contents($csv_data);
        fclose($csv_data);

        $this->Audit_model->log_action('Exported Data', 'System', 0, "Inventory export generated: $filename");

        force_download($filename, $csv_string);
    }

    public function process_import()
    {
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access!');
            redirect('dashboard');
        }

        // Get Selected College for Direct Allocation
        $target_college_id = $this->input->post('college_id');
        $target_college = null;
        if ($target_college_id) {
            $target_college = $this->College_model->get_by_id($target_college_id);
        }
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] != 0) {
            $this->session->set_flashdata('error', 'Please select a valid CSV file.');
            redirect('assets/import');
        }

        $file = $_FILES['csv_file']['tmp_name'];

        // Load PhpSpreadsheet
        require 'vendor/autoload.php';

        $file = $_FILES['csv_file']['tmp_name'];

        // Identify file type and load
        try {
            // Check if it's CSV or Excel based on signature or just try to load
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

            // For CSV, ensure we set correct delimiter if needed, but IOFactory is usually smart.
            if ($inputFileType == 'Csv') {
                $reader->setReadDataOnly(true);
            }

            $spreadsheet = $reader->load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error loading file: ' . $e->getMessage());
            redirect('assets/import');
        }

        // Header Hunting Logic: Scan first 15 rows
        $header_row = null;
        $start_data_index = 0; // Index where data starts (header index + 1)
        $debug_rows = [];

        foreach ($rows as $index => $row) {
            if ($index > 15)
                break;

            // Sanitize row data
            $clean_row = array_map(function ($cell) {
                return is_string($cell) || is_numeric($cell) ? trim((string) $cell) : '';
            }, $row);

            $debug_rows[] = "Row " . ($index + 1) . ": " . implode(' | ', $clean_row);

            // permissive check
            $row_lower = array_map('strtolower', $clean_row);
            $has_type = false;
            foreach ($row_lower as $col) {
                if (
                    strpos($col, 'computer type') !== false ||
                    strpos($col, 'category') !== false ||
                    strpos($col, 'item') !== false ||
                    strpos($col, 'make') !== false ||
                    strpos($col, 'brand') !== false
                ) {
                    $has_type = true;
                    break;
                }
            }

            if ($has_type) {
                $header_row = $clean_row;
                $start_data_index = $index + 1;
                break;
            }
        }

        if (!$header_row) {
            $debug_msg = implode("<br>", $debug_rows);
            $this->session->set_flashdata('error', 'Invalid Format. Could not find header row. Saw:<br>' . $debug_msg);
            redirect('assets/import');
        }

        // --- Dynamic Column Mapping ---
        $headers = array_map('strtolower', array_map('trim', $header_row));

        // Helper to find index by keyword
        $find_idx = function ($keywords) use ($headers) {
            foreach ($headers as $idx => $header) {
                foreach ((array) $keywords as $keyword) {
                    if (strpos($header, strtolower($keyword)) !== false)
                        return $idx;
                }
            }
            return false;
        };

        $idx_sr_no = $find_idx(['sr. no', 'sr no', 'id']);
        $idx_monitor = $find_idx(['monitor']);
        $idx_type = $find_idx(['computer type', 'category', 'type', 'item']);
        $idx_make = $find_idx(['make', 'brand']);
        $idx_model = $find_idx(['model']);
        $idx_proc = $find_idx(['processor', 'cpu']);
        $idx_ram = $find_idx(['ram', 'memory']);
        $idx_hdd = $find_idx(['hard disk', 'ssd', 'hdd', 'storage']);
        $idx_os = $find_idx(['operating system', 'os', 'windows']);
        $idx_serial = $find_idx(['serial number', 'serial', 'sn']);
        $idx_amc = $find_idx(['amc']);
        $idx_loc = $find_idx(['location', 'room', 'dept']);
        $idx_assign = $find_idx(['assigned to', 'user']);
        $idx_cond = $find_idx(['condition']);
        $idx_remarks = $find_idx(['remarks', 'note']);

        $success_count = 0;
        $error_count = 0;
        $errors = [];

        // Fetch categories for mapping
        $categories = $this->Category_model->get_all();
        $cat_map = [];
        foreach ($categories as $cat) {
            $cat_map[strtolower(trim($cat->name))] = $cat->id;
        }

        // EXPLICIT MAPPINGS for broad categories
        // Computer Systems (ID 20)
        $cat_map['desktop'] = 20;
        $cat_map['all in one pc'] = 20; // Specific fix for user error
        $cat_map['laptop'] = 20;
        $cat_map['server'] = 20;
        $cat_map['workstation'] = 20;

        // Output Devices (ID 16)
        $cat_map['monitor'] = 16;
        $cat_map['printer'] = 16;
        $cat_map['projector'] = 16;
        $cat_map['led'] = 16;

        // Input Devices (ID 15)
        $cat_map['keyboard'] = 15;
        $cat_map['mouse'] = 15;
        $cat_map['scanner'] = 15;

        // Processing & Core Components (ID 13)
        $cat_map['cpu'] = 13;
        $cat_map['processor'] = 13;
        $cat_map['motherboard'] = 13;
        $cat_map['ram'] = 13;
        $cat_map['graphic card'] = 13;

        // Storage Devices (ID 14)
        $cat_map['hdd'] = 14;
        $cat_map['ssd'] = 14;
        $cat_map['hard disk'] = 14;

        // Networking (ID 17)
        $cat_map['router'] = 17;
        $cat_map['switch'] = 17;

        // Initialize processing stats
        $start_row = $start_data_index + 1; // Visual row number (1-based)
        $row_count = $start_data_index; // Current index in array

        // Loop through data rows starting from where header ended
        $total_rows_in_file = count($rows);

        for ($i = $start_data_index; $i < $total_rows_in_file; $i++) {
            $row_count++; // Just for error reporting consistency
            $row = $rows[$i];

            // Sanitize row data
            $clean_row = array_map(function ($cell) {
                return is_string($cell) || is_numeric($cell) ? trim((string) $cell) : '';
            }, $row);

            // Skip empty rows
            if (count(array_filter($clean_row)) == 0)
                continue;

            $row = $clean_row; // Use sanitized row from here

            // Relaxed Validation
            $type_val = ($idx_type !== false && isset($row[$idx_type])) ? trim($row[$idx_type]) : '';
            $make_val = ($idx_make !== false && isset($row[$idx_make])) ? trim($row[$idx_make]) : '';

            // If both Type and Make are empty, skip (likely section header or empty line)
            if (empty($type_val) && empty($make_val)) {
                continue;
            }

            // Category Mapping
            $cat_name = strtolower($type_val);
            $cat_id = isset($cat_map[$cat_name]) ? $cat_map[$cat_name] : null;

            if (!$cat_id) {
                // Fallback: Check if category name contains the type
                foreach ($cat_map as $c_name => $c_id) {
                    if (strpos($c_name, $cat_name) !== false || strpos($cat_name, $c_name) !== false) {
                        $cat_id = $c_id;
                        break;
                    }
                }
            }

            // Fallback for unknown category
            if (!$cat_id) {
                $cat_id = isset($cat_map['others']) ? $cat_map['others'] : (isset($cat_map['general']) ? $cat_map['general'] : null);
            }

            if (!$cat_id) {
                $error_count++;
                $errors[] = "Row $row_count: Category '$cat_name' not found.";
                continue;
            }

            // Construct Name/Description from Make + Model
            $make = ($idx_make !== false && isset($row[$idx_make])) ? trim($row[$idx_make]) : '';
            $model = ($idx_model !== false && isset($row[$idx_model])) ? trim($row[$idx_model]) : '';
            $full_name = trim("$make $model");
            if (empty($full_name))
                $full_name = "Imported Item";

            // Condition Mapping
            $condition_val = ($idx_cond !== false && isset($row[$idx_cond])) ? strtolower(trim($row[$idx_cond])) : '';
            $condition = 'Good'; // Default
            if (stripos($condition_val, 'working') !== false)
                $condition = 'Working';
            if (stripos($condition_val, 'new') !== false)
                $condition = 'New';
            if (stripos($condition_val, 'faulty') !== false)
                $condition = 'Faulty';
            if (stripos($condition_val, 'scrap') !== false)
                $condition = 'Scrap';

            // Auto-generate Asset Tag
            // Format: {PREFIX}-YYYYMM-{RANDOM}
            $prefix = ($target_college && !empty($target_college->code)) ? strtoupper($target_college->code) : 'AST';
            $asset_tag = $prefix . '-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

            // Handle Monitor -> Remarks
            $remarks = ($idx_remarks !== false && isset($row[$idx_remarks])) ? trim($row[$idx_remarks]) : '';
            if ($idx_monitor !== false && !empty($row[$idx_monitor]) && $row[$idx_monitor] != '-') {
                $monitor_info = trim($row[$idx_monitor]);
                $remarks = "Monitor: $monitor_info. " . $remarks;
            }

            $asset_data = [
                'name' => $full_name,
                'category_id' => $cat_id,
                'brand_model' => "$make $model",
                'serial_number' => ($idx_serial !== false && isset($row[$idx_serial])) ? trim($row[$idx_serial]) : '',
                'asset_tag' => $asset_tag,
                'asset_condition' => $condition,
                'purchase_date' => date('Y-m-d'),
                'vendor' => '',
                'location' => ($idx_loc !== false && isset($row[$idx_loc])) ? trim($row[$idx_loc]) : '',
                'quantity' => 1,
                'status' => $target_college ? 'Deployed' : 'In Stock',
                'college_id' => $target_college ? $target_college->id : null,
                'created_by' => $this->session->userdata('user_id'),
                'processor' => ($idx_proc !== false && isset($row[$idx_proc])) ? trim($row[$idx_proc]) : '',
                'ram' => ($idx_ram !== false && isset($row[$idx_ram])) ? trim($row[$idx_ram]) : '',
                'hard_disk' => ($idx_hdd !== false && isset($row[$idx_hdd])) ? trim($row[$idx_hdd]) : '',
                'os' => ($idx_os !== false && isset($row[$idx_os])) ? trim($row[$idx_os]) : '',
                'amc_details' => ($idx_amc !== false && isset($row[$idx_amc])) ? trim($row[$idx_amc]) : '',
                'assigned_to' => ($idx_assign !== false && isset($row[$idx_assign])) ? trim($row[$idx_assign]) : '',
                'remarks' => trim($remarks)
            ];

            // Duplicate Serial Check
            if (!empty($asset_data['serial_number'])) {
                $this->db->where('serial_number', $asset_data['serial_number']);
                $exists = $this->db->get('assets')->row();
                if ($exists) {
                    $error_count++;
                    $errors[] = "Row $row_count: Serial '{$asset_data['serial_number']}' already exists.";
                    continue;
                }
            }

            $inserted_id = $this->Asset_model->insert($asset_data);
            if ($inserted_id) {
                $success_count++;
                $this->Audit_model->log_action('Imported Asset', 'Asset', $inserted_id, "Bulk import: {$asset_data['name']}");
            } else {
                $error_count++;
            }
        }

        if ($error_count > 0) {
            $msg = "Imported $success_count items successfully. $error_count items failed.";
            $this->session->set_flashdata('error', $msg . "<br>" . implode("<br>", array_slice($errors, 0, 5)));
        } else {
            $this->session->set_flashdata('success', "Successfully imported $success_count items!");
        }

        redirect('assets');
    }
    public function search_by_tag($tag)
    {
        $asset = $this->db->select('assets.*, categories.name as category_name')
            ->from('assets')
            ->join('categories', 'categories.id = assets.category_id', 'left')
            ->where('asset_tag', $tag)
            ->get()
            ->row();

        if ($asset) {
            echo json_encode(['status' => 'success', 'asset' => $asset]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Asset not found']);
        }
    }
    public function delete($id)
    {
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access!');
            redirect('dashboard');
        }

        $asset = $this->db->where('id', $id)->get('assets')->row();

        if ($this->Asset_model->delete($id)) {
            if ($asset) {
                $this->Audit_model->log_action('Moved to Trash', 'Asset', $id, "Item: {$asset->name}, Tag: {$asset->asset_tag}");
            }
            $this->session->set_flashdata('success', 'Asset moved to trash!');
        } else {
            $this->session->set_flashdata('error', 'Failed to move asset to trash.');
        }
        redirect('assets');
    }

    public function bulk_trash()
    {
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access!');
            redirect('dashboard');
        }

        $ids = $this->input->post('asset_ids');
        if (empty($ids)) {
            $this->session->set_flashdata('error', 'No items selected.');
            redirect('assets');
        }

        if ($this->Asset_model->bulk_trash($ids)) {
            $this->Audit_model->log_action('Bulk Trash', 'Asset', 0, count($ids) . " items moved to trash");
            $this->session->set_flashdata('success', count($ids) . ' items moved to trash!');
        } else {
            $this->session->set_flashdata('error', 'Failed to move items to trash.');
        }
        redirect('assets');
    }

    public function trash()
    {
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access!');
            redirect('dashboard');
        }

        $data['title'] = 'AZAM IT | Trash';
        $data['page_title'] = 'Trashed Items';
        $data['assets'] = $this->Asset_model->get_trashed();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('assets/trash', $data);
        $this->load->view('layout/footer');
    }

    public function bulk_restore()
    {
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access!');
            redirect('dashboard');
        }

        $ids = $this->input->post('asset_ids');
        if (empty($ids)) {
            $this->session->set_flashdata('error', 'No items selected.');
            redirect('assets/trash');
        }

        if ($this->Asset_model->bulk_restore($ids)) {
            $this->Audit_model->log_action('Bulk Restore', 'Asset', 0, count($ids) . " items restored");
            $this->session->set_flashdata('success', count($ids) . ' items restored successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to restore items.');
        }
        redirect('assets/trash');
    }

    public function restore($id)
    {
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access!');
            redirect('dashboard');
        }

        if ($this->Asset_model->restore($id)) {
            $this->Audit_model->log_action('Restored Asset', 'Asset', $id, "Item restored from trash");
            $this->session->set_flashdata('success', 'Asset restored successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to restore asset.');
        }
        redirect('assets/trash');
    }

    public function bulk_permanent_delete()
    {
        if ($this->session->userdata('role_id') != 1) { // Admin only
            $this->session->set_flashdata('error', 'Only System Admin can permanently delete items!');
            redirect('assets/trash');
        }

        $ids = $this->input->post('asset_ids');
        if (empty($ids)) {
            $this->session->set_flashdata('error', 'No items selected.');
            redirect('assets/trash');
        }

        if ($this->Asset_model->bulk_permanent_delete($ids)) {
            $this->Audit_model->log_action('Bulk Permanent Delete', 'Asset', 0, count($ids) . " items removed");
            $this->session->set_flashdata('success', count($ids) . ' items permanently deleted!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete items.');
        }
        redirect('assets/trash');
    }

    public function permanent_delete($id)
    {
        if ($this->session->userdata('role_id') != 1) { // Admin only
            $this->session->set_flashdata('error', 'Only System Admin can permanently delete items!');
            redirect('assets/trash');
        }

        if ($this->Asset_model->permanent_delete($id)) {
            $this->Audit_model->log_action('Permanent Delete', 'Asset', $id, "Item permanently removed");
            $this->session->set_flashdata('success', 'Asset permanently deleted!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete asset.');
        }
        redirect('assets/trash');
    }

    public function consolidate($id)
    {
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access!');
            redirect('dashboard');
        }
        if ($this->Asset_model->consolidate_stock($id)) {
            $this->Audit_model->log_action('Consolidated Batch', 'Asset', $id, "Merged all 'In Stock' units back to root record");
            $this->session->set_flashdata('success', 'Batch consolidated successfully! Rows merged into main record.');
        } else {
            $this->session->set_flashdata('error', 'Nothing to consolidate or operation failed.');
        }
        redirect('assets');
    }

    public function distribution($id)
    {
        $distribution = $this->Asset_model->get_batch_distribution($id);
        if (empty($distribution)) {
            $this->session->set_flashdata('error', 'Batch record not found.');
            redirect('assets');
        }

        $data['title'] = 'AZAM IT | Batch Distribution';
        $data['page_title'] = 'Batch Distribution Breakdown';
        $data['distribution'] = $distribution;
        $data['root_asset'] = $distribution[0]; // Ordered by created_at ASC, so index 0 is the original

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('assets/distribution', $data);
        $this->load->view('layout/footer');
    }

    public function consolidate_master()
    {
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access!');
            redirect('dashboard');
        }
        if ($this->Asset_model->auto_consolidate_stock()) {
            $this->Audit_model->log_action('Bulk Consolidation', 'System', 0, "Admin triggered full inventory stock merge");
            $this->session->set_flashdata('success', 'Master Inventory consolidated! All fragmented In-Stock items have been merged.');
        } else {
            $this->session->set_flashdata('error', 'Consolidation failed or no fragments found.');
        }
        redirect('assets');
    }

    public function allocate()
    {
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access!');
            redirect('dashboard');
        }
        $asset_id = $this->input->post('asset_id');
        $college_id = $this->input->post('college_id');
        $quantity = (int) $this->input->post('quantity');
        $return_date = $this->input->post('return_date') ? $this->input->post('return_date') : null;

        $asset = $this->db->where('id', $asset_id)->get('assets')->row();
        if (!$asset || $quantity <= 0) {
            $this->session->set_flashdata('error', 'Invalid allocation request.');
            redirect('assets');
        }

        $college = $college_id ? $this->College_model->get_by_id($college_id) : null;

        if ($this->Asset_model->split_and_allocate($asset_id, $college_id, $quantity, $return_date)) {
            $college_name = $college ? $college->name : 'Central Storage';
            $this->Audit_model->log_action('Allocated Asset', 'Asset', $asset_id, "Item: {$asset->name} x {$quantity} allocated to {$college_name}");
            $this->session->set_flashdata('success', "{$quantity} units of {$asset->name} successfully allocated to {$college_name}!");
        } else {
            $this->session->set_flashdata('error', 'Failed to allocate asset. Check stock quantity.');
        }

        redirect('assets');
    }

    public function bulk_allocate()
    {
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access!');
            redirect('dashboard');
        }
        $asset_ids = $this->input->post('asset_ids');
        $college_id = $this->input->post('college_id');
        $return_date = $this->input->post('return_date') ? $this->input->post('return_date') : null;

        if (empty($asset_ids) || !$college_id) {
            $this->session->set_flashdata('error', 'Please select assets and a target institution.');
            redirect('assets');
        }

        $college = $this->College_model->get_by_id($college_id);
        $college_name = $college ? $college->name : 'Unknown';
        $count = 0;

        foreach ($asset_ids as $id) {
            $asset = $this->db->where('id', $id)->get('assets')->row();
            // Allocate full quantity of the selected row
            if ($asset && $asset->status == 'In Stock' && $asset->quantity > 0) {
                if ($this->Asset_model->split_and_allocate($id, $college_id, $asset->quantity, $return_date)) {
                    $count++;
                    $this->Audit_model->log_action('Bulk Allocated', 'Asset', $id, "Item: {$asset->name} allocated to {$college_name}");
                }
            }
        }

        if ($count > 0) {
            $this->session->set_flashdata('success', "Successfully allocated $count items to $college_name.");
        } else {
            $this->session->set_flashdata('error', 'No valid in-stock items were selected for allocation.');
        }
        redirect('assets');
    }

    public function return_item($id)
    {
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access!');
            redirect('dashboard');
        }
        // Get asset details for logging
        $asset = $this->db->where('id', $id)->get('assets')->row();

        if ($this->Asset_model->return_asset($id)) {
            $this->Audit_model->log_action('Returned Asset', 'Asset', $id, "Item returned to stock: {$asset->name}");
            $this->session->set_flashdata('success', 'Asset returned to stock successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to return asset.');
        }

        // Redirect back to distribution if part of a batch, else assets list
        if ($asset && ($asset->parent_id || $this->db->where('parent_id', $asset->id)->count_all_results('assets') > 0)) {
            // It's part of a batch, find the root to redirect to distribution view
            $root_id = $asset->parent_id ? $asset->parent_id : $asset->id;
            redirect('assets/distribution/' . $root_id);
        }
        redirect('assets');
    }

    public function extend_loan()
    {
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access!');
            redirect('dashboard');
        }
        $id = $this->input->post('asset_id');
        $new_date = $this->input->post('return_date');

        $asset = $this->db->get_where('assets', ['id' => $id])->row();

        if ($this->Asset_model->extend_loan($id, $new_date)) {
            $this->Audit_model->log_action('Extended Loan', 'Asset', $id, "Loan extended for: {$asset->name} to $new_date");
            $this->session->set_flashdata('success', 'Loan duration extended successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to extend loan.');
        }

        $root_id = $asset->parent_id ? $asset->parent_id : $asset->id;
        redirect('assets/distribution/' . $root_id);
    }

    public function partial_return()
    {
        if (!in_array($this->session->userdata('role_id'), [1, 2])) {
            $this->session->set_flashdata('error', 'Unauthorized access!');
            redirect('dashboard');
        }
        $id = $this->input->post('asset_id');
        $qty = (int) $this->input->post('quantity');

        $asset = $this->db->get_where('assets', ['id' => $id])->row();

        if ($this->Asset_model->partial_return($id, $qty)) {
            $this->Audit_model->log_action('Partial Return', 'Asset', $id, "returned $qty units of {$asset->name} to stock");
            $this->session->set_flashdata('success', 'Partial return processed successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to process partial return. Invalid quantity.');
        }

        $root_id = $asset->parent_id ? $asset->parent_id : $asset->id;
        redirect('assets/distribution/' . $root_id);
    }

    public function edit($id)
    {
        if (!$this->session->userdata('logged_in')) {
            $redirect_url = current_url() . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '');
            $this->session->set_userdata('redirect_url', $redirect_url);
            redirect('auth/login');
        }

        $asset = $this->Asset_model->get_asset_by_id($id);
        if (!$asset) {
            show_404();
        }

        $data['title'] = 'AZAM IT | Edit Item';
        $data['page_title'] = 'Edit Inventory Item';
        $data['asset'] = $asset;
        $data['categories'] = $this->Category_model->get_all();
        $data['colleges'] = $this->College_model->get_all();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('assets/edit', $data);
        $this->load->view('layout/footer');
    }

    public function update()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $id = $this->input->post('id');
        $this->form_validation->set_rules('name', 'Item Name', 'required');
        $this->form_validation->set_rules('category_id', 'Category', 'required');
        $this->form_validation->set_rules('asset_tag', 'Asset Tag', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('assets/edit/' . $id);
        } else {
            $update_data = array(
                'name' => $this->input->post('name'),
                'category_id' => $this->input->post('category_id'),
                'sub_category' => $this->input->post('sub_category'),
                'brand_model' => $this->input->post('brand_model'),
                'processor' => $this->input->post('processor'),
                'ram' => $this->input->post('ram'),
                'hard_disk' => $this->input->post('hard_disk'),
                'os' => $this->input->post('os'),
                'serial_number' => $this->input->post('serial_number'),
                'asset_tag' => $this->input->post('asset_tag'),
                'asset_condition' => $this->input->post('asset_condition'),
                'purchase_date' => $this->input->post('purchase_date'),
                'vendor' => $this->input->post('vendor'),
                'warranty_expiry' => $this->input->post('warranty_expiry'),
                'location' => $this->input->post('location'),
                'status' => $this->input->post('status'),
                'assigned_to' => $this->input->post('assigned_to'),
                'amc_details' => $this->input->post('amc_details'),
                'remarks' => $this->input->post('remarks')
            );

            if ($this->Asset_model->update_asset($id, $update_data)) {
                $this->Audit_model->log_action('Updated Item', 'Asset', $id, "Updated details for {$update_data['asset_tag']}");
                $this->session->set_flashdata('success', 'Asset updated successfully!');
                redirect('assets/view_details/' . $update_data['asset_tag']);
            } else {
                $this->session->set_flashdata('error', 'Failed to update asset.');
                redirect('assets/edit/' . $id);
            }
        }
    }

    public function view_details($tag)
    {
        $this->db->select('assets.*, categories.name as category_name, colleges.name as college_name');
        $this->db->from('assets');
        $this->db->join('categories', 'categories.id = assets.category_id', 'left');
        $this->db->join('colleges', 'colleges.id = assets.college_id', 'left');
        $this->db->where('asset_tag', $tag);
        $asset = $this->db->get()->row();

        if (!$asset) {
            show_404();
        }

        $data['title'] = "Asset Info: " . $asset->asset_tag;
        $data['asset'] = $asset;

        $this->load->view('assets/view_details', $data);
    }

}
