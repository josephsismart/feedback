<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->redirect_home();
        $data = $this->system();
        $data += [
            "page_title"    => "Login",
            "current_location"  => "login",
        ];
        $this->load->view('interface/system/Login', $data);
    }

    public function request_login()
    {
        // $sy = $this->getOnLoad()["sy_id"];
        $username = $this->input->post('username');
        $password = md5($this->input->post('password')); //md5($this->input->post('password'));
        $row1 = "";
        $row2 = "";
        $result = "";
        $data = [];
        // Use prepared statements to prevent SQL injection
        $chck = $this->db->query(
            "SELECT t1.id,t1.password, t1.name, t1.username FROM user t1 
                                     WHERE t1.password = ? AND t1.username = ? LIMIT 1",
            array($password, $username)
        );

        if ($chck->num_rows() > 0) {
            $row1 = $chck->row();

            $data += [
                "feedback_login_id"         => $row1->id, // $query->row('id'),
                "feedback_login_uname"      => $row1->username, // $query->row('username'),
                "feedback_login_name"       => $row1->name, // $value->level,
                "feedback_login_uri"        => 'admin',
                "feedback_login_landing"    => 'dashboard',
            ];

            $this->session->set_userdata($data);

            $login_id = $this->session->feedback_login_id;
            $uri = $this->session->feedback_login_uri;
            $landing = $this->session->feedback_login_landing;

            if ($login_id != "") {
                redirect(base_url($uri . '/' . $landing));
            }
        } else {
            redirect(base_url() . 'login?login_attempt=' . md5(0));
        }
    }

    public function request_logout()
    {
        // $this->userlog("USER HAS LOGGED OUT.");
        // if ($this->session->feedback_login_lrn) {
        //     $this->learnerlog("LEARNER HAS LOGGED OUT.");
        // }

        $array_logout = [
            "feedback_login_id"             => '',
            "feedback_login_uname"          => '',
            "feedback_login_level"          => '',
            "feedback_login_uri"            => '',
            "feedback_login_landing"        => '',
            "feedback_login_prsnnl_Id"      => '',


            //role_id != 8;
            "feedback_login_name"           => '',
            "feedback_login_title"          => '',
            "feedback_login_district"       => '',
            "feedback_login_schl_id"        => '',
            "feedback_login_schl_name"      => '',
            "feedback_login_schl_type"      => '',
            "feedback_login_abbrv"          => '',
            //role_id = 8;
            "feedback_login_learner_id"     => '',
            "feedback_login_lrn"            => '',
            "feedback_login_prsn_uuid"      => '',
            "feedback_login_rm_sec_id"      => '',

            "feedback_pass"                 => '',
            "feedback_change_password"      => '',
        ];
        $this->session->unset_userdata($array_logout);
        $this->session->sess_destroy();
        redirect(base_url());
    }
}

/* End of file Login_admin.php */
/* Location: ./application/controllers/system/Login_admin.php */