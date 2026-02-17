<?php
class Show_cats extends CI_Controller
{
    public function index()
    {
        $cats = $this->db->get('categories')->result();
        foreach ($cats as $c) {
            echo $c->id . " : " . $c->name . "\n";
        }
    }
}
