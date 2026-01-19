<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Useraccount extends MY_Controller
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
            "page_title"    => "Useraccount",
            "current_location"  => "useraccount",
        ];
        $this->load->view('interface/admin/Useraccount', $data);
    }


    function getUserAccount()
    {
        $requestData = $_REQUEST;
        $searchValue = isset($requestData['search']['value']) ? $requestData['search']['value'] : '';

        // Calculate pagination parameters using the separate function
        list($limit, $offset) = $this->calculatePagination($requestData);

        // Query to get total record count
        $thisQuery = $this->db->query("SELECT COUNT(1) AS total
                                            FROM user u
                                            LEFT JOIN user_type ut ON u.user_type_id = ut.id
                                        WHERE (u.id>1) AND (u.name LIKE '%$searchValue%' OR u.username LIKE '%$searchValue%' OR ut.name LIKE '%$searchValue%')");

        $totalRecords = $thisQuery->row()->total;

        $query = $this->db->query("SELECT 
                                        u.*,
                                        ut.name as user_type_name,
                                        ut.id as user_type_id,
                                        u.p_char as password,
                                        u.p_char as confirm_password
                                    FROM user u
                                    LEFT JOIN user_type ut ON u.user_type_id = ut.id
                                    WHERE (u.id>1) AND (u.name LIKE '%$searchValue%' OR u.username LIKE '%$searchValue%' OR ut.name LIKE '%$searchValue%')
                                    ORDER BY u.id DESC
                                    LIMIT $limit OFFSET $offset");

        $data = array();
        $cc = $offset + 1;
        foreach ($query->result() as $key => $value) {
            $user = "<a href='javascript:void(0)'
                        class='text-decoration-none'
                        onclick='edit(
                            \"#form_save_dataUserAccount\",
                            " . json_encode($value) . "
                        )'>
                        <i class='fas fa-pencil-alt'></i>
                    </a> " . strtoupper($value->name);


            $data[] = array(
                $cc++,
                $user,
                $value->user_type_name,
                $value->username,
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


    function saveUserAccount()
    {
        $this->db->trans_begin();
        $true = ["success"   => true];
        $false = ["success"   => false];
        $data = [];
        $id = $this->input->post("id");
        $name = strtoupper($this->input->post("name"));
        $username = $this->input->post("username");
        $user_type_id = $this->input->post("user_type_id");
        $password = $this->input->post("password");
        $confirm_password = $this->input->post("confirm_password");

        if ($password != $confirm_password) {
            $false += ["message"   => "Password does not match!", "exist"   => true];
            $ret = $false;
            echo json_encode($ret);
            return;
        }

        $exist = $this->db->query("SELECT * FROM user WHERE username = '$username'")->num_rows();
        if ($exist > 0) {
            $false += ["message"   => "Username already exists!", "exist"   => true];
            $ret = $false;
            echo json_encode($ret);
            return;
        }

        if ($id == "" || $id == null) {
            $exist = $this->db->query("SELECT * FROM user WHERE username = '$username'")->num_rows();
            if ($exist > 0) {
                $false += ["message"   => "Username already exists!", "exist"   => true];
                $ret = $false;
                echo json_encode($ret);
                return;
            }
        }

        $data = [
            "name" => $name,
            "username" => $username,
            "user_type_id" => $user_type_id,
            "password" => md5($password),
            "created_at" => date("Y-m-d H:i:s"),
            "p_char" => $password,
        ];

        $isSuccess = false;

        if (!empty($id)) {
            // update
            $isSuccess = $this->db->update("user", $data, ["id" => $id]);
        } else {
            // insert
            $isSuccess = $this->db->insert("user", $data);
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



    function getUserType()
    {
        $requestData = $_REQUEST;
        $person_id  = $this->session->feedback_login_id;
        $searchValue = isset($requestData['search']['value']) ? $requestData['search']['value'] : '';

        // Calculate pagination parameters using the separate function
        list($limit, $offset) = $this->calculatePagination($requestData);

        // Query to get total record count
        $thisQuery = $this->db->query("SELECT COUNT(1) AS total
                                    FROM user_type ut
                                    LEFT JOIN category c
                                        ON FIND_IN_SET(c.id, ut.category_id_list)
                                    WHERE 
                                        ut.name LIKE '%$searchValue%' 
                                    GROUP BY ut.id");

        $totalRecords = $thisQuery->row()->total;

        $query = $this->db->query("SELECT 
                                        ut.*,
                                        GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ', ') AS categories
                                    FROM user_type ut
                                    LEFT JOIN category c
                                        ON FIND_IN_SET(c.id, ut.category_id_list)
                                    WHERE 
                                        ut.name LIKE '%$searchValue%'   
                                    GROUP BY ut.id
                                    ORDER BY ut.id DESC, ut.name
                                    LIMIT $limit OFFSET $offset");

        $data = array();
        $cc = $offset + 1;
        foreach ($query->result() as $key => $value) {
            $cats3 = [];
            $cats = explode(',', $value->categories);
            foreach ($cats as $cat) {
                $cats3[] = '<span class="badge bg-primary me-1">' . trim($cat) . '</span>';
            }
            $name = "<a href='javascript:void(0)'
                        class='text-decoration-none'
                        onclick='edit(
                            \"#form_save_dataUserType\",
                            " . json_encode($value) . "
                        )'>
                        <i class='fas fa-pencil-alt'></i>
                    </a> " . $value->name;
            $data[] = array(
                $name,
                implode('', $cats3),
                // $value->category_id_list
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

    function saveUserType()
    {
        $this->db->trans_begin();
        $true = ["success"   => true];
        $false = ["success"   => false];
        $data = [];
        $id = $this->input->post("id");
        $name = strtoupper($this->input->post("name"));
        $category_id_list_arr = $this->input->post("category_id_list[]");
        $category_id_list = implode(',', $category_id_list_arr);


        if ($id == "" || $id == null) {
            $exist = $this->db->query("SELECT * FROM service WHERE name = '$name'")->num_rows();
            if ($exist > 0) {
                $false += ["message"   => "Service already exists!", "exist"   => true];
                $ret = $false;
                echo json_encode($ret);
                return;
            }
        }

        $data = [
            "name" => $name,
            "category_id_list" => $category_id_list,
        ];

        $isSuccess = false;

        if (!empty($id)) {
            // update
            $isSuccess = $this->db->update("user_type", $data, ["id" => $id]);
        } else {
            // insert
            $isSuccess = $this->db->insert("user_type", $data);
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