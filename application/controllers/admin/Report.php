<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends MY_Controller
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
            "page_title"    => "Report",
            "current_location"  => "report",
        ];
        $this->load->view('interface/admin/Report', $data);
    }

    public function get_job_factor_report()
    {
        $from_date   = $this->input->get('from_date');
        $to_date     = $this->input->get('to_date');
        $category_id = $this->input->get('category_id[value]');

        $where = "WHERE f.date_created BETWEEN ? AND ?";

        $params = [$from_date, $to_date];

        if (!empty($category_id)) {
            $where .= " AND f.category_id = ? ";
            $params[] = $category_id;
        }

        $sql_job_factors = "SELECT fs.rating_txt, CASE WHEN fs.rating_txt='VS' THEN 'Very Satisfied'
                WHEN fs.rating_txt='S' THEN 'Satisfied' WHEN fs.rating_txt='D' THEN 'Dissatisfied'
                WHEN fs.rating_txt='VD' THEN 'Very Dissatisfied' END AS rating_meaning, count(fs.id) AS count From feedback_service fs
                LEFT JOIN feedback f on fs.feedback_id = f.id
                $where
                group by fs.rating_txt
                ORDER BY fs.rating_int";

        $query_job_factors = $this->db->query($sql_job_factors, $params);
        $rows_job_factors = $query_job_factors->result_array();

        $defaults['job_factors'] = [
            'VS' => ['rating_txt' => 'VS', 'rating_meaning' => 'Very Satisfied', 'count' => 0],
            'S'  => ['rating_txt' => 'S',  'rating_meaning' => 'Satisfied', 'count' => 0],
            'D'  => ['rating_txt' => 'D',  'rating_meaning' => 'Dissatisfied', 'count' => 0],
            'VD' => ['rating_txt' => 'VD', 'rating_meaning' => 'Very Dissatisfied', 'count' => 0],
        ];

        foreach ($rows_job_factors as $row) {
            $defaults['job_factors'][$row['rating_txt']]['count'] = (int) $row['count'];
        }




        $sql_sentiment = "SELECT fs.sentiment as rating_txt, count(1) AS count 
                FROM feedback_sentiment fs
                LEFT JOIN feedback f ON fs.feedback_id = f.id
                $where
                GROUP BY fs.sentiment;";

        $query_sentiment = $this->db->query($sql_sentiment, $params);
        $rows_sentiment = $query_sentiment->result_array();

        $defaults['sentiment'] = [
            'positive' => ['rating_txt' => 'positive', 'rating_meaning' => 'Positive', 'count' => 0],
            'negative'  => ['rating_txt' => 'negative',  'rating_meaning' => 'Negative', 'count' => 0],
            'neutral'  => ['rating_txt' => 'neutral',  'rating_meaning' => 'Neutral', 'count' => 0],
        ];

        foreach ($rows_sentiment as $row) {
            $defaults['sentiment'][$row['rating_txt']]['count'] = (int) $row['count'];
        }

        echo json_encode($defaults);
    }
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */