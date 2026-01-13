<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->redirect();
    }

    public function index()
    {

        $this->redirect();
        $data = $this->system();
        $data += [
            "page_title"    => "Controller",
            "current_location"  => "controller",
        ];
        $this->load->view('interface/admin/Controller', $data);
    }

    function getSector()
    {
        $requestData = $_REQUEST;
        $person_id  = $this->session->feedback_login_id;
        $searchValue = isset($requestData['search']['value']) ? $requestData['search']['value'] : '';

        // Calculate pagination parameters using the separate function
        list($limit, $offset) = $this->calculatePagination($requestData);

        // Query to get total record count
        $thisQuery = $this->db->query("SELECT COUNT(1) AS total
                                            FROM sector p
                                        WHERE name LIKE '%$searchValue%' OR description LIKE '%$searchValue%'");

        $totalRecords = $thisQuery->row()->total;

        $query = $this->db->query("SELECT * FROM sector
                                    WHERE name LIKE '%$searchValue%' OR description LIKE '%$searchValue%'
                                    ORDER BY id DESC, name
                                    LIMIT $limit OFFSET $offset");

        $data = array();
        $cc = $offset + 1;
        foreach ($query->result() as $key => $value) {
            $is_a_v = $value->is_active;
            $img = $value->img_path ? base_url($value->img_path) : base_url("dist/img/SMCCnewlogo_5x6.png");
            $is_active = $is_a_v == 1 ? "<span class='badge bg-success'>ACTIVE</span>" : "<span class='badge bg-danger'>INACTIVE</span>";
            $image_path = "<img src='$img' width='55' height='55' class='rounded' data-toggle='tooltip' data-placement='top' title=''>";
            $name = "<a href='javascript:void(0)'
                        class='text-decoration-none'
                        onclick='edit(
                            \"#form_save_dataSector\",
                            " . json_encode($value) . "
                        )'>
                        <i class='fas fa-pencil-alt'></i>
                    </a> " . $value->name;
            $data[] = array(
                $image_path,
                $name,
                $is_active,
            );
        } // Prepare the response data in the required format
        $response = array(
            'draw' => intval($requestData['draw']),
            'recordsTotal' => intval($totalRecords),
            'recordsFiltered' => intval($totalRecords), // For simplicity, assuming no filtering is applied
            'data' => $data,
        );
        echo json_encode($response);
    }

    function saveSector()
    {
        $this->db->trans_begin();
        $true = ["success"   => true];
        $false = ["success"   => false];
        $data = [];
        $id = $this->input->post("id");
        $name = strtoupper($this->input->post("name"));
        $active = $this->input->post("is_active");

        if ($id == "" || $id == null) {
            $exist = $this->db->query("SELECT * FROM sector WHERE name = '$name'")->num_rows();
            if ($exist > 0) {
                $false += ["message"   => "Sector already exists!", "exist"   => true];
                $ret = $false;
                echo json_encode($ret);
                return;
            }
        }

        $data = [
            "name" => $name,
            "is_active" => $active,
            "created_by_person_id" => $this->session->feedback_login_id,
            "is_active" => $id == "" ? 1 : $active
        ];

        if (isset($_FILES['picSector']) && $_FILES['picSector']['error'] === UPLOAD_ERR_OK) {
            // Normal upload
            $upload = $this->uploadImg($_FILES['picSector'], $name, 'sector', 'picSector');
            $data += [
                "img_path" => $upload
            ];
        }
        $isSuccess = false;

        if (!empty($id)) {
            // update
            $isSuccess = $this->db->update("sector", $data, ["id" => $id]);
        } else {
            // insert
            $isSuccess = $this->db->insert("sector", $data);
        }

        $ret = $isSuccess
            ? ($true  + ["message" => "Successfully saved!"])
            : ($false + ["message" => "Something went wrong!"]);


        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode($ret);
    }

    function getCategory()
    {
        $requestData = $_REQUEST;
        $person_id  = $this->session->feedback_login_id;
        $searchValue = isset($requestData['search']['value']) ? $requestData['search']['value'] : '';

        // Calculate pagination parameters using the separate function
        list($limit, $offset) = $this->calculatePagination($requestData);

        // Query to get total record count
        $thisQuery = $this->db->query("SELECT COUNT(1) AS total
                                            FROM category p
                                            LEFT JOIN category c ON p.parent_id = c.id
                                        WHERE p.name LIKE '%$searchValue%' OR c.name LIKE '%$searchValue%'");

        $totalRecords = $thisQuery->row()->total;

        $query = $this->db->query("SELECT p.*, c.name as parent_name FROM category p
                                    LEFT JOIN category c ON p.parent_id = c.id
                                    WHERE p.name LIKE '%$searchValue%' OR c.name LIKE '%$searchValue%'
                                    ORDER BY p.parent_id ASC, p.order_by ASC
                                    LIMIT $limit OFFSET $offset");

        $data = array();
        $cc = $offset + 1;
        foreach ($query->result() as $key => $value) {
            $is_a_v = $value->is_active;
            $img = $value->img_path ? base_url($value->img_path) : base_url("dist/img/SMCCnewlogo_5x6.png");
            $is_active = $is_a_v == 1 ? "<span class='badge bg-success'>ACTIVE</span>" : "<span class='badge bg-danger'>INACTIVE</span>";
            $image_path = "<img src='$img' width='55' height='55' class='rounded' data-toggle='tooltip' data-placement='top' title=''>";
            $name = "<a href='javascript:void(0)'
                        class='text-decoration-none'
                        onclick='edit(
                            \"#form_save_dataCategory\",
                            " . json_encode($value) . "
                        )'>
                        <i class='fas fa-pencil-alt'></i>
                    </a> " . $value->name;
            $parent_name = $value->parent_id ? $value->parent_name : "-";


            $data[] = array(
                $image_path,
                $name,
                $parent_name,
                $is_active,
            );
        } // Prepare the response data in the required format
        $response = array(
            'draw' => intval($requestData['draw']),
            'recordsTotal' => intval($totalRecords),
            'recordsFiltered' => intval($totalRecords), // For simplicity, assuming no filtering is applied
            'data' => $data,
        );
        echo json_encode($response);
    }

    function saveCategory()
    {
        $this->db->trans_begin();
        $true = ["success"   => true];
        $false = ["success"   => false];
        $data = [];
        $id = $this->input->post("id");
        $name = strtoupper($this->input->post("name"));
        $parent_id = $this->input->post("parent_id");
        $active = $this->input->post("is_active");

        if ($id == "" || $id == null) {
            $exist = $this->db->query("SELECT * FROM category WHERE name = '$name'")->num_rows();
            if ($exist > 0) {
                $false += ["message"   => "Sector already exists!", "exist"   => true];
                $ret = $false;
                echo json_encode($ret);
                return;
            }
        }

        $data = [
            "name" => $name,
            "parent_id" => $parent_id == 0 || $parent_id == "" ? null : $parent_id,
            "is_active" => $active,
            "created_by_person_id" => $this->session->feedback_login_id,
            "is_active" => $id == "" ? 1 : $active
        ];

        if (isset($_FILES['picCategory']) && $_FILES['picCategory']['error'] === UPLOAD_ERR_OK) {
            // Normal upload
            $upload = $this->uploadImg($_FILES['picCategory'], $name, 'category', 'picCategory');
            $data += [
                "img_path" => $upload
            ];
        }
        $isSuccess = false;

        if (!empty($id)) {
            // update
            $isSuccess = $this->db->update("category", $data, ["id" => $id]);
        } else {
            // insert
            $isSuccess = $this->db->insert("category", $data);
        }

        $ret = $isSuccess
            ? ($true  + ["message" => "Successfully saved!"])
            : ($false + ["message" => "Something went wrong!"]);


        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode($ret);
    }

    function getSentimentWord()
    {
        $requestData = $_REQUEST;
        $searchValue = isset($requestData['search']['value']) ? $requestData['search']['value'] : '';

        // Calculate pagination parameters using the separate function
        list($limit, $offset) = $this->calculatePagination($requestData);

        // Query to get total record count
        $thisQuery = $this->db->query("SELECT COUNT(1) AS total
                                            FROM sentiment_words 
                                        WHERE word LIKE '%$searchValue%' or type LIKE '%$searchValue%'");

        $totalRecords = $thisQuery->row()->total;

        $query = $this->db->query("SELECT * FROM sentiment_words 
                                    WHERE word LIKE '%$searchValue%' or type LIKE '%$searchValue%'
                                    ORDER BY id DESC
                                    LIMIT $limit OFFSET $offset");

        $data = array();
        $cc = $offset + 1;
        foreach ($query->result() as $key => $value) {
            $is_positive = $value->type == 'positive' || $value->type == 'POSITIVE' ? "<span class='badge bg-success'>POSITIVE</span>" : "<span class='badge bg-danger'>NEGATIVE</span>";
            $word = "<a href='javascript:void(0)'
                        class='text-decoration-none'
                        onclick='edit(
                            \"#form_save_dataSentimentWord\",
                            " . json_encode($value) . "
                        )'>
                        <i class='fas fa-pencil-alt'></i>
                    </a> " . strtoupper($value->word);


            $data[] = array(
                $cc++,
                $word,
                $is_positive,
            );
        } // Prepare the response data in the required format
        $response = array(
            'draw' => intval($requestData['draw']),
            'recordsTotal' => intval($totalRecords),
            'recordsFiltered' => intval($totalRecords), // For simplicity, assuming no filtering is applied
            'data' => $data,
        );
        echo json_encode($response);
    }


    function saveSentimentWord()
    {
        $this->db->trans_begin();
        $true = ["success"   => true];
        $false = ["success"   => false];
        $data = [];
        $id = $this->input->post("id");
        $word = strtoupper($this->input->post("word"));
        $type_int = $this->input->post("type_int");

        if ($id == "" || $id == null) {
            $exist = $this->db->query("SELECT * FROM sentiment_words WHERE word = '$word'")->num_rows();
            if ($exist > 0) {
                $false += ["message"   => "Sentiment Word already exists!", "exist"   => true];
                $ret = $false;
                echo json_encode($ret);
                return;
            }
        }

        $data = [
            "word" => $word,
            "type" => $type_int == 1 ? "positive" : "negative",
            "type_int" => $type_int,
        ];

        $isSuccess = false;

        if (!empty($id)) {
            // update
            $isSuccess = $this->db->update("sentiment_words", $data, ["id" => $id]);
        } else {
            // insert
            $isSuccess = $this->db->insert("sentiment_words", $data);
        }

        $ret = $isSuccess
            ? ($true  + ["message" => "Successfully saved!"])
            : ($false + ["message" => "Something went wrong!"]);


        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode($ret);
    }


    

    
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */