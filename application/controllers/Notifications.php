<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            // Return 401 or just empty JSON if not logged in
            echo json_encode(['count' => 0, 'alerts' => []]);
            exit;
        }
        $this->load->model('Stats_model');
    }

    public function fetch()
    {
        // 1. Get Due Due Soon (Next 7 days)
        $due_soon = $this->Stats_model->get_loans_due_soon(7);

        // 2. Get Low Stock (Threshold < 5)
        $low_stock = $this->Stats_model->get_low_stock(5);

        $alerts = [];

        // Format Due Soon
        foreach ($due_soon as $item) {
            $days_left = ceil((strtotime($item->return_date) - time()) / 60 / 60 / 24);
            $msg = ($days_left < 0) ? "Overdue by " . abs($days_left) . " days" : "Due in $days_left days";
            $priority = ($days_left <= 2) ? 'high' : 'medium';

            $alerts[] = [
                'type' => 'due_soon',
                'title' => 'Return Due: ' . $item->name,
                'message' => "Tag: {$item->asset_tag} ($msg)",
                'time' => date('M d', strtotime($item->return_date)),
                'link' => site_url('assets'), // or specific filtering link
                'icon' => 'calendar_clock',
                'color' => 'orange',
                'priority' => $priority
            ];
        }

        // Format Low Stock
        foreach ($low_stock as $item) {
            $alerts[] = [
                'type' => 'low_stock',
                'title' => 'Low Stock Alert',
                'message' => "Only {$item->quantity} units left for {$item->name}",
                'time' => 'Now',
                'link' => site_url('assets?status=In+Stock'),
                'icon' => 'inventory',
                'color' => 'red',
                'priority' => 'high'
            ];
        }

        echo json_encode([
            'count' => count($alerts),
            'alerts' => $alerts
        ]);
    }
}
