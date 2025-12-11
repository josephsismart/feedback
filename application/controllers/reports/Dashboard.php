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
        $page_data = $this->system();
        $uri = $this->session->schoolmis_login_uri;
        $page_data += [
            "page_title"        => "Dashboard",
            "current_location"  => "dashboard",
            "content"           =>  [$this->load->view('interface/'.$uri.'/Dashboard', [
                                        // "dashboard_data" => $this->get_covid_dashboard(),
                                        // "getYearMonth" => $this->getDateYearMonth(),
                                        //"useraccount"      => $this->get_useraccount(),
                                        //"pending"      => $this->get_pending(),
                                        //"documents"      => $this->get_documents(),
                                    ], TRUE)]
        ];
        $this->public_create_page($page_data);
    }

    function getMFGradelvl()
    {
        $data = ["series" => [],"categories" => [],"totals" => []];
        $dc = ["male","female","total"];
        $c = 1;
        $sy = $this->getOnLoad()["sy_id"];
        $query = $this->db->query("SELECT t1.grade,SUM(t1.male) male,SUM(t1.female) female FROM (
                                        SELECT t1.grade,
                                                CASE WHEN t1.sex_bool=TRUE THEN count(1) ELSE 0 END AS male,
                                                CASE WHEN t1.sex_bool=FALSE THEN count(1) ELSE 0 END AS female
                                        FROM sy$sy.bs_view_enrollment$sy t1
                                        WHERE t1.status_id=5
                                        GROUP BY t1.grade,t1.sex_bool 
                                    )t1
                                    GROUP BY t1.grade
                                    ORDER BY t1.grade::integer");

        foreach ($query->result() as $key => $value) {
            $dc["male"][]=(int) $value->male;
            $dc["female"][]=(int) $value->female;
        }

        $data["series"][] = [
            "name"=>"Male",
            "color"=>"#007bff",
            "data"=>$dc["male"],
         ];
         $data["series"][] = [
            "name"=>"Female",
            "color"=>"#dc3545",
            "data"=>$dc["female"],
         ];
         
         $data["categories"] = [
            'Grade 7',
            'Grade 8',
            'Grade 9',
            'Grade 10',
        ];

        echo json_encode($data);
    }

    function getMFAgebracket()
    {
        $data = ["series" => [],"categories" => []];
        $dc = ["male","female"];
        $c = 1;
        $sy = $this->getOnLoad()["sy_id"];
        $query = $this->db->query("SELECT t2.sex,
                                    SUM(t2.below_11)below_11,SUM(t2.age_11)age_11,SUM(t2.age_12)age_12,SUM(t2.age_13)age_13,SUM(t2.age_14)age_14,SUM(t2.age_15)age_15,SUM(t2.age_16)age_16,SUM(t2.age_17)age_17,SUM(t2.age_18)age_18,SUM(t2.age_19)age_19,SUM(t2.above_20)above_20
                                    FROM  (SELECT t1.*
                                                ,SUM(CASE WHEN t1.age_gap<11 THEN 1 END) AS below_11 
                                                ,SUM(CASE WHEN t1.age_gap=11 THEN 1 END) AS age_11 
                                                ,SUM(CASE WHEN t1.age_gap=12 THEN 1 END) AS age_12 
                                                ,SUM(CASE WHEN t1.age_gap=13 THEN 1 END) AS age_13 
                                                ,SUM(CASE WHEN t1.age_gap=14 THEN 1 END) AS age_14 
                                                ,SUM(CASE WHEN t1.age_gap=15 THEN 1 END) AS age_15 
                                                ,SUM(CASE WHEN t1.age_gap=16 THEN 1 END) AS age_16 
                                                ,SUM(CASE WHEN t1.age_gap=17 THEN 1 END) AS age_17 
                                                ,SUM(CASE WHEN t1.age_gap=18 THEN 1 END) AS age_18 
                                                ,SUM(CASE WHEN t1.age_gap=19 THEN 1 END) AS age_19 
                                                ,SUM(CASE WHEN t1.age_gap>19 THEN 1 END) AS above_20
                                            FROM (SELECT t1.sex,
                                                        date_part('YEAR',  AGE(t1.birthdate)) AS age_gap
                                                    FROM sy$sy.bs_view_enrollment$sy t1
                                                    WHERE t1.schl_yr_id=1 AND t1.status_id=5) t1
                                                    GROUP BY t1.sex, t1.age_gap) t2
                                                    GROUP BY t2.sex");

        foreach ($query->result() as $key => $value) {
            if($value->sex=="MALE")$dc["male"][] = (int) $value->below_11;
            if($value->sex=="FEMALE")$dc["female"][] = (int) $value->below_11;
            if($value->sex=="MALE")$dc["male"][] = (int) $value->age_11;
            if($value->sex=="FEMALE")$dc["female"][] = (int) $value->age_11;
            if($value->sex=="MALE")$dc["male"][] = (int) $value->age_12;
            if($value->sex=="FEMALE")$dc["female"][] = (int) $value->age_12;
            if($value->sex=="MALE")$dc["male"][] = (int) $value->age_13;
            if($value->sex=="FEMALE")$dc["female"][] = (int) $value->age_13;
            if($value->sex=="MALE")$dc["male"][] = (int) $value->age_14;
            if($value->sex=="FEMALE")$dc["female"][] = (int) $value->age_14;
            if($value->sex=="MALE")$dc["male"][] = (int) $value->age_15;
            if($value->sex=="FEMALE")$dc["female"][] = (int) $value->age_15;
            if($value->sex=="MALE")$dc["male"][] = (int) $value->age_16;
            if($value->sex=="FEMALE")$dc["female"][] = (int) $value->age_16;
            if($value->sex=="MALE")$dc["male"][] = (int) $value->age_17;
            if($value->sex=="FEMALE")$dc["female"][] = (int) $value->age_17;
            if($value->sex=="MALE")$dc["male"][] = (int) $value->age_18;
            if($value->sex=="FEMALE")$dc["female"][] = (int) $value->age_18;
            if($value->sex=="MALE")$dc["male"][] = (int) $value->age_19;
            if($value->sex=="FEMALE")$dc["female"][] = (int) $value->age_19;
            if($value->sex=="MALE")$dc["male"][] = (int) $value->above_20;
            if($value->sex=="FEMALE")$dc["female"][] = (int) $value->above_20;
        }

        $data["series"][] = [
            "name"=>"Male",
            "color"=>"#007bff",
            "data"=>$dc["male"],
         ];
         $data["series"][] = [
            "name"=>"Female",
            "color"=>"#dc3545",
            "data"=>$dc["female"],
         ];
        $data["categories"] = [
            'BELOW 11',
            '11',
            '12',
            '13',
            '14',
            '15',
            '16',
            '17',
            '18',
            '19',
            'ABOVE 19',
        ];

        echo json_encode($data);
    }
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */