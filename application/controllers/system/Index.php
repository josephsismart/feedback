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
            "page_title"    => "Online Feedback Form",
            "current_location"  => "Index",
        ];
        $this->load->view('interface/system/Index', $data);
    }

    public function get_categories($sector_id)
    {
        $data = $this->db
            ->where('parent_id', NULL)
            ->where('is_active', 1)
            ->order_by('order_by', 'ASC')
            ->get('category')
            ->result_array();

        echo json_encode($data);
    }


    public function get_subcategories($parent_id)
    {
        $data = $this->db
            ->where('parent_id', $parent_id)
            ->where('is_active', 1)
            ->order_by('order_by', 'ASC')
            ->get('category')
            ->result();

        echo json_encode($data);
    }

    public function SubmitSurveyForm()
    {
        $this->db->trans_begin();
        $data_service = [];
        $true = ["success"   => true];
        $false = ["success"   => false];

        // ===== Insert FEEDBACK main data =====
        $data = [
            "sector_id" => $this->input->post("sector_id"),
            "category_id" => $this->input->post("category_id"),
            "date_created" => date("Y-m-d"),
            "name" => strtoupper($this->input->post("name")),
            "address" => strtoupper($this->input->post("address")),
            "sex" => strtoupper($this->input->post("sex")),
            "contact_number" => strtoupper($this->input->post("contact_number")),
            "comment" => $this->input->post("comment"),
            "ip_address" => $this->get_ip(),
            "unique_device_id" => $this->input->post("getmyid"),
        ];

        $this->db->insert("feedback", $data);
        $feedback_id = $this->db->insert_id(); // GET FEEDBACK ID

        if ($feedback_id) {

            $comment = $this->input->post("comment");

            // CALL stored procedure
            $sql = "CALL sp_evaluate_feedback_sentiment(?, ?)";
            $this->db->query($sql, [$feedback_id, $comment]);

            // IMPORTANT: clear remaining results (MySQL requirement)
            while ($this->db->conn_id->more_results()) {
                $this->db->conn_id->next_result();
            }
        }

        // ===== Insert FEEDBACK SERVICE ratings =====
        foreach ($_POST as $key => $value) {

            // Detect only service_id fields
            if (strpos($key, "service_id_") === 0) {

                $service_id = str_replace("service_id_", "", $key);
                $rating_int = $value; // Hidden value (1–4)

                // Get the name used by radio (md5)
                $radioName = "rate_" . md5($service_id);
                $rating_txt = $this->input->post($radioName);

                if ($rating_txt && $rating_int) {
                    $data_service[] = [
                        "feedback_id" => $feedback_id,
                        "service_id"  => $service_id,
                        "rating_int"  => $rating_int,
                        "rating_txt"  => $rating_txt
                    ];
                }
            }
        }
        if ($this->db->insert_batch("feedback_service", $data_service)) {
            $true += ["message"   => "Successfully created!"];
            $ret = $true;
        } else {
            $false += ["message"   => "Something went wrong!"];
            $ret = $false;
        }


        // ===== Final response =====
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(["success" => false, "message" => "Something went wrong!"]);
        } else {
            $this->db->trans_commit();
            echo json_encode(["success" => true, "message" => "Successfully created!"]);
        }
    }
}

/* End of file Login_admin.php */
/* Location: ./application/controllers/system/Login_admin.php */