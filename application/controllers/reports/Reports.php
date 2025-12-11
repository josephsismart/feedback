<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->redirect_session();
        $this->load->model('mainModel');
        $this->load->helper('date');
        date_default_timezone_set("Asia/Manila");
    }

    public function index()
    {
        $page_data = $this->system();
        $uri = $this->session->schoolmis_login_uri;
        $page_data += [
            "page_title"        => "Reports",
            "current_location"  => "reports",
            "content"           =>  [$this->load->view('interface/' . $uri . '/Reports', [
                "getOnLoad" => $this->getOnLoad(),
                "getSHdboard" => $this->getSHdboard(),
            ], TRUE)]
        ];
        $this->public_create_page($page_data);
    }

    function getMFGradelvl()
    {
        $data = ["data" => []];
        $sy = $this->getOnLoad()["sy_id"];
        $tmpG = "";

        $tb11_m = 0;
        $t11_m = 0;
        $t12_m = 0;
        $t13_m = 0;
        $t14_m = 0;
        $t15_m = 0;
        $t16_m = 0;
        $t17_m = 0;
        $t18_m = 0;
        $t19_m = 0;
        $t20_m = 0;
        $t4p_m = 0;
        $tb11_f = 0;
        $t11_f = 0;
        $t12_f = 0;
        $t13_f = 0;
        $t14_f = 0;
        $t15_f = 0;
        $t16_f = 0;
        $t17_f = 0;
        $t18_f = 0;
        $t19_f = 0;
        $t20_f = 0;
        $t4p_f = 0;

        $stb11_m = 0;
        $st11_m = 0;
        $st12_m = 0;
        $st13_m = 0;
        $st14_m = 0;
        $st15_m = 0;
        $st16_m = 0;
        $st17_m = 0;
        $st18_m = 0;
        $st19_m = 0;
        $st20_m = 0;
        $st4p_m = 0;
        $stb11_f = 0;
        $st11_f = 0;
        $st12_f = 0;
        $st13_f = 0;
        $st14_f = 0;
        $st15_f = 0;
        $st16_f = 0;
        $st17_f = 0;
        $st18_f = 0;
        $st19_f = 0;
        $st20_f = 0;
        $st4p_f = 0;

        $t_m = 0;
        $t_f = 0;

        $total_m = 0;
        $total_f = 0;
        $stotal_m = 0;
        $stotal_f = 0;

        $empty = [
            " ", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
        ];

        $query = $this->db->query("SELECT t1.*,t2.four_ps_m,t2.four_ps_f FROM (
                                    SELECT t1.rm_sctn_sbjct_assgnmnt_id AS rssaid,t1.sctn_nm,t1.grade,
                                    COALESCE(SUM(t2.below_11_m),0) AS below_11_m,
                                    COALESCE(SUM(t2.below_11_f),0) AS below_11_f,
                                    COALESCE(SUM(t2.age_11_m),0) AS age_11_m,
                                    COALESCE(SUM(t2.age_11_f),0) AS age_11_f,
                                    COALESCE(SUM(t2.age_12_m),0) AS age_12_m,
                                    COALESCE(SUM(t2.age_12_f),0) AS age_12_f,
                                    COALESCE(SUM(t2.age_13_m),0) AS age_13_m,
                                    COALESCE(SUM(t2.age_13_f),0) AS age_13_f,
                                    COALESCE(SUM(t2.age_14_m),0) AS age_14_m,
                                    COALESCE(SUM(t2.age_14_f),0) AS age_14_f,
                                    COALESCE(SUM(t2.age_15_m),0) AS age_15_m,
                                    COALESCE(SUM(t2.age_15_f),0) AS age_15_f,
                                    COALESCE(SUM(t2.age_16_m),0) AS age_16_m,
                                    COALESCE(SUM(t2.age_16_f),0) AS age_16_f,
                                    COALESCE(SUM(t2.age_17_m),0) AS age_17_m,
                                    COALESCE(SUM(t2.age_17_f),0) AS age_17_f,
                                    COALESCE(SUM(t2.age_18_m),0) AS age_18_m,
                                    COALESCE(SUM(t2.age_18_f),0) AS age_18_f,
                                    COALESCE(SUM(t2.age_19_m),0) AS age_19_m,
                                    COALESCE(SUM(t2.age_19_f),0) AS age_19_f,
                                    COALESCE(SUM(t2.above_20_m),0) AS above_20_m,
                                    COALESCE(SUM(t2.above_20_f),0) AS above_20_f
                                    FROM building_sectioning.view_room_section t1
                                    LEFT JOIN (SELECT t1.*
                                    ,SUM(CASE WHEN t1.age_gap<11 AND t1.sex_bool=TRUE THEN 1 END) AS below_11_m 
													,SUM(CASE WHEN t1.age_gap<11 AND t1.sex_bool=FALSE THEN 1 END) AS below_11_f 
													,SUM(CASE WHEN t1.age_gap=11 AND t1.sex_bool=TRUE THEN 1 END) AS age_11_m 
													,SUM(CASE WHEN t1.age_gap=11 AND t1.sex_bool=FALSE THEN 1 END) AS age_11_f 
													,SUM(CASE WHEN t1.age_gap=12 AND t1.sex_bool=TRUE THEN 1 END) AS age_12_m 
													,SUM(CASE WHEN t1.age_gap=12 AND t1.sex_bool=FALSE THEN 1 END) AS age_12_f 
													,SUM(CASE WHEN t1.age_gap=13 AND t1.sex_bool=TRUE THEN 1 END) AS age_13_m 
													,SUM(CASE WHEN t1.age_gap=13 AND t1.sex_bool=FALSE THEN 1 END) AS age_13_f 
													,SUM(CASE WHEN t1.age_gap=14 AND t1.sex_bool=TRUE THEN 1 END) AS age_14_m 
													,SUM(CASE WHEN t1.age_gap=14 AND t1.sex_bool=FALSE THEN 1 END) AS age_14_f 
													,SUM(CASE WHEN t1.age_gap=15 AND t1.sex_bool=TRUE THEN 1 END) AS age_15_m 
													,SUM(CASE WHEN t1.age_gap=15 AND t1.sex_bool=FALSE THEN 1 END) AS age_15_f 
													,SUM(CASE WHEN t1.age_gap=16 AND t1.sex_bool=TRUE THEN 1 END) AS age_16_m 
													,SUM(CASE WHEN t1.age_gap=16 AND t1.sex_bool=FALSE THEN 1 END) AS age_16_f 
													,SUM(CASE WHEN t1.age_gap=17 AND t1.sex_bool=TRUE THEN 1 END) AS age_17_m 
													,SUM(CASE WHEN t1.age_gap=17 AND t1.sex_bool=FALSE THEN 1 END) AS age_17_f 
													,SUM(CASE WHEN t1.age_gap=18 AND t1.sex_bool=TRUE THEN 1 END) AS age_18_m 
													,SUM(CASE WHEN t1.age_gap=18 AND t1.sex_bool=FALSE THEN 1 END) AS age_18_f 
													,SUM(CASE WHEN t1.age_gap=19 AND t1.sex_bool=TRUE THEN 1 END) AS age_19_m 
													,SUM(CASE WHEN t1.age_gap=19 AND t1.sex_bool=FALSE THEN 1 END) AS age_19_f 
													,SUM(CASE WHEN t1.age_gap>19 AND t1.sex_bool=TRUE THEN 1 END) AS above_20_m
													,SUM(CASE WHEN t1.age_gap>19 AND t1.sex_bool=FALSE THEN 1 END) AS above_20_f
                                                FROM (SELECT t1.rm_sctn_sbjct_assgnmnt_id AS rssaid,
                                                            date_part('YEAR',  AGE(t1.birthdate)) AS age_gap,
                                                            t1.sex_bool
                                                        FROM sy$sy.bs_view_enrollment$sy t1
                                                        WHERE t1.schl_yr_id=1 AND t1.status_id=5) t1
                                                        GROUP BY t1.rssaid,t1.age_gap,t1.sex_bool) t2 ON t1.rm_sctn_sbjct_assgnmnt_id = t2.rssaid
                                    WHERE t1.schl_yr_id = 1
                                    GROUP BY t1.rm_sctn_sbjct_assgnmnt_id,t1.sctn_nm,t1.grade,t1.order_by
                                    ORDER BY t1.order_by,t1.sctn_nm) t1
                                    LEFT JOIN (SELECT t1.rssaid, SUM(t1.four_ps_m) AS four_ps_m,SUM(t1.four_ps_f) AS four_ps_f FROM(
                                                    SELECT t1.rm_sctn_sbjct_assgnmnt_id AS rssaid,
                                                        SUM(CASE WHEN t1.four_ps = TRUE AND t1.sex_bool=TRUE THEN 1 ELSE 0 END) four_ps_m,
                                                        SUM(CASE WHEN t1.four_ps = TRUE AND t1.sex_bool=FALSE THEN 1 ELSE 0 END) four_ps_f
                                                    FROM sy$sy.bs_view_enrollment$sy t1
                                                    WHERE t1.schl_yr_id=1 AND t1.status_id=5
                                                    GROUP BY t1.rm_sctn_sbjct_assgnmnt_id,t1.sex)t1    
                                                GROUP BY t1.rssaid) t2 ON t1.rssaid = t2.rssaid");

        foreach ($query->result() as $key => $value) {
            $total = [
                "<center><b>TOTAL</b></center>",
                "<b>" . number_format($tb11_m) . "</b>",
                "<b>" . number_format($tb11_f) . "</b>",
                "<b>" . number_format($t11_m) . "</b>",
                "<b>" . number_format($t11_f) . "</b>",
                "<b>" . number_format($t12_m) . "</b>",
                "<b>" . number_format($t12_f) . "</b>",
                "<b>" . number_format($t13_m) . "</b>",
                "<b>" . number_format($t13_f) . "</b>",
                "<b>" . number_format($t14_m) . "</b>",
                "<b>" . number_format($t14_f) . "</b>",
                "<b>" . number_format($t15_m) . "</b>",
                "<b>" . number_format($t15_f) . "</b>",
                "<b>" . number_format($t16_m) . "</b>",
                "<b>" . number_format($t16_f) . "</b>",
                "<b>" . number_format($t17_m) . "</b>",
                "<b>" . number_format($t17_f) . "</b>",
                "<b>" . number_format($t18_m) . "</b>",
                "<b>" . number_format($t18_f) . "</b>",
                "<b>" . number_format($t19_m) . "</b>",
                "<b>" . number_format($t19_f) . "</b>",
                "<b>" . number_format($t20_m) . "</b>",
                "<b>" . number_format($t20_f) . "</b>",
                "<b>" . number_format($total_m) . "</b>",
                "<b>" . number_format($total_f) . "</b>",
                "<b>" . number_format($t4p_m) . "</b>",
                "<b>" . number_format($t4p_f) . "</b>",
            ];

            $t_m = $value->below_11_m + $value->age_11_m + $value->age_12_m + $value->age_13_m + $value->age_14_m + $value->age_15_m + $value->age_16_m + $value->age_17_m + $value->age_18_m + $value->age_19_m + $value->above_20_m;
            $t_f = $value->below_11_f + $value->age_11_f + $value->age_12_f + $value->age_13_f + $value->age_14_f + $value->age_15_f + $value->age_16_f + $value->age_17_f + $value->age_18_f + $value->age_19_f + $value->above_20_f;
            if (($tmpG != "") && ($tmpG != $value->grade)) {

                $data["data"][] = $total;

                $tb11_m = 0;
                $tb11_f = 0;
                $t11_m = 0;
                $t11_f = 0;
                $t12_m = 0;
                $t12_f = 0;
                $t13_m = 0;
                $t13_f = 0;
                $t14_m = 0;
                $t14_f = 0;
                $t15_m = 0;
                $t15_f = 0;
                $t16_m = 0;
                $t16_f = 0;
                $t17_m = 0;
                $t17_f = 0;
                $t18_m = 0;
                $t18_f = 0;
                $t19_m = 0;
                $t19_f = 0;
                $t20_m = 0;
                $t20_f = 0;
                $total_m = 0;
                $total_f = 0;
                $t4p_m = 0;
                $t4p_f = 0;
                $data["data"][] = $empty;
            }

            if ($tmpG != $value->grade) {
                $data["data"][] = [
                    "<center><b>GRADE - " . $value->grade . "</b></center>", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                ];
            }
            $data["data"][] = [
                '<p class="mb-n2 p-0 mt-n2" align="left">' . $value->sctn_nm . '</p>',
                $value->below_11_m,
                $value->below_11_f,
                $value->age_11_m,
                $value->age_11_f,
                $value->age_12_m,
                $value->age_12_f,
                $value->age_13_m,
                $value->age_13_f,
                $value->age_14_m,
                $value->age_14_f,
                $value->age_15_m,
                $value->age_15_f,
                $value->age_16_m,
                $value->age_16_f,
                $value->age_17_m,
                $value->age_17_f,
                $value->age_18_m,
                $value->age_18_f,
                $value->age_19_m,
                $value->age_19_f,
                $value->above_20_m,
                $value->above_20_f,
                $t_m,
                $t_f,
                $value->four_ps_m,
                $value->four_ps_f,
            ];
            $tb11_m += (int) $value->below_11_m;
            $tb11_f += (int) $value->below_11_f;
            $t11_m += (int) $value->age_11_m;
            $t11_f += (int) $value->age_11_f;
            $t12_m += (int) $value->age_12_m;
            $t12_f += (int) $value->age_12_f;
            $t13_m += (int) $value->age_13_m;
            $t13_f += (int) $value->age_13_f;
            $t14_m += (int) $value->age_14_m;
            $t14_f += (int) $value->age_14_f;
            $t15_m += (int) $value->age_15_m;
            $t15_f += (int) $value->age_15_f;
            $t16_m += (int) $value->age_16_m;
            $t16_f += (int) $value->age_16_f;
            $t17_m += (int) $value->age_17_m;
            $t17_f += (int) $value->age_17_f;
            $t18_m += (int) $value->age_18_m;
            $t18_f += (int) $value->age_18_f;
            $t19_m += (int) $value->age_19_m;
            $t19_f += (int) $value->age_19_f;
            $t20_m += (int) $value->above_20_m;
            $t20_f += (int) $value->above_20_f;
            $total_m += (int) $t_m;
            $total_f += (int) $t_f;
            $t4p_m += (int) $value->four_ps_m;
            $t4p_f += (int) $value->four_ps_f;


            $stb11_m += (int) $value->below_11_m;
            $stb11_f += (int) $value->below_11_f;
            $st11_m += (int) $value->age_11_m;
            $st11_f += (int) $value->age_11_f;
            $st12_m += (int) $value->age_12_m;
            $st12_f += (int) $value->age_12_f;
            $st13_m += (int) $value->age_13_m;
            $st13_f += (int) $value->age_13_f;
            $st14_m += (int) $value->age_14_m;
            $st14_f += (int) $value->age_14_f;
            $st15_m += (int) $value->age_15_m;
            $st15_f += (int) $value->age_15_f;
            $st16_m += (int) $value->age_16_m;
            $st16_f += (int) $value->age_16_f;
            $st17_m += (int) $value->age_17_m;
            $st17_f += (int) $value->age_17_f;
            $st18_m += (int) $value->age_18_m;
            $st18_f += (int) $value->age_18_f;
            $st19_m += (int) $value->age_19_m;
            $st19_f += (int) $value->age_19_f;
            $st20_m += (int) $value->above_20_m;
            $st20_f += (int) $value->above_20_f;
            $stotal_m += (int) $t_m;
            $stotal_f += (int) $t_f;
            $st4p_m += (int) $value->four_ps_m;
            $st4p_f += (int) $value->four_ps_f;

            $tmpG = $value->grade;
        }

        $data["data"][] = $total;
        $data["data"][] = $empty;
        $data["data"][] = [
            "<center><b>GRAND TOTAL</b></center>",
            "<b>" . number_format($stb11_m) . "</b>",
            "<b>" . number_format($stb11_f) . "</b>",
            "<b>" . number_format($st11_m) . "</b>",
            "<b>" . number_format($st11_f) . "</b>",
            "<b>" . number_format($st12_m) . "</b>",
            "<b>" . number_format($st12_f) . "</b>",
            "<b>" . number_format($st13_m) . "</b>",
            "<b>" . number_format($st13_f) . "</b>",
            "<b>" . number_format($st14_m) . "</b>",
            "<b>" . number_format($st14_f) . "</b>",
            "<b>" . number_format($st15_m) . "</b>",
            "<b>" . number_format($st15_f) . "</b>",
            "<b>" . number_format($st16_m) . "</b>",
            "<b>" . number_format($st16_f) . "</b>",
            "<b>" . number_format($st17_m) . "</b>",
            "<b>" . number_format($st17_f) . "</b>",
            "<b>" . number_format($st18_m) . "</b>",
            "<b>" . number_format($st18_f) . "</b>",
            "<b>" . number_format($st19_m) . "</b>",
            "<b>" . number_format($st19_f) . "</b>",
            "<b>" . number_format($st20_m) . "</b>",
            "<b>" . number_format($st20_f) . "</b>",
            "<b>" . number_format($stotal_m) . "</b>",
            "<b>" . number_format($stotal_f) . "</b>",
            "<b>" . number_format($st4p_m) . "</b>",
            "<b>" . number_format($st4p_f) . "</b>",
        ];

        echo json_encode($data);
    }

    function getMPS()
    {
        $tab = null;
        $content = null;
        $sy = $this->input->get("sy");
        $qrtr = $this->input->get("qrtr");
        $ssy = 'sy' . $sy;




        // $query1 = $this->db->query("SELECT * FROM global.tbl_party t1 WHERE t1.group_by = 11 AND (t1.id=70 OR t1.id=71 
        //                             OR t1.id=69 OR t1.id=72 OR t1.id=87 OR t1.id=68 
        //                             OR t1.id=88 OR t1.id=90 OR t1.id=91 OR t1.id=92 OR t1.id=93)
        //                             ORDER BY array_position(ARRAY[70,71,69,72,87,68,88,90,91,92,93]::bigint[],t1.id)");
        $query1 = $this->db->query("SELECT * FROM global.tbl_party t1 WHERE t1.group_by = 11
                                    ORDER BY array_position(ARRAY[70,71,69,72,87,68,88,90,91,92,93]::bigint[],t1.id)");
        foreach ($query1->result() as $key => $value) {
            $sbjctid = $value->id;
            $sbjct = $value->description;
            $sbjct_abbr = $value->abbr;
            $active = "";
            $tmpGlvl = "";
            $cc = 0;

            $st_m = 0;
            $st_f = 0;
            $st_mps = 0;
            $st_mb5 = 0;
            $st_fb5 = 0;
            $st_m5 = 0;
            $st_f5 = 0;
            $st_m7 = 0;
            $st_f7 = 0;
            $st_mt = 0;
            $st_ft = 0;

            $t_m = 0;
            $t_f = 0;
            $t_t = 0;
            $t_mps = 0;
            $tt_mps = 0;
            $t_mb5 = 0;
            $t_fb5 = 0;
            $t_m5 = 0;
            $t_f5 = 0;
            $t_m7 = 0;
            $t_f7 = 0;
            $t_mt = 0;
            $t_ft = 0;


            $a = null;
            $b = null;
            $c = null;
            if ($key === array_key_first($query1->result())) {
                $active = "active";
                // $tab .= '<li class="nav-item">
                //         <a onclick="rssaid=0;" class="nav-link active" title="CONSOLIDATED" id="custom-tabs-0-tab" data-toggle="pill" href="#custom-tabs-0" role="tab" aria-controls="custom-tabs-0" aria-selected="false">CONSOLIDATED</a>
                //     </li>';
            }
            $tab .= '<li class="nav-item">
                        <a onclick="rssaidMPS=' . $sbjctid . ';" class="nav-link ' . $active . '" title="' . $sbjct . '" id="customMPS-tabs-' . $sbjctid . '-tab" data-toggle="pill" href="#customMPS-tabs-' . $sbjctid . '" role="tab" aria-controls="customMPS-tabs-' . $sbjctid . '" aria-selected="false">' . $sbjct_abbr . '</a>
                    </li>
                    <script>
                        // if(rssaid_tmp == 0){
                        //     rssaid = ' . $sbjctid . ';
                        //     console.log(rssaid);
                        //     rssaid_tmp = 1;
                        // }
                    </script>';

            $query2 = $this->db->query("SELECT
                                        t4.description,t1.rm_sctn_sbjct_assgnmnt_id AS rssa_id,t1.room_section_id AS rsid,
                                        t1.full_name,t1.grade,t1.sctn_nm,t2.m,t2.f,(t2.m + t2.f) AS t,t3.*
                                        FROM building_sectioning.view_subject_grdlvl_personnel_assgnmnt t1
                                        LEFT JOIN(
                                            SELECT
                                                t1.room_section_id AS rsid,
                                                SUM(CASE WHEN(t1.sex_bool = TRUE)THEN 1 END) AS m,
                                                SUM(CASE WHEN(t1.sex_bool = FALSE)THEN 1 END) AS f
                                            FROM sy$sy.bs_view_enrollment t1
                                            WHERE t1.status_id = 5
                                            GROUP BY t1.room_section_id )t2 ON t1.room_section_id = t2.rsid
                                        LEFT JOIN (
                                            SELECT * FROM mps('$ssy','$qrtr') AS 
                                                    (rssaid bigint,m_b50 bigint,f_b50 bigint,
                                                    t_b50 bigint,m_51_75 bigint,f_51_75 bigint,
                                                    t_51_75 bigint,m_76_100 bigint,f_76_100 bigint,
                                                    t_76_100 bigint,m_t bigint,f_t bigint,t bigint,qrtr bigint)
                                            ) t3 ON t1.rm_sctn_sbjct_assgnmnt_id = t3.rssaid
                                        LEFT JOIN global.tbl_party t4 ON t1.subject_id = t4.id
                                        WHERE t1.schl_yr_id = 1 AND t1.subject_id = $sbjctid
                                        ORDER BY t1.grade::Int,t1.sctn_nm");

            $a = '<div class="tab-pane fade show ' . $active . '" id="customMPS-tabs-' . $sbjctid . '" role="tabpanel" aria-labelledby="customMPS-tabs-' . $sbjctid . '-tab">
            <h5 class="font-weight-bold text-primary">' . strtoupper($sbjct) . '</h5>
            <table class="table table-sm table-hover table-striped" border="1" id="tblAllGradesList' . $sbjctid . '" width="100%">
                        <thead style="text-align:center">
                            <tr valign="center">
                                <th width="1" rowspan="2">#</th>
                                <th width="1" rowspan="2">NAME OF TEACHER</th>
                                <th width="1" rowspan="2">SECTION</th>
                                <th width="1" colspan="3">TOTAL ENROLLMENT</th>
                                <th width="1" rowspan="2">MPS</th>
                                <th width="1" colspan="3">50 AND BELOW</th>
                                <th width="1" colspan="3">51-75</th>
                                <th width="1" colspan="3">76-100</th>
                                <th width="1" colspan="3">TOTAL</th>
                            </tr>
                            <tr>
                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>
                            </tr>
                        </thead>
                        <tbody>';

            foreach ($query2->result() as $key => $value) {
                $grade = $value->grade;
                $t_mf = $value->m + $value->f;
                $avg_ = $t_mf > 0 ? ($value->qrtr / $t_mf) : $value->qrtr;
                $avg_mps = number_format($avg_, 2);
                $cc++;

                if (($tmpGlvl != "") && ($tmpGlvl != $grade)) {
                    $t_mps += number_format((($st_mps) / ($cc - 1)), 2);
                    $tt_mps++;
                    $c .= '<tr style="font-weight:bold">
                                <td></td>
                                <td style="white-space:nowrap;"> </td>
                                <td style="white-space:nowrap;"> </td>
                                <td align="right">' . $st_m . '</td>
                                <td align="right">' . $st_f . '</td>
                                <td align="right">' . ($st_m + $st_f) . '</td>
                                <td align="right">' . number_format((($st_mps) / ($cc - 1)), 2) . '</td>
                                <td align="right">' . $st_mb5 . '</td>
                                <td align="right">' . $st_fb5 . '</td>
                                <td align="right">' . ($st_mb5 + $st_fb5) . '</td>
                                <td align="right">' . $st_m5 . '</td>
                                <td align="right">' . $st_f5 . '</td>
                                <td align="right">' . ($st_m5 + $st_f5) . '</td>
                                <td align="right">' . $st_m7 . '</td>
                                <td align="right">' . $st_f7 . '</td>
                                <td align="right">' . ($st_m7 + $st_f7) . '</td>
                                <td align="right">' . $st_mt . '</td>
                                <td align="right">' . $st_ft . '</td>
                                <td align="right">' . ($st_mt + $st_ft) . '</td>
                            </tr>
                            <tr style="font-size:5px;padding:0px">
                                <td colspan="19"> </td>
                            </tr>';
                    $st_m = 0;
                    $st_f = 0;
                    $st_mps = 0;
                    $st_mb5 = 0;
                    $st_fb5 = 0;
                    $st_m5 = 0;
                    $st_f5 = 0;
                    $st_m7 = 0;
                    $st_f7 = 0;
                    $st_mt = 0;
                    $st_ft = 0;

                    $cc = 1;
                }
                if ($tmpGlvl == "") {
                    $c .= '<tr style="font-weight:bold">
                                <td align="center" colspan="3">TOTAL</td>
                                <td align="right" class="mps_m"></td>
                                <td align="right" class="mps_f"></td>
                                <td align="right" class="mps_t"></td>
                                <td align="right" class="mps_avg"></td>
                                <td align="right" class="mps_mb5"></td>
                                <td align="right" class="mps_fb5"></td>
                                <td align="right" class="mps_tb5"></td>
                                <td align="right" class="mps_m5"></td>
                                <td align="right" class="mps_f5"></td>
                                <td align="right" class="mps_t5"></td>
                                <td align="right" class="mps_m7"></td>
                                <td align="right" class="mps_b7"></td>
                                <td align="right" class="mps_t7"></td>
                                <td align="right" class="mps_mt"></td>
                                <td align="right" class="mps_ft"></td>
                                <td align="right" class="mps_tt"></td>
                            </tr>';
                }
                $c .= '<tr>
                                <td>' . $cc . '</td>
                                <td style="white-space:nowrap;">' . $value->full_name . '</td>
                                <td style="white-space:nowrap;">' . $value->sctn_nm . '</td>
                                <td align="right">' . $value->m . '</td>
                                <td align="right">' . $value->f . '</td>
                                <td align="right">' . $t_mf . '</td>
                                <td align="right">' . $avg_mps . '</td>
                                <td align="right">' . $value->m_b50 . '</td>
                                <td align="right">' . $value->f_b50 . '</td>
                                <td align="right">' . $value->t_b50 . '</td>
                                <td align="right">' . $value->m_51_75 . '</td>
                                <td align="right">' . $value->f_51_75 . '</td>
                                <td align="right">' . $value->t_51_75 . '</td>
                                <td align="right">' . $value->m_76_100 . '</td>
                                <td align="right">' . $value->f_76_100 . '</td>
                                <td align="right">' . $value->t_76_100 . '</td>
                                <td align="right">' . $value->m_t . '</td>
                                <td align="right">' . $value->f_t . '</td>
                                <td align="right">' . $value->t . '</td>
                            </tr>';
                $st_m += (int) $value->m;
                $st_f += (int) $value->f;
                $st_mps += $avg_mps;
                $st_mb5 += (int) $value->m_b50;
                $st_fb5 += (int) $value->f_b50;
                $st_m5 += (int) $value->m_51_75;
                $st_f5 += (int) $value->f_51_75;
                $st_m7 += (int) $value->m_76_100;
                $st_f7 += (int) $value->f_76_100;
                $st_mt += (int) $value->m_t;
                $st_ft += (int) $value->f_t;

                $t_m += (int) $value->m;
                $t_f += (int) $value->f;
                $t_t += ((int) $value->m + (int) $value->f);
                $t_mb5  += (int) $value->m_b50;
                $t_fb5  += (int) $value->f_b50;
                $t_m5 += (int) $value->m_51_75;
                $t_f5 += (int) $value->f_51_75;
                $t_m7 += (int) $value->m_76_100;
                $t_f7 += (int) $value->f_76_100;
                $t_mt += (int) $value->m_t;
                $t_ft += (int) $value->f_t;


                $tmpGlvl = $grade;

                if ($key === array_key_last($query2->result())) {
                    $t_mps += number_format(($st_mps / $cc), 2);
                    $tt_mps++;
                    $t_mps = number_format(($t_mps / $tt_mps), 2);
                    $c .= '<tr style="font-weight:bold">
                                <td></td>
                                <td style="white-space:nowrap;"> </td>
                                <td style="white-space:nowrap;"> </td>
                                <td align="right">' . $st_m . '</td>
                                <td align="right">' . $st_f . '</td>
                                <td align="right">' . ($st_m + $st_f) . '</td>
                                <td align="right">' . number_format(($st_mps / $cc), 2) . '</td>
                                <td align="right">' . $st_mb5 . '</td>
                                <td align="right">' . $st_fb5 . '</td>
                                <td align="right">' . ($st_mb5 + $st_fb5) . '</td>
                                <td align="right">' . $st_m5 . '</td>
                                <td align="right">' . $st_f5 . '</td>
                                <td align="right">' . ($st_m5 + $st_f5) . '</td>
                                <td align="right">' . $st_m7 . '</td>
                                <td align="right">' . $st_f7 . '</td>
                                <td align="right">' . ($st_m7 + $st_f7) . '</td>
                                <td align="right">' . $st_mt . '</td>
                                <td align="right">' . $st_ft . '</td>
                                <td align="right">' . ($st_mt + $st_ft) . '</td>
                            </tr>
                            <script>
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_m").text("' . $t_m . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_f").text("' . $t_f . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_t").text("' . $t_t . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_avg").text("' . $t_mps . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_mb5").text("' . $t_mb5 . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_fb5").text("' . $t_fb5 . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_tb5").text("' . ($t_fb5 + $t_mb5) . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_m5").text("' . $t_m5 . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_f5").text("' . $t_f5 . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_t5").text("' . ($t_m5 + $t_f5) . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_m7").text("' . $t_m7 . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_b7").text("' . $t_f7 . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_t7").text("' . ($t_m7 + $t_f7) . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_mt").text("' . $t_mt . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_ft").text("' . $t_ft . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_tt").text("' . ($t_mt + $t_ft) . '");
                            </script>';
                }
            }


            $b =      '</tbody>
                    </table>
                  </div>';
            $content .= $a . $c . $b;
        }
        $data = '<div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="customMPS-tabs-four-tab" role="tablist">' . $tab . '</ul>
                    </div>
                    <div class="card-body">
                            <div class="tab-content" id="customMPS-tabs-one-tabContent">
                            ' . $content . '
                    </div>
                </div>
                <script>
                    rssaidMPS_tmp = 0;
                </script>';
        echo json_encode($data);
    }

    function getGPA()
    {
        //form_report_dataSMEA

        // parse_str($this->input->post("a"), $filter);
        // $sy = $filter['sy'];
        // $qrtr = $filter['qrtr'];
        // $ssy = 'sy' . $sy;
        $tab = null;
        $content = null;
        $sy = $this->input->get("sy");
        $qrtr = $this->input->get("qrtr");
        $ssy = 'sy' . $sy;

        // echo $sy;
        // echo $qrtr;

        // echo "</br>";
        // echo $sy;
        // echo "</br>";
        // echo $qrtr;
        // echo "</br>";
        // echo "yyyyy";

        //         SELECT t4.description, t1.rm_sctn_sbjct_assgnmnt_id AS rssa_id, t1.room_section_id AS rsid, t1.full_name,t1.grade,t1.sctn_nm,
        // 	   t2.m,t2.f,(t2.m + t2.f) AS t,t3.*  
        // FROM building_sectioning.view_subject_grdlvl_personnel_assgnmnt t1
        // LEFT JOIN(SELECT t1.room_section_id AS rsid,
        // 					SUM(CASE WHEN(t1.sex_bool=TRUE)THEN 1 END) AS m,
        // 					SUM(CASE WHEN(t1.sex_bool=FALSE)THEN 1 END) AS f 
        // 			FROM sy$sy.bs_view_enrollment t1
        // 			WHERE t1.status_id=5
        // 			GROUP BY t1.room_section_id)t2 ON t1.room_section_id  = t2.rsid
        // LEFT JOIN sy$sy.bs_view_grades_ps_avg t3 ON t1.rm_sctn_sbjct_assgnmnt_id = t3.rssaid
        // LEFT JOIN "global".tbl_party t4 ON t1.subject_id = t4.id
        // WHERE t1.schl_yr_id =1 AND t1.subject_id = 72
        // ORDER BY t1.grade::Int,t1.sctn_nm



        // $query1 = $this->db->query("SELECT * FROM global.tbl_party t1 WHERE t1.group_by = 11 AND (t1.id=70 OR t1.id=71 
        //                             OR t1.id=69 OR t1.id=72 OR t1.id=87 OR t1.id=68 
        //                             OR t1.id=88 OR t1.id=90 OR t1.id=91 OR t1.id=92 OR t1.id=93)
        //                             ORDER BY array_position(ARRAY[70,71,69,72,87,68,88,90,91,92,93]::bigint[],t1.id)");

        $query1 = $this->db->query("SELECT * FROM global.tbl_party t1 WHERE t1.group_by = 11
                                    ORDER BY array_position(ARRAY[70,71,69,72,87,68,88,90,91,92,93]::bigint[],t1.id)");
        foreach ($query1->result() as $key => $value) {
            $sbjctid = $value->id;
            $sbjct = $value->description;
            $sbjct_abbr = $value->abbr;
            $active = "";
            $tmpGlvl = "";
            $cc = 0;

            $st_m = 0;
            $st_f = 0;
            $st_mps = 0;
            $st_mb7 = 0;
            $st_fb7 = 0;
            $st_m7 = 0;
            $st_f7 = 0;
            $st_m8 = 0;
            $st_f8 = 0;
            $st_m85 = 0;
            $st_f85 = 0;
            $st_m9 = 0;
            $st_f9 = 0;
            $st_m1 = 0;
            $st_f1 = 0;
            $st_mt = 0;
            $st_ft = 0;

            $t_m = 0;
            $t_f = 0;
            $t_t = 0;
            $t_mps = 0;
            $tt_mps = 0;
            $t_mb7 = 0;
            $t_fb7 = 0;
            $t_m7 = 0;
            $t_f7 = 0;
            $t_m8 = 0;
            $t_f8 = 0;
            $t_m85 = 0;
            $t_f85 = 0;
            $t_m9 = 0;
            $t_f9 = 0;
            $t_m1 = 0;
            $t_f1 = 0;
            $t_mt = 0;
            $t_ft = 0;

            $a = null;
            $b = null;
            $c = null;
            if ($key === array_key_first($query1->result())) {
                $active = "active";
                // $tab .= '<li class="nav-item">
                //         <a onclick="rssaid=0;" class="nav-link active" title="CONSOLIDATED" id="custom-tabs-0-tab" data-toggle="pill" href="#custom-tabs-0" role="tab" aria-controls="custom-tabs-0" aria-selected="false">CONSOLIDATED</a>
                //     </li>';
            }
            $tab .= '<li class="nav-item">
                        <a onclick="rssaidGPA=' . $sbjctid . ';" class="nav-link ' . $active . '" title="' . $sbjct . '" id="customGPA-tabs-' . $sbjctid . '-tab" data-toggle="pill" href="#customGPA-tabs-' . $sbjctid . '" role="tab" aria-controls="customGPA-tabs-' . $sbjctid . '" aria-selected="false">' . $sbjct_abbr . '</a>
                    </li>
                    <script>
                        // if(rssaidGPA_tmp == 0){
                        //     rssaidGPA = ' . $sbjctid . ';
                        //     console.log(rssaidGPA);
                        //     rssaidGPA_tmp = 1;
                        // }
                    </script>';

            $query2 = $this->db->query("SELECT
                                        t4.description,t1.rm_sctn_sbjct_assgnmnt_id AS rssa_id,t1.room_section_id AS rsid,
                                        t1.full_name,t1.grade,t1.sctn_nm,t2.m,t2.f,(t2.m + t2.f) AS t,t3.*
                                        FROM building_sectioning.view_subject_grdlvl_personnel_assgnmnt t1
                                        LEFT JOIN(
                                            SELECT
                                                t1.room_section_id AS rsid,
                                                SUM(CASE WHEN(t1.sex_bool = TRUE)THEN 1 END) AS m,
                                                SUM(CASE WHEN(t1.sex_bool = FALSE)THEN 1 END) AS f
                                            FROM sy$sy.bs_view_enrollment t1
                                            WHERE t1.status_id = 5
                                            GROUP BY t1.room_section_id )t2 ON t1.room_section_id = t2.rsid
                                        LEFT JOIN (
                                            SELECT * FROM gpa('$ssy','$qrtr') AS 
                                                    (rssaid bigint,m_b75 bigint,f_b75 bigint,
                                                    t_b75 bigint,m_75_79 bigint,f_75_79 bigint,
                                                    t_75_79 bigint,m_80_84 bigint,f_80_84 bigint,
                                                    t_80_84 bigint,m_85_89 bigint,f_85_89 bigint,
                                                    t_85_89 bigint,m_90_94 bigint,f_90_94 bigint,
                                                    t_90_94 bigint,m_95_100 bigint,f_95_100 bigint,
                                                    t_95_100 bigint,m_t bigint,f_t bigint,t bigint,qrtr bigint)
                                            ) t3 ON t1.rm_sctn_sbjct_assgnmnt_id = t3.rssaid
                                        LEFT JOIN global.tbl_party t4 ON t1.subject_id = t4.id
                                        WHERE t1.schl_yr_id = 1 AND t1.subject_id = $sbjctid
                                        ORDER BY t1.grade::Int,t1.sctn_nm");

            $a = '<div class="tab-pane fade show ' . $active . '" id="customGPA-tabs-' . $sbjctid . '" role="tabpanel" aria-labelledby="customGPA-tabs-' . $sbjctid . '-tab">
            <h5 class="font-weight-bold text-primary">' . strtoupper($sbjct) . '</h5>
            <table class="table table-sm table-hover table-striped" border="1" id="tblAllGradesList' . $sbjctid . '" width="100%">
                        <thead style="text-align:center">
                            <tr valign="center">
                                <th width="1" rowspan="2">#</th>
                                <th width="1" rowspan="2">NAME OF TEACHER</th>
                                <th width="1" rowspan="2">SECTION</th>
                                <th width="1" colspan="3">TOTAL ENROLLMENT</th>
                                <th width="1" rowspan="2">GPA</th>
                                <th width="1" colspan="3">75 BELOW</th>
                                <th width="1" colspan="3">75-79</th>
                                <th width="1" colspan="3">80-84</th>
                                <th width="1" colspan="3">85-89</th>
                                <th width="1" colspan="3">90-94</th>
                                <th width="1" colspan="3">95-100</th>
                                <th width="1" colspan="3">TOTAL</th>
                            </tr>
                            <tr>
                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>
                            </tr>
                        </thead>
                        <tbody>';

            $c_male = 1;
            $c_fmale = 1;

            foreach ($query2->result() as $key => $value) {
                $grade = $value->grade;
                $t_mf = $value->m + $value->f;
                $avg_ = $t_mf > 0 ? ($value->qrtr / $t_mf) : $value->qrtr;
                $avg_mps = number_format($avg_, 2);
                $cc++;

                if (($tmpGlvl != "") && ($tmpGlvl != $grade)) {
                    $t_mps += number_format((($st_mps) / ($cc - 1)), 2);
                    $tt_mps++;
                    $c .= '<tr style="font-weight:bold">
                                <td></td>
                                <td style="white-space:nowrap;"> </td>
                                <td style="white-space:nowrap;"> </td>
                                <td align="right">' . $st_m . '</td>
                                <td align="right">' . $st_f . '</td>
                                <td align="right">' . ($st_m + $st_f) . '</td>
                                <td align="right">' . number_format((($st_mps) / ($cc - 1)), 2) . '</td>
                                <td align="right">' . $st_mb7 . '</td>
                                <td align="right">' . $st_fb7 . '</td>
                                <td align="right">' . ($st_mb7 + $st_fb7) . '</td>
                                <td align="right">' . $st_m7 . '</td>
                                <td align="right">' . $st_f7 . '</td>
                                <td align="right">' . ($st_m7 + $st_f7) . '</td>
                                <td align="right">' . $st_m8 . '</td>
                                <td align="right">' . $st_f8 . '</td>
                                <td align="right">' . ($st_m8 + $st_f8) . '</td>
                                <td align="right">' . $st_m85 . '</td>
                                <td align="right">' . $st_f85 . '</td>
                                <td align="right">' . ($st_m85 + $st_f85) . '</td>
                                <td align="right">' . $st_m9 . '</td>
                                <td align="right">' . $st_f9 . '</td>
                                <td align="right">' . ($st_m9 + $st_f9) . '</td>
                                <td align="right">' . $st_m1 . '</td>
                                <td align="right">' . $st_f1 . '</td>
                                <td align="right">' . ($st_m1 + $st_f1) . '</td>
                                <td align="right">' . $st_mt . '</td>
                                <td align="right">' . $st_ft . '</td>
                                <td align="right">' . ($st_mt + $st_ft) . '</td>
                            </tr>
                            <tr style="font-size:5px;padding:0px">
                                <td colspan="28"> </td>
                            </tr>';
                    $st_m = 0;
                    $st_f = 0;
                    $st_mps = 0;
                    $st_mb7 = 0;
                    $st_fb7 = 0;
                    $st_m7 = 0;
                    $st_f7 = 0;
                    $st_m8 = 0;
                    $st_f8 = 0;
                    $st_m85 = 0;
                    $st_f85 = 0;
                    $st_m9 = 0;
                    $st_f9 = 0;
                    $st_m1 = 0;
                    $st_f1 = 0;
                    $st_mt = 0;
                    $st_ft = 0;

                    $cc = 1;
                }
                if ($tmpGlvl == "") {
                    $c .= '<tr style="font-weight:bold">
                                <td align="center" colspan="3">TOTAL</td>
                                <td align="right" class="gpa_m"></td>
                                <td align="right" class="gpa_f"></td>
                                <td align="right" class="gpa_t"></td>
                                <td align="right" class="gpa_avg"></td>
                                <td align="right" class="gpa_mb7"></td>
                                <td align="right" class="gpa_fb7"></td>
                                <td align="right" class="gpa_tb7"></td>
                                <td align="right" class="gpa_m7"></td>
                                <td align="right" class="gpa_f7"></td>
                                <td align="right" class="gpa_t7"></td>
                                <td align="right" class="gpa_m8"></td>
                                <td align="right" class="gpa_b8"></td>
                                <td align="right" class="gpa_t8"></td>
                                <td align="right" class="gpa_m85"></td>
                                <td align="right" class="gpa_b85"></td>
                                <td align="right" class="gpa_t85"></td>
                                <td align="right" class="gpa_m9"></td>
                                <td align="right" class="gpa_b9"></td>
                                <td align="right" class="gpa_t9"></td>
                                <td align="right" class="gpa_m1"></td>
                                <td align="right" class="gpa_b1"></td>
                                <td align="right" class="gpa_t1"></td>
                                <td align="right" class="gpa_mt"></td>
                                <td align="right" class="gpa_ft"></td>
                                <td align="right" class="gpa_tt"></td>
                            </tr>';
                }
                $c .= '<tr>
                                <td>' . $c_male++ . '</td>
                                <td style="white-space:nowrap;">' . $value->full_name . '</td>
                                <td style="white-space:nowrap;">' . $value->sctn_nm . '</td>
                                <td align="right">' . $value->m . '</td>
                                <td align="right">' . $value->f . '</td>
                                <td align="right">' . $t_mf . '</td>
                                <td align="right">' . $avg_mps . '</td>
                                <td align="right">' . $value->m_b75 . '</td>
                                <td align="right">' . $value->f_b75 . '</td>
                                <td align="right">' . $value->t_b75 . '</td>
                                <td align="right">' . $value->m_75_79 . '</td>
                                <td align="right">' . $value->f_75_79 . '</td>
                                <td align="right">' . $value->t_75_79 . '</td>
                                <td align="right">' . $value->m_80_84 . '</td>
                                <td align="right">' . $value->f_80_84 . '</td>
                                <td align="right">' . $value->t_80_84 . '</td>
                                <td align="right">' . $value->m_85_89 . '</td>
                                <td align="right">' . $value->f_85_89 . '</td>
                                <td align="right">' . $value->t_85_89 . '</td>
                                <td align="right">' . $value->m_90_94 . '</td>
                                <td align="right">' . $value->f_90_94 . '</td>
                                <td align="right">' . $value->t_90_94 . '</td>
                                <td align="right">' . $value->m_95_100 . '</td>
                                <td align="right">' . $value->f_95_100 . '</td>
                                <td align="right">' . $value->t_95_100 . '</td>
                                <td align="right">' . $value->m_t . '</td>
                                <td align="right">' . $value->f_t . '</td>
                                <td align="right">' . $value->t . '</td>
                            </tr>';
                $st_m += (int) $value->m;
                $st_f += (int) $value->f;
                $st_mps += $avg_mps;
                $st_mb7 += (int) $value->m_b75;
                $st_fb7 += (int) $value->f_b75;
                $st_m7 += (int) $value->m_75_79;
                $st_f7 += (int) $value->f_75_79;
                $st_m8 += (int) $value->m_80_84;
                $st_f8 += (int) $value->f_80_84;
                $st_m85 += (int) $value->m_85_89;
                $st_f85 += (int) $value->f_85_89;
                $st_m9 += (int) $value->f_90_94;
                $st_f9 += (int) $value->t_90_94;
                $st_m1 += (int) $value->m_95_100;
                $st_f1 += (int) $value->f_95_100;
                $st_mt += (int) $value->m_t;
                $st_ft += (int) $value->f_t;

                $t_m += (int) $value->m;
                $t_f += (int) $value->f;
                $t_t += ((int) $value->m + (int) $value->f);
                // $t_mps += (int) $value->m;
                // $tt_mps += (int) $value->m;
                $t_mb7 += (int) $value->m_b75;
                $t_fb7 += (int) $value->f_b75;
                $t_m7 += (int) $value->m_75_79;
                $t_f7 += (int) $value->f_75_79;
                $t_m8 += (int) $value->m_80_84;
                $t_f8 += (int) $value->f_80_84;
                $t_m85 += (int) $value->m_85_89;
                $t_f85 += (int) $value->f_85_89;
                $t_m9 += (int) $value->f_90_94;
                $t_f9 += (int) $value->t_90_94;
                $t_m1 += (int) $value->m_95_100;
                $t_f1 += (int) $value->f_95_100;
                $t_mt += (int) $value->m_t;
                $t_ft += (int) $value->f_t;

                $tmpGlvl = $grade;

                if ($key === array_key_last($query2->result())) {
                    $t_mps += number_format(($st_mps / $cc), 2);
                    $tt_mps++;
                    $t_mps = number_format(($t_mps / $tt_mps), 2);
                    $c .= '<tr style="font-weight:bold">
                                <td></td>
                                <td style="white-space:nowrap;"> </td>
                                <td style="white-space:nowrap;"> </td>
                                <td align="right">' . $st_m . '</td>
                                <td align="right">' . $st_f . '</td>
                                <td align="right">' . ($st_m + $st_f) . '</td>
                                <td align="right">' . number_format((($st_mps) / ($cc - 1)), 2) . '</td>
                                <td align="right">' . $st_mb7 . '</td>
                                <td align="right">' . $st_fb7 . '</td>
                                <td align="right">' . ($st_mb7 + $st_fb7) . '</td>
                                <td align="right">' . $st_m7 . '</td>
                                <td align="right">' . $st_f7 . '</td>
                                <td align="right">' . ($st_m7 + $st_f7) . '</td>
                                <td align="right">' . $st_m8 . '</td>
                                <td align="right">' . $st_f8 . '</td>
                                <td align="right">' . ($st_m8 + $st_f8) . '</td>
                                <td align="right">' . $st_m85 . '</td>
                                <td align="right">' . $st_f85 . '</td>
                                <td align="right">' . ($st_m85 + $st_f85) . '</td>
                                <td align="right">' . $st_m9 . '</td>
                                <td align="right">' . $st_f9 . '</td>
                                <td align="right">' . ($st_m9 + $st_f9) . '</td>
                                <td align="right">' . $st_m1 . '</td>
                                <td align="right">' . $st_f1 . '</td>
                                <td align="right">' . ($st_m1 + $st_f1) . '</td>
                                <td align="right">' . $st_mt . '</td>
                                <td align="right">' . $st_ft . '</td>
                                <td align="right">' . ($st_mt + $st_ft) . '</td>
                            </tr>
                            <script>
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_m").text("' . $t_m . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_f").text("' . $t_f . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_t").text("' . $t_t . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_avg").text("' . $t_mps . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_mb7").text("' . $t_mb7 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_fb7").text("' . $t_fb7 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_tb7").text("' . ($t_mb7 + $t_fb7) . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_m7").text("' . $t_m7 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_f7").text("' . $t_f7 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_t7").text("' . ($t_m7 + $t_f7) . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_m8").text("' . $t_m8 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_b8").text("' . $t_f8 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_t8").text("' . ($t_m8 + $t_f8) . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_m85").text("' . $t_m85 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_b85").text("' . $t_f85 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_t85").text("' . ($t_m85 + $t_f85) . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_m9").text("' . $t_m9 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_b9").text("' . $t_f9 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_t9").text("' . ($t_m9 + $t_f9) . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_m1").text("' . $t_m1 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_b1").text("' . $t_f1 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_t1").text("' . ($t_m1 + $t_f1) . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_mt").text("' . $t_mt . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_ft").text("' . $t_ft . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_tt").text("' . ($t_mt + $t_ft) . '");
                            </script>';
                }
            }


            $b =      '</tbody>
                    </table>
                  </div>';
            $content .= $a . $c . $b;
        }
        $data = '<div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="customGPA-tabs-four-tab" role="tablist">' . $tab . '</ul>
                    </div>
                    <div class="card-body">
                            <div class="tab-content" id="customGPA-tabs-one-tabContent">
                            ' . $content . '
                    </div>
                </div>
                <script>
                    rssaidGPA_tmp = 0;
                </script>';
        echo json_encode($data);
    }

    function getMPS_GPA_dept()
    {
        $tab = null;
        $content = null;
        $sy = $this->input->get("sy");
        $qrtr = $this->input->get("qrtr");
        $report = $this->input->get("report");
        $dept_id = $this->session->schoolmis_login_dept_id;
        $ssy = 'sy' . $sy;




        // $query1 = $this->db->query("SELECT * FROM global.tbl_party t1 WHERE t1.group_by = 11 AND (t1.id=70 OR t1.id=71 
        //                             OR t1.id=69 OR t1.id=72 OR t1.id=87 OR t1.id=68 
        //                             OR t1.id=88 OR t1.id=90 OR t1.id=91 OR t1.id=92 OR t1.id=93)
        //                             ORDER BY array_position(ARRAY[70,71,69,72,87,68,88,90,91,92,93]::bigint[],t1.id)");
        $query1 = $this->db->query("SELECT t1.* FROM global.tbl_party t1
                                    JOIN (SELECT t2.subject_id AS id FROM profile.tbl_school_department t1
                                                LEFT JOIN profile.tbl_school_department_subject t2 
                                                ON t1.id=t2.school_department_id
                                                WHERE t1.id = $dept_id
                                                ) t2 ON t1.id=t2.id
                                    WHERE t1.group_by = 11
                                    ORDER BY array_position(ARRAY[70,71,69,72,87,68,88,90,91,92,93]::bigint[],t1.id)");
        if ($report == "MPS") {
            foreach ($query1->result() as $key => $value) {
                $sbjctid = $value->id;
                $sbjct = $value->description;
                $sbjct_abbr = $value->abbr;
                $active = "";
                $tmpGlvl = "";
                $cc = 0;

                $st_m = 0;
                $st_f = 0;
                $st_mps = 0;
                $st_mb5 = 0;
                $st_fb5 = 0;
                $st_m5 = 0;
                $st_f5 = 0;
                $st_m7 = 0;
                $st_f7 = 0;
                $st_mt = 0;
                $st_ft = 0;

                $t_m = 0;
                $t_f = 0;
                $t_t = 0;
                $t_mps = 0;
                $tt_mps = 0;
                $t_mb5 = 0;
                $t_fb5 = 0;
                $t_m5 = 0;
                $t_f5 = 0;
                $t_m7 = 0;
                $t_f7 = 0;
                $t_mt = 0;
                $t_ft = 0;


                $a = null;
                $b = null;
                $c = null;
                if ($key === array_key_first($query1->result())) {
                    $active = "active";
                    // $tab .= '<li class="nav-item">
                    //         <a onclick="rssaid=0;" class="nav-link active" title="CONSOLIDATED" id="custom-tabs-0-tab" data-toggle="pill" href="#custom-tabs-0" role="tab" aria-controls="custom-tabs-0" aria-selected="false">CONSOLIDATED</a>
                    //     </li>';
                }
                $tab .= '<li class="nav-item">
                        <a onclick="rssaidMPS=' . $sbjctid . ';" class="nav-link ' . $active . '" title="' . $sbjct . '" id="customMPS-tabs-' . $sbjctid . '-tab" data-toggle="pill" href="#customMPS-tabs-' . $sbjctid . '" role="tab" aria-controls="customMPS-tabs-' . $sbjctid . '" aria-selected="false">' . $sbjct_abbr . '</a>
                    </li>
                    <script>
                        // if(rssaid_tmp == 0){
                        //     rssaid = ' . $sbjctid . ';
                        //     console.log(rssaid);
                        //     rssaid_tmp = 1;
                        // }
                    </script>';

                $query2 = $this->db->query("SELECT
                                        t4.description,t1.rm_sctn_sbjct_assgnmnt_id AS rssa_id,t1.room_section_id AS rsid,
                                        t1.full_name,t1.grade,t1.sctn_nm,t2.m,t2.f,(t2.m + t2.f) AS t,t3.*
                                        FROM building_sectioning.view_subject_grdlvl_personnel_assgnmnt t1
                                        LEFT JOIN(
                                            SELECT
                                                t1.room_section_id AS rsid,
                                                SUM(CASE WHEN(t1.sex_bool = TRUE)THEN 1 END) AS m,
                                                SUM(CASE WHEN(t1.sex_bool = FALSE)THEN 1 END) AS f
                                            FROM sy$sy.bs_view_enrollment t1
                                            WHERE t1.status_id = 5
                                            GROUP BY t1.room_section_id )t2 ON t1.room_section_id = t2.rsid
                                        LEFT JOIN (
                                            SELECT * FROM mps('$ssy','$qrtr') AS 
                                                    (rssaid bigint,m_b50 bigint,f_b50 bigint,
                                                    t_b50 bigint,m_51_75 bigint,f_51_75 bigint,
                                                    t_51_75 bigint,m_76_100 bigint,f_76_100 bigint,
                                                    t_76_100 bigint,m_t bigint,f_t bigint,t bigint,qrtr bigint)
                                            ) t3 ON t1.rm_sctn_sbjct_assgnmnt_id = t3.rssaid
                                        LEFT JOIN global.tbl_party t4 ON t1.subject_id = t4.id
                                        WHERE t1.schl_yr_id = 1 AND t1.subject_id = $sbjctid
                                        ORDER BY t1.grade::Int,t1.sctn_nm");

                $a = '<div class="tab-pane fade show ' . $active . '" id="customMPS-tabs-' . $sbjctid . '" role="tabpanel" aria-labelledby="customMPS-tabs-' . $sbjctid . '-tab">
            <h5 class="font-weight-bold text-primary">' . strtoupper($sbjct) . '</h5>
            <table class="table table-sm table-hover table-striped" border="1" id="tblAllGradesList' . $sbjctid . '" width="100%">
                        <thead style="text-align:center">
                            <tr valign="center">
                                <th width="1" rowspan="2">#</th>
                                <th width="1" rowspan="2">NAME OF TEACHER</th>
                                <th width="1" rowspan="2">SECTION</th>
                                <th width="1" colspan="3">TOTAL ENROLLMENT</th>
                                <th width="1" rowspan="2">MPS</th>
                                <th width="1" colspan="3">50 AND BELOW</th>
                                <th width="1" colspan="3">51-75</th>
                                <th width="1" colspan="3">76-100</th>
                                <th width="1" colspan="3">TOTAL</th>
                            </tr>
                            <tr>
                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>
                            </tr>
                        </thead>
                        <tbody>';

                foreach ($query2->result() as $key => $value) {
                    $grade = $value->grade;
                    $t_mf = $value->m + $value->f;
                    $avg_ = $t_mf > 0 ? ($value->qrtr / $t_mf) : $value->qrtr;
                    $avg_mps = number_format($avg_, 2);
                    $cc++;

                    if (($tmpGlvl != "") && ($tmpGlvl != $grade)) {
                        $t_mps += number_format((($st_mps) / ($cc - 1)), 2);
                        $tt_mps++;
                        $c .= '<tr style="font-weight:bold">
                                <td></td>
                                <td style="white-space:nowrap;"> </td>
                                <td style="white-space:nowrap;"> </td>
                                <td align="right">' . $st_m . '</td>
                                <td align="right">' . $st_f . '</td>
                                <td align="right">' . ($st_m + $st_f) . '</td>
                                <td align="right">' . number_format((($st_mps) / ($cc - 1)), 2) . '</td>
                                <td align="right">' . $st_mb5 . '</td>
                                <td align="right">' . $st_fb5 . '</td>
                                <td align="right">' . ($st_mb5 + $st_fb5) . '</td>
                                <td align="right">' . $st_m5 . '</td>
                                <td align="right">' . $st_f5 . '</td>
                                <td align="right">' . ($st_m5 + $st_f5) . '</td>
                                <td align="right">' . $st_m7 . '</td>
                                <td align="right">' . $st_f7 . '</td>
                                <td align="right">' . ($st_m7 + $st_f7) . '</td>
                                <td align="right">' . $st_mt . '</td>
                                <td align="right">' . $st_ft . '</td>
                                <td align="right">' . ($st_mt + $st_ft) . '</td>
                            </tr>
                            <tr style="font-size:5px;padding:0px">
                                <td colspan="19"> </td>
                            </tr>';
                        $st_m = 0;
                        $st_f = 0;
                        $st_mps = 0;
                        $st_mb5 = 0;
                        $st_fb5 = 0;
                        $st_m5 = 0;
                        $st_f5 = 0;
                        $st_m7 = 0;
                        $st_f7 = 0;
                        $st_mt = 0;
                        $st_ft = 0;

                        $cc = 1;
                    }
                    if ($tmpGlvl == "") {
                        $c .= '<tr style="font-weight:bold">
                                <td align="center" colspan="3">TOTAL</td>
                                <td align="right" class="mps_m"></td>
                                <td align="right" class="mps_f"></td>
                                <td align="right" class="mps_t"></td>
                                <td align="right" class="mps_avg"></td>
                                <td align="right" class="mps_mb5"></td>
                                <td align="right" class="mps_fb5"></td>
                                <td align="right" class="mps_tb5"></td>
                                <td align="right" class="mps_m5"></td>
                                <td align="right" class="mps_f5"></td>
                                <td align="right" class="mps_t5"></td>
                                <td align="right" class="mps_m7"></td>
                                <td align="right" class="mps_b7"></td>
                                <td align="right" class="mps_t7"></td>
                                <td align="right" class="mps_mt"></td>
                                <td align="right" class="mps_ft"></td>
                                <td align="right" class="mps_tt"></td>
                            </tr>';
                    }
                    $c .= '<tr>
                                <td>' . $cc . '</td>
                                <td style="white-space:nowrap;">' . $value->full_name . '</td>
                                <td style="white-space:nowrap;">' . $value->sctn_nm . '</td>
                                <td align="right">' . $value->m . '</td>
                                <td align="right">' . $value->f . '</td>
                                <td align="right">' . $t_mf . '</td>
                                <td align="right">' . $avg_mps . '</td>
                                <td align="right">' . $value->m_b50 . '</td>
                                <td align="right">' . $value->f_b50 . '</td>
                                <td align="right">' . $value->t_b50 . '</td>
                                <td align="right">' . $value->m_51_75 . '</td>
                                <td align="right">' . $value->f_51_75 . '</td>
                                <td align="right">' . $value->t_51_75 . '</td>
                                <td align="right">' . $value->m_76_100 . '</td>
                                <td align="right">' . $value->f_76_100 . '</td>
                                <td align="right">' . $value->t_76_100 . '</td>
                                <td align="right">' . $value->m_t . '</td>
                                <td align="right">' . $value->f_t . '</td>
                                <td align="right">' . $value->t . '</td>
                            </tr>';
                    $st_m += (int) $value->m;
                    $st_f += (int) $value->f;
                    $st_mps += $avg_mps;
                    $st_mb5 += (int) $value->m_b50;
                    $st_fb5 += (int) $value->f_b50;
                    $st_m5 += (int) $value->m_51_75;
                    $st_f5 += (int) $value->f_51_75;
                    $st_m7 += (int) $value->m_76_100;
                    $st_f7 += (int) $value->f_76_100;
                    $st_mt += (int) $value->m_t;
                    $st_ft += (int) $value->f_t;

                    $t_m += (int) $value->m;
                    $t_f += (int) $value->f;
                    $t_t += ((int) $value->m + (int) $value->f);
                    $t_mb5  += (int) $value->m_b50;
                    $t_fb5  += (int) $value->f_b50;
                    $t_m5 += (int) $value->m_51_75;
                    $t_f5 += (int) $value->f_51_75;
                    $t_m7 += (int) $value->m_76_100;
                    $t_f7 += (int) $value->f_76_100;
                    $t_mt += (int) $value->m_t;
                    $t_ft += (int) $value->f_t;


                    $tmpGlvl = $grade;

                    if ($key === array_key_last($query2->result())) {
                        $t_mps += number_format(($st_mps / $cc), 2);
                        $tt_mps++;
                        $t_mps = number_format(($t_mps / $tt_mps), 2);
                        $c .= '<tr style="font-weight:bold">
                                <td></td>
                                <td style="white-space:nowrap;"> </td>
                                <td style="white-space:nowrap;"> </td>
                                <td align="right">' . $st_m . '</td>
                                <td align="right">' . $st_f . '</td>
                                <td align="right">' . ($st_m + $st_f) . '</td>
                                <td align="right">' . number_format(($st_mps / $cc), 2) . '</td>
                                <td align="right">' . $st_mb5 . '</td>
                                <td align="right">' . $st_fb5 . '</td>
                                <td align="right">' . ($st_mb5 + $st_fb5) . '</td>
                                <td align="right">' . $st_m5 . '</td>
                                <td align="right">' . $st_f5 . '</td>
                                <td align="right">' . ($st_m5 + $st_f5) . '</td>
                                <td align="right">' . $st_m7 . '</td>
                                <td align="right">' . $st_f7 . '</td>
                                <td align="right">' . ($st_m7 + $st_f7) . '</td>
                                <td align="right">' . $st_mt . '</td>
                                <td align="right">' . $st_ft . '</td>
                                <td align="right">' . ($st_mt + $st_ft) . '</td>
                            </tr>
                            <script>
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_m").text("' . $t_m . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_f").text("' . $t_f . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_t").text("' . $t_t . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_avg").text("' . $t_mps . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_mb5").text("' . $t_mb5 . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_fb5").text("' . $t_fb5 . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_tb5").text("' . ($t_fb5 + $t_mb5) . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_m5").text("' . $t_m5 . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_f5").text("' . $t_f5 . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_t5").text("' . ($t_m5 + $t_f5) . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_m7").text("' . $t_m7 . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_b7").text("' . $t_f7 . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_t7").text("' . ($t_m7 + $t_f7) . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_mt").text("' . $t_mt . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_ft").text("' . $t_ft . '");
                                $("#customMPS-tabs-' . $sbjctid . ' .mps_tt").text("' . ($t_mt + $t_ft) . '");
                            </script>';
                    }
                }


                $b =      '</tbody>
                    </table>
                  </div>';
                $content .= $a . $c . $b;
            }
            $data = '<div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="customMPS-tabs-four-tab" role="tablist">' . $tab . '</ul>
                    </div>
                    <div class="card-body">
                            <div class="tab-content" id="customMPS-tabs-one-tabContent">
                            ' . $content . '
                    </div>
                </div>
                <script>
                    rssaidMPS_tmp = 0;
                </script>';
        }
        if ($report == "GPA") {
            foreach ($query1->result() as $key => $value) {
                $sbjctid = $value->id;
                $sbjct = $value->description;
                $sbjct_abbr = $value->abbr;
                $active = "";
                $tmpGlvl = "";
                $cc = 0;

                $st_m = 0;
                $st_f = 0;
                $st_mps = 0;
                $st_mb7 = 0;
                $st_fb7 = 0;
                $st_m7 = 0;
                $st_f7 = 0;
                $st_m8 = 0;
                $st_f8 = 0;
                $st_m85 = 0;
                $st_f85 = 0;
                $st_m9 = 0;
                $st_f9 = 0;
                $st_m1 = 0;
                $st_f1 = 0;
                $st_mt = 0;
                $st_ft = 0;

                $t_m = 0;
                $t_f = 0;
                $t_t = 0;
                $t_mps = 0;
                $tt_mps = 0;
                $t_mb7 = 0;
                $t_fb7 = 0;
                $t_m7 = 0;
                $t_f7 = 0;
                $t_m8 = 0;
                $t_f8 = 0;
                $t_m85 = 0;
                $t_f85 = 0;
                $t_m9 = 0;
                $t_f9 = 0;
                $t_m1 = 0;
                $t_f1 = 0;
                $t_mt = 0;
                $t_ft = 0;

                $a = null;
                $b = null;
                $c = null;
                if ($key === array_key_first($query1->result())) {
                    $active = "active";
                    // $tab .= '<li class="nav-item">
                    //         <a onclick="rssaid=0;" class="nav-link active" title="CONSOLIDATED" id="custom-tabs-0-tab" data-toggle="pill" href="#custom-tabs-0" role="tab" aria-controls="custom-tabs-0" aria-selected="false">CONSOLIDATED</a>
                    //     </li>';
                }
                $tab .= '<li class="nav-item">
                        <a onclick="rssaidGPA=' . $sbjctid . ';" class="nav-link ' . $active . '" title="' . $sbjct . '" id="customGPA-tabs-' . $sbjctid . '-tab" data-toggle="pill" href="#customGPA-tabs-' . $sbjctid . '" role="tab" aria-controls="customGPA-tabs-' . $sbjctid . '" aria-selected="false">' . $sbjct_abbr . '</a>
                    </li>
                    <script>
                        // if(rssaidGPA_tmp == 0){
                        //     rssaidGPA = ' . $sbjctid . ';
                        //     console.log(rssaidGPA);
                        //     rssaidGPA_tmp = 1;
                        // }
                    </script>';

                $query2 = $this->db->query("SELECT
                                        t4.description,t1.rm_sctn_sbjct_assgnmnt_id AS rssa_id,t1.room_section_id AS rsid,
                                        t1.full_name,t1.grade,t1.sctn_nm,t2.m,t2.f,(t2.m + t2.f) AS t,t3.*
                                        FROM building_sectioning.view_subject_grdlvl_personnel_assgnmnt t1
                                        LEFT JOIN(
                                            SELECT
                                                t1.room_section_id AS rsid,
                                                SUM(CASE WHEN(t1.sex_bool = TRUE)THEN 1 END) AS m,
                                                SUM(CASE WHEN(t1.sex_bool = FALSE)THEN 1 END) AS f
                                            FROM sy$sy.bs_view_enrollment t1
                                            WHERE t1.status_id = 5
                                            GROUP BY t1.room_section_id )t2 ON t1.room_section_id = t2.rsid
                                        LEFT JOIN (
                                            SELECT * FROM gpa('$ssy','$qrtr') AS 
                                                    (rssaid bigint,m_b75 bigint,f_b75 bigint,
                                                    t_b75 bigint,m_75_79 bigint,f_75_79 bigint,
                                                    t_75_79 bigint,m_80_84 bigint,f_80_84 bigint,
                                                    t_80_84 bigint,m_85_89 bigint,f_85_89 bigint,
                                                    t_85_89 bigint,m_90_94 bigint,f_90_94 bigint,
                                                    t_90_94 bigint,m_95_100 bigint,f_95_100 bigint,
                                                    t_95_100 bigint,m_t bigint,f_t bigint,t bigint,qrtr bigint)
                                            ) t3 ON t1.rm_sctn_sbjct_assgnmnt_id = t3.rssaid
                                        LEFT JOIN global.tbl_party t4 ON t1.subject_id = t4.id
                                        WHERE t1.schl_yr_id = 1 AND t1.subject_id = $sbjctid
                                        ORDER BY t1.grade::Int,t1.sctn_nm");

                $a = '<div class="tab-pane fade show ' . $active . '" id="customGPA-tabs-' . $sbjctid . '" role="tabpanel" aria-labelledby="customGPA-tabs-' . $sbjctid . '-tab">
            <h5 class="font-weight-bold text-primary">' . strtoupper($sbjct) . '</h5>
            <table class="table table-sm table-hover table-striped" border="1" id="tblAllGradesList' . $sbjctid . '" width="100%">
                        <thead style="text-align:center">
                            <tr valign="center">
                                <th width="1" rowspan="2">#</th>
                                <th width="1" rowspan="2">NAME OF TEACHER</th>
                                <th width="1" rowspan="2">SECTION</th>
                                <th width="1" colspan="3">TOTAL ENROLLMENT</th>
                                <th width="1" rowspan="2">GPA</th>
                                <th width="1" colspan="3">75 BELOW</th>
                                <th width="1" colspan="3">75-79</th>
                                <th width="1" colspan="3">80-84</th>
                                <th width="1" colspan="3">85-89</th>
                                <th width="1" colspan="3">90-94</th>
                                <th width="1" colspan="3">95-100</th>
                                <th width="1" colspan="3">TOTAL</th>
                            </tr>
                            <tr>
                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>

                                <th width="1">M</th>
                                <th width="1">F</th>
                                <th width="1">T</th>
                            </tr>
                        </thead>
                        <tbody>';

                $c_male = 1;
                $c_fmale = 1;

                foreach ($query2->result() as $key => $value) {
                    $grade = $value->grade;
                    $t_mf = $value->m + $value->f;
                    $avg_ = $t_mf > 0 ? ($value->qrtr / $t_mf) : $value->qrtr;
                    $avg_mps = number_format($avg_, 2);
                    $cc++;

                    if (($tmpGlvl != "") && ($tmpGlvl != $grade)) {
                        $t_mps += number_format((($st_mps) / ($cc - 1)), 2);
                        $tt_mps++;
                        $c .= '<tr style="font-weight:bold">
                                <td></td>
                                <td style="white-space:nowrap;"> </td>
                                <td style="white-space:nowrap;"> </td>
                                <td align="right">' . $st_m . '</td>
                                <td align="right">' . $st_f . '</td>
                                <td align="right">' . ($st_m + $st_f) . '</td>
                                <td align="right">' . number_format((($st_mps) / ($cc - 1)), 2) . '</td>
                                <td align="right">' . $st_mb7 . '</td>
                                <td align="right">' . $st_fb7 . '</td>
                                <td align="right">' . ($st_mb7 + $st_fb7) . '</td>
                                <td align="right">' . $st_m7 . '</td>
                                <td align="right">' . $st_f7 . '</td>
                                <td align="right">' . ($st_m7 + $st_f7) . '</td>
                                <td align="right">' . $st_m8 . '</td>
                                <td align="right">' . $st_f8 . '</td>
                                <td align="right">' . ($st_m8 + $st_f8) . '</td>
                                <td align="right">' . $st_m85 . '</td>
                                <td align="right">' . $st_f85 . '</td>
                                <td align="right">' . ($st_m85 + $st_f85) . '</td>
                                <td align="right">' . $st_m9 . '</td>
                                <td align="right">' . $st_f9 . '</td>
                                <td align="right">' . ($st_m9 + $st_f9) . '</td>
                                <td align="right">' . $st_m1 . '</td>
                                <td align="right">' . $st_f1 . '</td>
                                <td align="right">' . ($st_m1 + $st_f1) . '</td>
                                <td align="right">' . $st_mt . '</td>
                                <td align="right">' . $st_ft . '</td>
                                <td align="right">' . ($st_mt + $st_ft) . '</td>
                            </tr>
                            <tr style="font-size:5px;padding:0px">
                                <td colspan="28"> </td>
                            </tr>';
                        $st_m = 0;
                        $st_f = 0;
                        $st_mps = 0;
                        $st_mb7 = 0;
                        $st_fb7 = 0;
                        $st_m7 = 0;
                        $st_f7 = 0;
                        $st_m8 = 0;
                        $st_f8 = 0;
                        $st_m85 = 0;
                        $st_f85 = 0;
                        $st_m9 = 0;
                        $st_f9 = 0;
                        $st_m1 = 0;
                        $st_f1 = 0;
                        $st_mt = 0;
                        $st_ft = 0;

                        $cc = 1;
                    }
                    if ($tmpGlvl == "") {
                        $c .= '<tr style="font-weight:bold">
                                <td align="center" colspan="3">TOTAL</td>
                                <td align="right" class="gpa_m"></td>
                                <td align="right" class="gpa_f"></td>
                                <td align="right" class="gpa_t"></td>
                                <td align="right" class="gpa_avg"></td>
                                <td align="right" class="gpa_mb7"></td>
                                <td align="right" class="gpa_fb7"></td>
                                <td align="right" class="gpa_tb7"></td>
                                <td align="right" class="gpa_m7"></td>
                                <td align="right" class="gpa_f7"></td>
                                <td align="right" class="gpa_t7"></td>
                                <td align="right" class="gpa_m8"></td>
                                <td align="right" class="gpa_b8"></td>
                                <td align="right" class="gpa_t8"></td>
                                <td align="right" class="gpa_m85"></td>
                                <td align="right" class="gpa_b85"></td>
                                <td align="right" class="gpa_t85"></td>
                                <td align="right" class="gpa_m9"></td>
                                <td align="right" class="gpa_b9"></td>
                                <td align="right" class="gpa_t9"></td>
                                <td align="right" class="gpa_m1"></td>
                                <td align="right" class="gpa_b1"></td>
                                <td align="right" class="gpa_t1"></td>
                                <td align="right" class="gpa_mt"></td>
                                <td align="right" class="gpa_ft"></td>
                                <td align="right" class="gpa_tt"></td>
                            </tr>';
                    }
                    $c .= '<tr>
                                <td>' . $c_male++ . '</td>
                                <td style="white-space:nowrap;">' . $value->full_name . '</td>
                                <td style="white-space:nowrap;">' . $value->sctn_nm . '</td>
                                <td align="right">' . $value->m . '</td>
                                <td align="right">' . $value->f . '</td>
                                <td align="right">' . $t_mf . '</td>
                                <td align="right">' . $avg_mps . '</td>
                                <td align="right">' . $value->m_b75 . '</td>
                                <td align="right">' . $value->f_b75 . '</td>
                                <td align="right">' . $value->t_b75 . '</td>
                                <td align="right">' . $value->m_75_79 . '</td>
                                <td align="right">' . $value->f_75_79 . '</td>
                                <td align="right">' . $value->t_75_79 . '</td>
                                <td align="right">' . $value->m_80_84 . '</td>
                                <td align="right">' . $value->f_80_84 . '</td>
                                <td align="right">' . $value->t_80_84 . '</td>
                                <td align="right">' . $value->m_85_89 . '</td>
                                <td align="right">' . $value->f_85_89 . '</td>
                                <td align="right">' . $value->t_85_89 . '</td>
                                <td align="right">' . $value->m_90_94 . '</td>
                                <td align="right">' . $value->f_90_94 . '</td>
                                <td align="right">' . $value->t_90_94 . '</td>
                                <td align="right">' . $value->m_95_100 . '</td>
                                <td align="right">' . $value->f_95_100 . '</td>
                                <td align="right">' . $value->t_95_100 . '</td>
                                <td align="right">' . $value->m_t . '</td>
                                <td align="right">' . $value->f_t . '</td>
                                <td align="right">' . $value->t . '</td>
                            </tr>';
                    $st_m += (int) $value->m;
                    $st_f += (int) $value->f;
                    $st_mps += $avg_mps;
                    $st_mb7 += (int) $value->m_b75;
                    $st_fb7 += (int) $value->f_b75;
                    $st_m7 += (int) $value->m_75_79;
                    $st_f7 += (int) $value->f_75_79;
                    $st_m8 += (int) $value->m_80_84;
                    $st_f8 += (int) $value->f_80_84;
                    $st_m85 += (int) $value->m_85_89;
                    $st_f85 += (int) $value->f_85_89;
                    $st_m9 += (int) $value->f_90_94;
                    $st_f9 += (int) $value->t_90_94;
                    $st_m1 += (int) $value->m_95_100;
                    $st_f1 += (int) $value->f_95_100;
                    $st_mt += (int) $value->m_t;
                    $st_ft += (int) $value->f_t;

                    $t_m += (int) $value->m;
                    $t_f += (int) $value->f;
                    $t_t += ((int) $value->m + (int) $value->f);
                    // $t_mps += (int) $value->m;
                    // $tt_mps += (int) $value->m;
                    $t_mb7 += (int) $value->m_b75;
                    $t_fb7 += (int) $value->f_b75;
                    $t_m7 += (int) $value->m_75_79;
                    $t_f7 += (int) $value->f_75_79;
                    $t_m8 += (int) $value->m_80_84;
                    $t_f8 += (int) $value->f_80_84;
                    $t_m85 += (int) $value->m_85_89;
                    $t_f85 += (int) $value->f_85_89;
                    $t_m9 += (int) $value->f_90_94;
                    $t_f9 += (int) $value->t_90_94;
                    $t_m1 += (int) $value->m_95_100;
                    $t_f1 += (int) $value->f_95_100;
                    $t_mt += (int) $value->m_t;
                    $t_ft += (int) $value->f_t;

                    $tmpGlvl = $grade;

                    if ($key === array_key_last($query2->result())) {
                        $t_mps += number_format(($st_mps / $cc), 2);
                        $tt_mps++;
                        $t_mps = number_format(($t_mps / $tt_mps), 2);
                        $c .= '<tr style="font-weight:bold">
                                <td></td>
                                <td style="white-space:nowrap;"> </td>
                                <td style="white-space:nowrap;"> </td>
                                <td align="right">' . $st_m . '</td>
                                <td align="right">' . $st_f . '</td>
                                <td align="right">' . ($st_m + $st_f) . '</td>
                                <td align="right">' . number_format((($st_mps) / ($cc - 1)), 2) . '</td>
                                <td align="right">' . $st_mb7 . '</td>
                                <td align="right">' . $st_fb7 . '</td>
                                <td align="right">' . ($st_mb7 + $st_fb7) . '</td>
                                <td align="right">' . $st_m7 . '</td>
                                <td align="right">' . $st_f7 . '</td>
                                <td align="right">' . ($st_m7 + $st_f7) . '</td>
                                <td align="right">' . $st_m8 . '</td>
                                <td align="right">' . $st_f8 . '</td>
                                <td align="right">' . ($st_m8 + $st_f8) . '</td>
                                <td align="right">' . $st_m85 . '</td>
                                <td align="right">' . $st_f85 . '</td>
                                <td align="right">' . ($st_m85 + $st_f85) . '</td>
                                <td align="right">' . $st_m9 . '</td>
                                <td align="right">' . $st_f9 . '</td>
                                <td align="right">' . ($st_m9 + $st_f9) . '</td>
                                <td align="right">' . $st_m1 . '</td>
                                <td align="right">' . $st_f1 . '</td>
                                <td align="right">' . ($st_m1 + $st_f1) . '</td>
                                <td align="right">' . $st_mt . '</td>
                                <td align="right">' . $st_ft . '</td>
                                <td align="right">' . ($st_mt + $st_ft) . '</td>
                            </tr>
                            <script>
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_m").text("' . $t_m . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_f").text("' . $t_f . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_t").text("' . $t_t . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_avg").text("' . $t_mps . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_mb7").text("' . $t_mb7 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_fb7").text("' . $t_fb7 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_tb7").text("' . ($t_mb7 + $t_fb7) . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_m7").text("' . $t_m7 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_f7").text("' . $t_f7 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_t7").text("' . ($t_m7 + $t_f7) . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_m8").text("' . $t_m8 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_b8").text("' . $t_f8 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_t8").text("' . ($t_m8 + $t_f8) . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_m85").text("' . $t_m85 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_b85").text("' . $t_f85 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_t85").text("' . ($t_m85 + $t_f85) . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_m9").text("' . $t_m9 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_b9").text("' . $t_f9 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_t9").text("' . ($t_m9 + $t_f9) . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_m1").text("' . $t_m1 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_b1").text("' . $t_f1 . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_t1").text("' . ($t_m1 + $t_f1) . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_mt").text("' . $t_mt . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_ft").text("' . $t_ft . '");
                                $("#customGPA-tabs-' . $sbjctid . ' .gpa_tt").text("' . ($t_mt + $t_ft) . '");
                            </script>';
                    }
                }


                $b =      '</tbody>
                    </table>
                  </div>';
                $content .= $a . $c . $b;
            }
            $data = '<div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="customGPA-tabs-four-tab" role="tablist">' . $tab . '</ul>
                    </div>
                    <div class="card-body">
                            <div class="tab-content" id="customGPA-tabs-one-tabContent">
                            ' . $content . '
                    </div>
                </div>
                <script>
                    rssaidGPA_tmp = 0;
                </script>';
        }
        echo json_encode($data);
    }

    function getGRADE_SLIP()
    {
        $tab = null;
        $arr = null;
        // $sy = $this->input->get("sy");
        // $qrtr = $this->input->get("qrtr");
        $rmsid = $this->input->get("rmsid");
        // $ssy = 'sy' . $sy;
        $sy = $this->getOnLoad()["sy_id"];

        //         SELECT t2.enrollment_id,t2.sctn_nm,t2.sy,t2.lrn,t2.last_fullname,
        // jsonb_agg(json_build_object(
        // 'order_by_sbjct',t1.order_by_sbjct,
        // 'q1',t4.q1,
        //         'q2',t4.q2,
        //         'q3',t4.q3,
        //         'q4',t4.q4,
        //         'rm_sctn_sbjct_assgnmnt_id',t1.rm_sctn_sbjct_assgnmnt_id,
        //         'enrollment_id',t2.enrollment_id,
        //         'subject',t1.subject,
        //         'subject_abbr',t1.subject_abbr,
        //         'schedule',t1.schedule,
        //         'advisory',t1.advisory,
        //         'full_name',t1.full_name,
        //         'personal_title',t1.personal_title,
        //         'parent_party_id',t1.parent_party_id)
        //         ORDER BY t1.order_by_sbjct) AS arr
        // FROM building_sectioning.view_subject_grdlvl_personnel_assgnmnt t1
        //                                     LEFT JOIN sy$sy.bs_view_enrollment t2 ON t1.room_section_id=t2.room_section_id AND t1.schl_yr_id=t2.schl_yr_id
        //                                     LEFT JOIN(SELECT t1.learner_enrollment_id,t1.rm_sctn_sbjct_assgnmnt_id,
        // 			                                    CASE WHEN (t1.q1stat='18') THEN t1.q1 ELSE 0 END q1,
        // 			                                    CASE WHEN (t1.q2stat='18') THEN t1.q2 ELSE 0 END q2,
        // 			                                    CASE WHEN (t1.q3stat='18') THEN t1.q3 ELSE 0 END q3,
        // 			                                    CASE WHEN (t1.q4stat='18') THEN t1.q4 ELSE 0 END q4
        // 			                                    FROM sy$sy.bs_m_view_grades t1) t4 ON t2.enrollment_id=t4.learner_enrollment_id AND t1.rm_sctn_sbjct_assgnmnt_id=t4.rm_sctn_sbjct_assgnmnt_id
        //                                     LEFT JOIN building_sectioning.view_room_section t5 ON t1.rm_sctn_sbjct_assgnmnt_id=t5.rm_sctn_sbjct_assgnmnt_id
        //                                     WHERE t1.room_section_id=30 -- AND t2.lrn ='214526130052' AND t1.schl_yr_id=1
        //                                     GROUP BY t2.enrollment_id,t2.sctn_nm,t2.sy,t2.lrn,t2.last_fullname
        // --                                    ORDER BY t1.order_by_sbjct

        $query1 = $this->db->query("SELECT t2.grade,t2.enrollment_id,t2.sctn_nm,t2.sy,t2.lrn,t2.last_fullname,
                                    jsonb_agg(json_build_object(
                                    'order_by_sbjct',t1.order_by_sbjct,
                                            'q1',t4.q1,
                                            'q2',t4.q2,
                                            'q3',t4.q3,
                                            'q4',t4.q4,
                                            'rm_sctn_sbjct_assgnmnt_id',t1.rm_sctn_sbjct_assgnmnt_id,
                                            'enrollment_id',t2.enrollment_id,
                                            'subject',t1.subject,
                                            'subject_abbr',t1.subject_abbr,
                                            'schedule',t1.schedule,
                                            'advisory',t1.advisory,
                                            'full_name',t1.full_name,
                                            'personal_title',t1.personal_title,
                                            'parent_party_id',t1.parent_party_id)
                                            ORDER BY t1.order_by_sbjct)::json AS arr
                                    FROM building_sectioning.view_subject_grdlvl_personnel_assgnmnt t1
                                            LEFT JOIN sy$sy.bs_view_enrollment t2 ON t1.room_section_id=t2.room_section_id AND t1.schl_yr_id=t2.schl_yr_id
                                            LEFT JOIN(SELECT t1.learner_enrollment_id,t1.rm_sctn_sbjct_assgnmnt_id,
                                                        CASE WHEN (t1.q1stat='18') THEN t1.q1 ELSE 0 END q1,
                                                        CASE WHEN (t1.q2stat='18') THEN t1.q2 ELSE 0 END q2,
                                                        CASE WHEN (t1.q3stat='18') THEN t1.q3 ELSE 0 END q3,
                                                        CASE WHEN (t1.q4stat='18') THEN t1.q4 ELSE 0 END q4
                                                        FROM sy$sy.bs_m_view_grades t1) t4 ON t2.enrollment_id=t4.learner_enrollment_id AND t1.rm_sctn_sbjct_assgnmnt_id=t4.rm_sctn_sbjct_assgnmnt_id
                                            LEFT JOIN building_sectioning.view_room_section t5 ON t1.rm_sctn_sbjct_assgnmnt_id=t5.rm_sctn_sbjct_assgnmnt_id
                                            WHERE t1.room_section_id=$rmsid AND t4.q1 IS NOT NULL  -- AND t2.lrn ='214526130052' AND t1.schl_yr_id=1
                                            GROUP BY t2.grade,t2.enrollment_id,t2.sctn_nm,t2.sy,t2.lrn,t2.last_fullname");
        foreach ($query1->result() as $key => $value) {
            $arr[] = [
                "enrollment_id" => $value->enrollment_id,
                "sctn_nm" => $value->sctn_nm,
                "grade" => $value->grade,
                "sy" => $value->sy,
                "lrn" => $value->lrn,
                "last_fullname" => $value->last_fullname,
                "grades" => $value->arr
            ];
        }
        $data = '[{"name":"John", "age":30, "city":"New York"}]';
        echo json_encode($arr);
        // $data = '<div class="card-header p-0 border-bottom-0">
        //                 <ul class="nav nav-tabs" id="customGPA-tabs-four-tab" role="tablist">' . $tab . '</ul>
        //             </div>
        //             <div class="card-body">
        //                     <div class="tab-content" id="customGPA-tabs-one-tabContent">
        //                     ' . $content . '
        //             </div>
        //         </div>
        //         <script>
        //             rssaidGPA_tmp = 0;
        //         </script>';
        // echo json_encode($data);
    }

    function getPreviewID()
    {
        $tab = null;
        $arr = null;
        // $sy = $this->input->get("sy");
        // $qrtr = $this->input->get("qrtr");
        $s = $this->input->get("s");
        $rmsid = $this->input->get("rmsid");
        $count = count($s);
        // $ssy = 'sy' . $sy;
        $w_lrn = null;
        for ($i = 0; $i < count($s); $i++) {
            // echo $s[$i];
            $parts = explode("_&&_", $s[$i]);
            $desiredValue = $parts[1];
            $w_lrn .= " t2.lrn='$desiredValue' ";
            if ($i === ($count - 1)) {
                // This is the last iteration, break out of the loop
                break;
            }else{
                $w_lrn .= " OR ";
            }
        }

        $sy = $this->getOnLoad()["sy_id"];

        //         SELECT t2.enrollment_id,t2.sctn_nm,t2.sy,t2.lrn,t2.last_fullname,
        // jsonb_agg(json_build_object(
        // 'order_by_sbjct',t1.order_by_sbjct,
        // 'q1',t4.q1,
        //         'q2',t4.q2,
        //         'q3',t4.q3,
        //         'q4',t4.q4,
        //         'rm_sctn_sbjct_assgnmnt_id',t1.rm_sctn_sbjct_assgnmnt_id,
        //         'enrollment_id',t2.enrollment_id,
        //         'subject',t1.subject,
        //         'subject_abbr',t1.subject_abbr,
        //         'schedule',t1.schedule,
        //         'advisory',t1.advisory,
        //         'full_name',t1.full_name,
        //         'personal_title',t1.personal_title,
        //         'parent_party_id',t1.parent_party_id)
        //         ORDER BY t1.order_by_sbjct) AS arr
        // FROM building_sectioning.view_subject_grdlvl_personnel_assgnmnt t1
        //                                     LEFT JOIN sy$sy.bs_view_enrollment t2 ON t1.room_section_id=t2.room_section_id AND t1.schl_yr_id=t2.schl_yr_id
        //                                     LEFT JOIN(SELECT t1.learner_enrollment_id,t1.rm_sctn_sbjct_assgnmnt_id,
        // 			                                    CASE WHEN (t1.q1stat='18') THEN t1.q1 ELSE 0 END q1,
        // 			                                    CASE WHEN (t1.q2stat='18') THEN t1.q2 ELSE 0 END q2,
        // 			                                    CASE WHEN (t1.q3stat='18') THEN t1.q3 ELSE 0 END q3,
        // 			                                    CASE WHEN (t1.q4stat='18') THEN t1.q4 ELSE 0 END q4
        // 			                                    FROM sy$sy.bs_m_view_grades t1) t4 ON t2.enrollment_id=t4.learner_enrollment_id AND t1.rm_sctn_sbjct_assgnmnt_id=t4.rm_sctn_sbjct_assgnmnt_id
        //                                     LEFT JOIN building_sectioning.view_room_section t5 ON t1.rm_sctn_sbjct_assgnmnt_id=t5.rm_sctn_sbjct_assgnmnt_id
        //                                     WHERE t1.room_section_id=30 -- AND t2.lrn ='214526130052' AND t1.schl_yr_id=1
        //                                     GROUP BY t2.enrollment_id,t2.sctn_nm,t2.sy,t2.lrn,t2.last_fullname
        // --                                    ORDER BY t1.order_by_sbjct

        $query1 = $this->db->query("SELECT t2.grade,t2.enrollment_id,t2.sctn_nm,t5.program_strand,t5.program_strand_color,t5.program,t5.grade_k,t5.color_k,t2.sy,t2.lrn,t2.last_name,t2.first_name,COALESCE(t2.middle_name) AS middle_name,t2.last_fullname,t2.full_name,t2.img_path,to_char(t2.birthdate, 'Month DD, YYYY') as birthdate,REPLACE(t2.address_details,' (Capital)','') AS address_details,t5.full_name AS adviser,t2.other_details
                                    FROM building_sectioning.view_subject_grdlvl_personnel_assgnmnt t1
                                            LEFT JOIN sy$sy.bs_view_enrollment t2 ON t1.room_section_id=t2.room_section_id AND t1.schl_yr_id=t2.schl_yr_id
                                            LEFT JOIN building_sectioning.view_room_section t5 ON t1.room_section_id=t5.id
                                            -- WHERE t1.room_section_id=$rmsid $w_lrn-- AND t4.q1 IS NOT NULL  -- AND t2.lrn ='214526130052' AND t1.schl_yr_id=1
                                            WHERE $w_lrn
                                            GROUP BY t2.grade,t2.enrollment_id,t2.sctn_nm,t5.program_strand,t5.program_strand_color,t5.program,t5.grade_k,t5.color_k,t2.sy,t2.lrn,t2.last_name,t2.first_name,COALESCE(t2.middle_name),t2.last_fullname,t2.full_name,t2.img_path,t2.birthdate,t2.address_details,t5.full_name,t2.sex_bool,t2.other_details
                                            ORDER BY t2.sex_bool DESC, t2.last_fullname ASC");
        foreach ($query1->result() as $key => $value) {
            $arr[] = [
                "enrollment_id" => $value->enrollment_id,
                "sctn_nm" => $value->sctn_nm,
                "program" => $value->program,
                "program_strand" => $value->program_strand,
                "program_strand_color" => $value->program_strand_color,
                "grade" => $value->grade,
                "sy" => $value->sy,
                "lrn" => $value->lrn,
                "last_name" => $this->readQuote($value->last_name),
                "first_name" => $this->readQuote($value->first_name),
                "middle_name" => $this->readQuote($value->middle_name),
                "first_minitial" => $this->readQuote($value->first_name) . ($value->middle_name != '' ? ($value->middle_name != '-' ? ' ' . $value->middle_name[0] . '.' : '') : ''),
                "lrn" => $value->lrn,
                "last_fullname" => $this->readQuote($value->last_fullname),
                "full_name" => $this->readQuote($value->full_name),
                "img_path" => $this->getImg($value->img_path),
                "birthdate" => $value->birthdate,
                "advisory" => $value->adviser, //$this->font_id(, 15, 19, 10),
                "grade_k" => $value->grade_k,
                "color_k" => $value->color_k,
                "address_details" => $this->readQuote($value->address_details),
                "other_details" => $value->other_details,
            ];
        }
        echo json_encode($arr);
        // $data = '<div class="card-header p-0 border-bottom-0">
        //                 <ul class="nav nav-tabs" id="customGPA-tabs-four-tab" role="tablist">' . $tab . '</ul>
        //             </div>
        //             <div class="card-body">
        //                     <div class="tab-content" id="customGPA-tabs-one-tabContent">
        //                     ' . $content . '
        //             </div>
        //         </div>
        //         <script>
        //             rssaidGPA_tmp = 0;
        //         </script>';
        // echo json_encode($data);
    }

    function getPreviewDTR()
    {
        // WITH Days AS (
        //     SELECT generate_series(1, 31) AS day_of_month
        //   )
        //   SELECT
        //     d.day_of_month,
        //     TO_CHAR(DATE '2023-11-01' + (d.day_of_month - 1), 'dd') AS day_cc,
        //     t1.uuid,
        //     t1.am_in,
        //     t1.am_out,
        //     t1.pm_in,
        //     t1.pm_out,
        //     CASE
        //       WHEN EXTRACT(ISODOW FROM DATE '2023-11-01' + (d.day_of_month - 1)) = 6 THEN 'SAT' -- 6 = Saturday
        //       WHEN EXTRACT(ISODOW FROM DATE '2023-11-01' + (d.day_of_month - 1)) = 7 THEN 'SUN' -- 7 = Sunday
        //       ELSE 'WD' -- WD = Weekday
        //     END AS day_type
        //   FROM
        //     Days d
        //   LEFT JOIN
        //     dtr.tbl_dtr t1 ON t1.personnel_id = 1
        //                    AND EXTRACT(MONTH FROM t1.date) = 11
        //                    AND EXTRACT(YEAR FROM t1.date) = 2023
        //                    AND EXTRACT(DAY FROM t1.date) = d.day_of_month
        //   ORDER BY
        //     d.day_of_month;
    }
}
