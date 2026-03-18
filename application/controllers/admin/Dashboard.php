<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->redirect();
    }

    public function index()
    {
        // $page_data = $this->system();
        // $uri = $this->session->feedback_login_uri;
        // $page_data += [
        //     "page_title"        => "Dashboard",
        //     "current_location"  => "dashboard",
        //     "content"           =>  [$this->load->view('interface/admin/Dashboard', [
        //                             ], TRUE)]
        // ];
        // $this->public_create_page($page_data);


        $this->redirect();
        $data = $this->system();
        $data += [
            "page_title"    => "Dashboard",
            "current_location"  => "dashboard",
        ];
        $this->load->view('interface/admin/Dashboard', $data);
    }


    function highlight_sentiment_words($comment, $words)
    {
        foreach ($words as $wordInfo) {
            $word = $wordInfo->word; #preg_quote($wordInfo->word, '/');
            $type = $wordInfo->type;
            $weight = $wordInfo->weight ?? 1; // default 1 if not provided

            $color = match ($type) {
                'positive' => 'green',
                'negative' => 'red',
                default    => 'black'
            };

            $tooltip = ucfirst($type) . " (" . $weight . ")";

            $comment = preg_replace(
                "/\b($word)\b/i",
                "<span style='color:$color; font-weight:bold;' title='$tooltip'>$1</span>",
                $comment
            );
        }
        return $comment;
    }

    public function getFeedbackReports()
    {
        $from_date = $this->input->get('from');
        $to_date   = $this->input->get('to');
        $category_id = $this->input->get('category_id');
        $this->evaluate_all_feedback_sentiment();

        $result = [
            'total'      => 0,
            'positive'   => 0,
            'negative'   => 0,
            'neutral'    => 0,
            'top_words'  => [],
            'root_names' => []
        ];

        /* -------------------------------
       DATE FILTER
       ------------------------------- */
        $dateWhere = "";
        $dateWhere2 = "";

        if ($category_id) {
            $firstDegreeIds = $this->getFirstDegreeCategoryIds($category_id);
            $category_ids = $this->getCategoryAndChildrenIds($category_id);
            $dateWhere .= " AND f.category_id IN ($category_ids)";
            $dateWhere2 .= " AND f.category_id IN ($firstDegreeIds)";
        }
        if ($from_date && $to_date) {
            $dateWhere .= " AND (f.date_created BETWEEN '{$from_date}' AND '{$to_date}')";
            $dateWhere2 .= " AND (f.date_created BETWEEN '{$from_date}' AND '{$to_date}')";
        }

        /* =========================================================
       1. TOTAL / POSITIVE / NEGATIVE / NEUTRAL (FROM SUMMARY)
       ========================================================= */
        $summary = $this->db->query("
        SELECT
            COUNT(fs.id) AS total,
            SUM(CASE WHEN fs.sentiment = 'positive' THEN 1 ELSE 0 END) AS positive,
            SUM(CASE WHEN fs.sentiment = 'negative' THEN 1 ELSE 0 END) AS negative,
            SUM(CASE WHEN fs.sentiment = 'neutral'  THEN 1 ELSE 0 END) AS neutral
        FROM feedback_sentiment fs
        JOIN feedback f ON f.id = fs.feedback_id
        WHERE 1=1
        {$dateWhere}
    ")->row();

        if ($summary) {
            $result['total']    = (int) $summary->total;
            $result['positive'] = (int) $summary->positive;
            $result['negative'] = (int) $summary->negative;
            $result['neutral']  = (int) $summary->neutral;
        }

        /* =========================================================
       2. CATEGORY ROOT COUNTS
       ========================================================= */

        if (!$category_id) {
            $qqq = $this->db->query("
                SELECT
                    v.root_name,
                    COUNT(f.id) AS count
                FROM feedback f
                JOIN (WITH RECURSIVE category_root AS (
                        SELECT id AS category_id, id AS root_id, name AS root_name, parent_id
                        FROM category
                        WHERE parent_id IS NULL
                        UNION ALL
                        SELECT c.id, cr.root_id, cr.root_name, c.parent_id
                        FROM category c
                        JOIN category_root cr ON c.parent_id = cr.category_id
                    )
                    SELECT * FROM category_root ) v ON v.category_id = f.category_id
                WHERE 1=1
                {$dateWhere}
                GROUP BY v.root_name
            ");
        } else {
            $qqq = $this->db->query("
                WITH RECURSIVE category_tree AS (
                    -- 1st degree children become GROUP HEADS
                    SELECT
                        id AS category_id,
                        id AS group_id,
                        name AS root_name
                    FROM category
                    WHERE parent_id = {$category_id}

                    UNION ALL

                    -- attach all deeper children to the same group_id
                    SELECT
                        c.id,
                        ct.group_id,
                        ct.root_name
                    FROM category c
                    JOIN category_tree ct ON c.parent_id = ct.category_id
                )
                SELECT
                    ct.root_name,
                    COUNT(f.id) AS count
                FROM feedback f
                JOIN category_tree ct ON ct.category_id = f.category_id
                {$dateWhere}
                GROUP BY ct.group_id, ct.root_name
                ORDER BY count DESC
            ");
        }
        $feedback_category = $qqq->result();
        foreach ($feedback_category as $row) {
            $result['root_names'][] = [
                'name'  => $row->root_name,
                'count' => (int) $row->count
            ];
        }

        /* =========================================================
       3. TOP WORDS (FROM feedback_sentiment_words)
       ========================================================= */
        $topWords = $this->db->query("
        SELECT
            fsw.type        AS sentiment,
            fsw.word        AS name,
            COUNT(*)        AS count
        FROM feedback_sentiment_words fsw
        JOIN feedback f ON f.id = fsw.feedback_id
        WHERE 1=1
        {$dateWhere}
        GROUP BY fsw.type, fsw.word
        ORDER BY count DESC
        LIMIT 10
    ")->result();
        foreach ($topWords as $row) {
            $result['top_words'][] = [
                'sentiment' => $row->sentiment,
                'name'      => $row->name,
                'count'     => (int) $row->count
            ];
        }

        /* -------------------------------
       OUTPUT
       ------------------------------- */
        echo json_encode($result);
    }

    function getComments()
    {
        $requestData = $_REQUEST;

        $searchValue = isset($requestData['search']['value'])
            ? trim($requestData['search']['value'])
            : '';

        $from = date("Y-m-d");
        $to   = date("Y-m-d");

        if (isset($requestData['from'])) $from = $requestData['from'];
        if (isset($requestData['to']))   $to   = $requestData['to'];
        if (isset($requestData['category_id'])) $category_id = $requestData['category_id'];

        $comment_filter = isset($requestData['comment_filter'])
            ? (int)$requestData['comment_filter']
            : 0;

        $dateWhere = "(f.date_created BETWEEN '{$from}' AND '{$to}')";
        if (isset($requestData['category_id']) && $requestData['category_id'] !== '') {
            $category_ids = $this->getCategoryAndChildrenIds($category_id);
            $dateWhere .= " AND f.category_id IN ($category_ids)";
        }

        /* Sentiment filter */
        $filter_where = "";
        if ($comment_filter > 0) {
            $map = [1 => 'positive', 2 => 'negative', 3 => 'neutral'];
            $sentiment = $map[$comment_filter];
            $filter_where = "AND fs.sentiment = '{$sentiment}'";
        }

        /* Pagination */
        list($limit, $offset) = $this->calculatePagination($requestData);

        /* Search */
        $searchSql = "";
        if ($searchValue !== '') {
            $searchSql = "AND f.comment LIKE '%" . $this->db->escape_like_str($searchValue) . "%'";
        }

        /* TOTAL RECORDS */
        $totalQuery = $this->db->query("
        SELECT COUNT(*) AS total
        FROM feedback f
        JOIN feedback_sentiment fs ON fs.feedback_id = f.id
        WHERE f.comment IS NOT NULL
          AND {$dateWhere}
          {$searchSql}
          {$filter_where}
    ")->row()->total;

        /* DATA QUERY */
        $dataQuery = $this->db->query("
        SELECT
            f.id,
            f.date_created,
            f.comment,
            s.name AS sector,
            c.name AS category,
            fs.sentiment
        FROM feedback f
        JOIN feedback_sentiment fs ON fs.feedback_id = f.id
        LEFT JOIN sector s ON s.id = f.sector_id
        LEFT JOIN category c ON c.id = f.category_id
        WHERE f.comment IS NOT NULL
          AND {$dateWhere}
          {$searchSql}
          {$filter_where}
        ORDER BY f.id DESC
        LIMIT {$offset}, {$limit}
    ");


        $data = [];

        foreach ($dataQuery->result() as $row) {

            // Highlight words (no scoring here)
            $analysis = $this->analyzeSentiment($row->comment);

            $highlighted = $row->comment;
            foreach ($analysis['matched'] as $m) {
                $color = $m['type'] === 'positive' ? 'success' : 'danger';
                $highlighted = preg_replace(
                    '/\b(' . preg_quote($m['word'], '/') . ')\b/i',
                    "<span class='badge bg-{$color} p-1'>$1</span>",
                    $highlighted
                );
            }

            $data[] = [
                $row->date_created,
                $row->sector,
                $row->category,
                $highlighted,
                ucfirst($row->sentiment)
            ];
        }

        echo json_encode([
            'draw'            => intval($requestData['draw']),
            'recordsTotal'    => (int)$totalQuery,
            'recordsFiltered' => (int)$totalQuery,
            'data'            => $data
        ]);
    }

    function analyzeSentiment($comment)
    {
        // Lowercase & remove punctuation
        $text = strtolower($comment);
        $text = preg_replace('/[.,!?:;]/', ' ', $text);

        $matched = [];


        /* Sentiment words */
        $sentimentWords = $this->db->query("
            SELECT word, type
            FROM sentiment_words
            WHERE type IN ('positive','negative')
            ORDER BY LENGTH(word) DESC
        ")->result();

        foreach ($sentimentWords as $sw) {
            $word = strtolower($sw->word);

            // Match exact words OR phrases
            $pattern = (strpos($word, ' ') !== false)
                ? '/' . preg_quote($word, '/') . '/i'
                : '/\b' . preg_quote($word, '/') . '\b/i';

            if (preg_match($pattern, $text)) {
                $matched[] = [
                    'word' => $sw->word,
                    'type' => $sw->type
                ];

                // Replace matched word with space to prevent double counting
                $text = preg_replace($pattern, ' ', $text);
            }
        }

        return ['matched' => $matched];
    }



    function getMonthlyTrend()
    {
        $category_id = $this->input->get('category_id');
        $months      = (int) ($this->input->get('months') ?: 6); // default last 6 months

        $categoryWhere = "";
        if ($category_id) {
            $category_ids  = $this->getCategoryAndChildrenIds($category_id);
            $categoryWhere = " AND f.category_id IN ($category_ids)";
        }

        // Build month series for the last N months (current month included)
        $rows = $this->db->query("
        SELECT
            DATE_FORMAT(f.date_created, '%Y-%m') AS month_key,
            DATE_FORMAT(f.date_created, '%b %Y')  AS month_label,
            SUM(CASE WHEN fs.sentiment = 'positive' THEN 1 ELSE 0 END) AS positive,
            SUM(CASE WHEN fs.sentiment = 'negative' THEN 1 ELSE 0 END) AS negative,
            SUM(CASE WHEN fs.sentiment = 'neutral'  THEN 1 ELSE 0 END) AS neutral,
            COUNT(fs.id) AS total
        FROM feedback_sentiment fs
        JOIN feedback f ON f.id = fs.feedback_id
        WHERE f.date_created >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL {$months} MONTH), '%Y-%m-01')
          {$categoryWhere}
        GROUP BY month_key, month_label
        ORDER BY month_key ASC
    ")->result();

        // Also get current-month vs last-month delta for stat cards
        $comparison = $this->db->query("
        SELECT
            DATE_FORMAT(f.date_created, '%Y-%m') AS month_key,
            SUM(CASE WHEN fs.sentiment = 'positive' THEN 1 ELSE 0 END) AS positive,
            SUM(CASE WHEN fs.sentiment = 'negative' THEN 1 ELSE 0 END) AS negative,
            SUM(CASE WHEN fs.sentiment = 'neutral'  THEN 1 ELSE 0 END) AS neutral,
            COUNT(fs.id) AS total
        FROM feedback_sentiment fs
        JOIN feedback f ON f.id = fs.feedback_id
        WHERE f.date_created >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH), '%Y-%m-01')
          {$categoryWhere}
        GROUP BY month_key
        ORDER BY month_key DESC
        LIMIT 2
    ")->result();

        $current_month  = $comparison[0] ?? null;
        $previous_month = $comparison[1] ?? null;

        $delta = [
            'total'    => 0,
            'positive' => 0,
            'negative' => 0,
            'neutral'  => 0,
        ];

        if ($current_month && $previous_month) {
            foreach (['total', 'positive', 'negative', 'neutral'] as $key) {
                $prev = (int) $previous_month->$key;
                $curr = (int) $current_month->$key;
                $delta[$key] = $prev > 0
                    ? round((($curr - $prev) / $prev) * 100, 1)
                    : ($curr > 0 ? 100 : 0);
            }
        }

        echo json_encode([
            'trend'  => $rows,
            'delta'  => $delta,
            'labels' => array_column((array) $rows, 'month_label'),
        ]);
    }
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */