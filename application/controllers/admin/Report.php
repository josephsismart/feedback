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
        $category_id = $this->input->get('category_id');

        $where = "WHERE f.date_created BETWEEN ? AND ?";

        $params = [$from_date, $to_date];

        if (!empty($category_id)) {
            $where .= " AND f.category_id = ? ";
            $params[] = $category_id;
        }

        $sql_job_factors = "WITH RECURSIVE category_tree AS (
                                    SELECT id
                                    FROM category
                                    WHERE id = $category_id

                                    UNION ALL

                                    SELECT c.id
                                    FROM category c
                                    INNER JOIN category_tree ct ON c.parent_id = ct.id
                                )
                                SELECT
                                    fs.rating_txt,
                                    CASE
                                        WHEN fs.rating_txt = 'VS' THEN 'Very Satisfied'
                                        WHEN fs.rating_txt = 'S'  THEN 'Satisfied'
                                        WHEN fs.rating_txt = 'D'  THEN 'Dissatisfied'
                                        WHEN fs.rating_txt = 'VD' THEN 'Very Dissatisfied'
                                    END AS rating_meaning,
                                    COUNT(fs.id) AS count
                                FROM feedback_service fs
                                LEFT JOIN feedback f ON fs.feedback_id = f.id
                                WHERE f.date_created BETWEEN '$from_date' AND '$to_date'
                                AND (
                                    f.category_id IN (SELECT id FROM category_tree)
                                )
                                GROUP BY fs.rating_txt
                                ORDER BY fs.rating_int";

        $query_job_factors = $this->db->query($sql_job_factors);
        $rows_job_factors = $query_job_factors->result_array();
        if (count($rows_job_factors) == 0) {
            $defaults['empty_data'] = true;
        } else {
            $defaults['empty_data'] = false;
        }

        $defaults['job_factors'] = [
            'VS' => ['rating_txt' => 'VS', 'rating_meaning' => 'Very Satisfied', 'count' => 0],
            'S'  => ['rating_txt' => 'S',  'rating_meaning' => 'Satisfied', 'count' => 0],
            'D'  => ['rating_txt' => 'D',  'rating_meaning' => 'Dissatisfied', 'count' => 0],
            'VD' => ['rating_txt' => 'VD', 'rating_meaning' => 'Very Dissatisfied', 'count' => 0],
        ];

        foreach ($rows_job_factors as $row) {
            $defaults['job_factors'][$row['rating_txt']]['count'] = (int) $row['count'];
        }




        $sql_sentiment = "WITH RECURSIVE category_tree AS (
                                SELECT id
                                FROM category
                                WHERE id = $category_id

                                UNION ALL

                                SELECT c.id
                                FROM category c
                                INNER JOIN category_tree ct ON c.parent_id = ct.id
                            )
                            SELECT
                                fs.sentiment AS rating_txt,
                                COUNT(1) AS count
                            FROM feedback_sentiment fs
                            LEFT JOIN feedback f ON fs.feedback_id = f.id
                            WHERE f.date_created BETWEEN '$from_date' AND '$to_date'
                            AND (
                                f.category_id IN (SELECT id FROM category_tree)
                            )
                            GROUP BY fs.sentiment
                            ORDER BY fs.sentiment";

        $query_sentiment = $this->db->query($sql_sentiment);
        $rows_sentiment = $query_sentiment->result_array();

        $defaults['sentiment'] = [
            'positive' => ['rating_txt' => 'positive', 'rating_meaning' => 'Positive', 'count' => 0],
            'negative'  => ['rating_txt' => 'negative',  'rating_meaning' => 'Negative', 'count' => 0],
            'neutral'  => ['rating_txt' => 'neutral',  'rating_meaning' => 'Neutral', 'count' => 0],
        ];

        

        $sql_sentiment_word = "WITH RECURSIVE category_tree AS (
                                SELECT id
                                FROM category
                                WHERE id = $category_id

                                UNION ALL

                                SELECT c.id
                                FROM category c
                                INNER JOIN category_tree ct ON c.parent_id = ct.id
                            )
                            SELECT
                                sw.word        AS sentiment_word,
                                sw.type   AS sentiment_type,
                                COUNT(*)       AS count
                            FROM feedback f
                            INNER JOIN sentiment_words sw
                                ON POSITION(LOWER(sw.word) IN LOWER(f.comment)) > 0
                            WHERE f.date_created BETWEEN '$from_date' AND '$to_date'
                            AND f.category_id IN (SELECT id FROM category_tree)
                            GROUP BY sw.word
                            ORDER BY count DESC
                            LIMIT 1";
                            

        $query_sentiment_word = $this->db->query($sql_sentiment_word);
        $rows_sentiment_word = $query_sentiment_word->result_array();
        // echo $rows_sentiment;
        foreach ($rows_sentiment_word as $row) {
            $defaults['sentiment_word'] = $row['sentiment_word'];
            // $defaults['sentiment_word_ai'] = $this->ask($row['sentiment_word'])['text'];
            $defaults['sentiment_type'] = $row['sentiment_type'];
        }
        

        $total = 0;
        foreach ($rows_sentiment as $row) {
            $count = (int) $row['count'];
            $defaults['sentiment'][$row['rating_txt']]['count'] = $count;
            $total += $count;
        }

        /* compute percent (total = 100%) */
        if ($total > 0) {
            foreach ($defaults['sentiment'] as &$s) {
                $s['percent'] = round(($s['count'] / $total) * 100, 2);
            }
        }

        echo json_encode($defaults);
    }

    public function get_sentiment_word_ai()
    {
        $word = $this->input->get('word');
        $result = $this->ask($word);
        echo $result;
    }
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */