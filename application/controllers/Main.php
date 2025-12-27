<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();

        if ($this->db->conn_id === FALSE) {
            show_error(
                'Database is currently offline. Please start MySQL / XAMPP.',
                503,
                'Service Unavailable'
            );
            exit;
        }
        $this->redirect_home();
    }

    public function index()
    {
        redirect(base_url('index'));
    }

    public function page_not_found()
    {
        $data = $this->system();
        $data += [
            "page_title"    => "Page Not Found"
        ];
        $this->load->view('interface/errors/cli/error_404', $data);
    }

    public function allow()
    {
        $this->allow_schema();
    }
}
