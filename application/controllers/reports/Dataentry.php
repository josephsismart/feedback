<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dataentry extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->redirect();
        $this->load->model('mainModel');
        $this->load->helper('date');
        date_default_timezone_set("Asia/Manila");
    }

    public function index()
    {
        $page_data = $this->system();
        $uri = $this->session->schoolmis_login_uri;
        $page_data += [
            "page_title"        => "Dashboard",
            "current_location"  => "dataentry",
            "content"           =>  [$this->load->view('interface/' . $uri . '/Dataentry', [
                "getOnLoad" => $this->getOnLoad(),
                "getSHdboard" => $this->getSHdboard(),
            ], TRUE)]
        ];
        $this->public_create_page($page_data);
    }

    function submitGrades()
    {

        $this->db->trans_begin();
        $true = ["success"   => true];
        $false = ["success"   => false];
        $sy = $this->getOnLoad()["sy_id"];
        $qrtr = $this->getOnLoad()["qrtr"];
        $login_id = $this->session->schoolmis_login_id;

        parse_str($this->input->post("c"), $filter);
        $status = $filter['status'];
        $stat = $status == "APPROVE" ? 18 : ($status == "RECHECK" ? 20 : null);
        // $password = md5($filter['password']);
        // $pass = $this->session->schoolmis_pass;

        // if ($pass == $password) {
        if ($sy && $qrtr && $login_id && $stat) {
            //1 3221232
            //2 2123221
            //3 3211123
            //4 4522323
            $remarks = strtoupper($filter["remarks"]);
            $qrssa = $filter["qrssa"];
            $rssa_id = $this->input->post("e");
            $q = $qrssa == 3221232 ? 1 : ($qrssa == 2123221 ? 2 : ($qrssa == 3211123 ? 3 : ($qrssa == 4522323 ? 4 : null)));


            $data = [
                "is_active" => false
            ];

            // $this->db->where('rssa_id', $rssa_id);
            if ($this->db->query("UPDATE sy$sy.bs_tbl_learner_grades_stat$sy 
                                  SET is_active=false WHERE rssa_id=$rssa_id AND qrtr=$q AND sy_id=$sy")) {
                $data1 = [
                    "rssa_id" => $rssa_id,
                    "status_id" => $stat,
                    "sy_id" => $sy,
                    "qrtr" => $q,
                    "remarks" => $remarks,
                    "added_by" => $login_id,
                ];
                if ($this->db->insert("sy$sy.bs_tbl_learner_grades_stat$sy", $data1)) {
                    $true += ["message"   => "Successfully submitted!"];
                    $ret = $true;
                }
            }
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
        } else {
            $false += ["message"   => "Password mismatch!"];
            $ret = $false;
        }

        echo json_encode($ret);
    }
}