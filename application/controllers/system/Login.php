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
            "SELECT t1.id,t1.password, t1.person_id,t1.username,t2.level, t3.first_name, t3.last_name,
                                    CASE WHEN t4.id IS NOT NULL AND t4.approved_at IS NULL THEN 1 ELSE 0 END as is_registered_farmer,
                                    'f' AS change_pwd, t1.is_active
                                    FROM public.user t1
                                    LEFT JOIN public.role t2 ON t1.role_id = t2.id
                                    LEFT JOIN public.person t3 ON t1.person_id = t3.id
                                    LEFT JOIN public.farmer t4 ON t3.id = t4.person_id
                                    WHERE t1.password = ? AND t1.username = ? AND t1.is_active = true LIMIT 1",
            array($password, $username)
        );

        if ($chck->num_rows() > 0) {
            $row1 = $chck->row();
            $person_id = $row1->person_id;
            if ($row1->is_active == true) {

                $data += [
                    "agrishop_login_district"   => '',
                    "agrishop_login_schl_id"    => '',
                    "agrishop_login_school_id"  => '',
                    "agrishop_login_schl_name"  => '',
                    "agrishop_login_schl_type"  => '',
                    "agrishop_login_abbrv"      => '',

                    "agrishop_request_registration" => $row1->is_registered_farmer,
                    "agrishop_person_id"        => $person_id, // $query->row('id'),
                    "agrishop_login_id"         => $row1->id, // $query->row('id'),
                    "agrishop_login_uname"      => $row1->username, // $query->row('username'),
                    "agrishop_login_level"      => $row1->level, // $value->level,
                    "agrishop_login_uri"        => ($row1->change_pwd == 't' ? "ud440aed189" : ($row1->level == 0 ? "useradmin" : ($row1->level == 1 ? "userconsumer" : ($row1->level == 2 ? "userfarmer" : "")))),

                    "agrishop_login_landing"    => $row1->change_pwd == 't' ? "changepassword" : ($row1->level == 2 ? "farmproduce" : "dataentry"), //($value->level==2?"dataentry":"dashboard"),
                    "agrishop_pass"             => $row1->password, // $query->row('password'),
                    "agrishop_change_password"  => $row1->change_pwd, // $query->row('change_password'),
                    "agrishop_login_name"       => 'AAAA', #$row2->full_name, // $this->personName($query->row('person_id'),'n'),
                    "agrishop_login_img"        => '', #$this->getImg($row2->img_path), // $this->personName($query->row('person_id'),'n'),
                ];

                // if ($row1->role_id != 8) {
                //     $data += [
                //         "agrishop_login_prsnnl_Id"  => '',#$row2->schoolpersonnel_id, // $this->personName($query->row('person_id'),'n'),
                //         "agrishop_login_title"      => '',#$row2->personal_title, // $this->personName($query->row('person_id'),'p'),
                //         "agrishop_login_district"   => '',#$row2->district_name, // $query->row('district_id'),
                //         "agrishop_login_schl_id"    => '',#$row2->school_id_num, // $query->row('district_id'),
                //         "agrishop_login_school_id"  => '',#$row2->school_id, // $query->row('district_id'),
                //         "agrishop_login_schl_name"  => '',#$row2->school_name, // $query->row('district_id'),
                //         "agrishop_login_schl_type"  => '',#$row2->school_type, // $query->row('district_id'),
                //         "agrishop_login_abbrv"      => '',#$row2->abbr, // $query->row('district_id'),
                //         "agrishop_login_dept_id"    => '',#$row2->school_department_id, // $query->row('district_id'),
                //         "agrishop_login_dept_name"  => '',#$row2->dept_name, // $query->row('district_id'),
                //     ];
                // }

                // if ($row1->role_id == 8) {
                //     $data += [
                //         "agrishop_login_learner_id" => '',#$row2->learner_id, // $this->personName($query->row('person_id'),'n'),
                //         "agrishop_login_lrn"        => '',#$row2->lrn, // $this->personName($query->row('person_id'),'p'),
                //         "agrishop_login_prsnnl_Id"  => '',#$row2->person_id, // $query->row('district_id'),
                //         "agrishop_login_prsn_uuid"  => '',#$row2->person_uuid, // $query->row('district_id'),
                //         "agrishop_login_rm_sec_id"  => '',#$row2->rm_sec_id, // $query->row('district_id'),
                //     ];
                // }

                $this->session->set_userdata($data);

                $level = $this->session->agrishop_login_level;
                $defaultPassword = $this->session->agrishop_change_password;
                $uri = $this->session->agrishop_login_uri;
                $landing = $this->session->agrishop_login_landing;

                if ($level != "") {
                    if ($defaultPassword == 't') {
                        redirect(base_url('ud440aed189/changepassword'));
                    } else {
                        $row1->level == 2 ? redirect(base_url($uri . '/' . $landing)) : redirect(base_url('index'));

                        // ($query->row('level')==1?redirect(base_url($uri.'/dataentry')):redirect(base_url($uri.'/dashboard')));
                    }
                }
            } else {
                redirect(base_url() . 'login?login_attempt=' . md5(0));
            }
        } else {
            redirect(base_url() . 'login?login_attempt=' . md5(0));
        }
    }

    public function request_logout()
    {
        // $this->userlog("USER HAS LOGGED OUT.");
        // if ($this->session->agrishop_login_lrn) {
        //     $this->learnerlog("LEARNER HAS LOGGED OUT.");
        // }

        $array_logout = [
            "agrishop_login_id"             => '',
            "agrishop_login_uname"          => '',
            "agrishop_login_level"          => '',
            "agrishop_login_uri"            => '',
            "agrishop_login_landing"        => '',
            "agrishop_login_prsnnl_Id"      => '',


            //role_id != 8;
            "agrishop_login_name"           => '',
            "agrishop_login_title"          => '',
            "agrishop_login_district"       => '',
            "agrishop_login_schl_id"        => '',
            "agrishop_login_schl_name"      => '',
            "agrishop_login_schl_type"      => '',
            "agrishop_login_abbrv"          => '',
            //role_id = 8;
            "agrishop_login_learner_id"     => '',
            "agrishop_login_lrn"            => '',
            "agrishop_login_prsn_uuid"      => '',
            "agrishop_login_rm_sec_id"      => '',

            "agrishop_pass"                 => '',
            "agrishop_change_password"      => '',
        ];
        $this->session->unset_userdata($array_logout);
        $this->session->sess_destroy();
        redirect(base_url());
    }
}

/* End of file Login_admin.php */
/* Location: ./application/controllers/system/Login_admin.php */