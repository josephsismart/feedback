<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Getdata extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->redirect2();
        $this->load->model('mainModel');
        $this->load->helper('date');
        date_default_timezone_set("Asia/Manila");
    }


    function getPartyList()
    {
        $v = $this->input->post("v");
        $filter = $v == '' || $v == null ? 0 : $v;
        $data = $this->PartyList($filter);
        echo json_encode($data);
    }
}

/* End of file Login_admin.php */
/* Location: ./application/controllers/system/Login_admin.php */