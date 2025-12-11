<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Index extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mainModel');
        $this->load->helper('date');
        date_default_timezone_set("Asia/Manila");
    }

    public function index()
    {
        $this->redirect_home();
        $data = $this->system();
        $data += [
            "page_title"    => "Fresh Organic | Farm-to-Table",
            "current_location"  => "Index",
        ];
        $this->load->view('interface/system/Index', $data);
    }

    public function get_subcategories()
    {
        $parent_id = $this->input->get('parent_id');
        $categories = $this->db->where('parent_id', $parent_id)
            ->where('is_active', TRUE)
            ->order_by('order_by', 'ASC')
            ->get('category')
            ->result_array();
        echo json_encode($categories);
    }
}

/* End of file Login_admin.php */
/* Location: ./application/controllers/system/Login_admin.php */