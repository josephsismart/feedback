<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->redirect();
    }

    public function index()
    {       
        // $page_data = $this->system();
        // $uri = $this->session->feedback_login_uri;
        // $page_data += [
        //     "page_title"        => "Dashboard",
        //     "current_location"  => "dashboard",
        //     "content"           =>  [$this->load->view('interface/admin/Dashboard', [
        //                             ], TRUE)]
        // ];
        // $this->public_create_page($page_data);


        $this->redirect_home();
        $data = $this->system();
        $data += [
            "page_title"    => "Dashboard",
            "current_location"  => "dashboard",
        ];
        $this->load->view('interface/admin/Dashboard', $data);
    }
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */