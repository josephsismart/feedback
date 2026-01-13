<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    public $global_requestid = null;
    public $global_requestid_personnel = null;

    public function system()
    {
        $data = [
            "system_title"  => "SMCC Feedback Form",
            "system_logo"   => base_url("dist/img/SMCCnewlogo.png"),
            "system_svg"    => base_url("dist/img/SMCCnewlogo_5x6.png"),
            "system_op"    => base_url("dist/img/icons/icon_op.png"),
            "system_ip"    => $this->get_ip(),
            "system_mac"    => $this->getServerMacAddress(),

        ];
        return $data;
    }

    public function ask($question)
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents("php://input"), true);
        $question = trim($question ?? '');

        if (!$question) {
            echo json_encode(['answer' => 'No question provided']);
            return;
        }

        // Call Ollama
        $payload = [
            "model" => "llama3",
            "prompt" => "You are a helpful assistant. Answer this question:\n$question",
            "stream" => false
        ];

        $ch = curl_init("http://localhost:11434/api/generate");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 20
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $answer = isset($data['response']) ? trim($data['response']) : 'No response from AI';

        // Optional: save chat in DB
        // $this->db->insert('feedback_chat', [
        //     'question' => $question,
        //     'answer' => $answer,
        //     'created_at' => date('Y-m-d H:i:s')
        // ]);

        echo json_encode(['answer' => $answer]);
    }

    public function analyzeCommentWithAI($comment)
    {
        // example API payload

        $prompt = <<<PROMPT
            You are a sentiment analysis engine.

            Task:
            - Analyze the given comment
            - Extract ONLY meaningful sentiment words or phrases
            - Classify each as:
            - 1 = positive
            - 0 = negative

            Rules:
            - Learn the tagalog language
            - Learn the bisaya language
            - Look the example output
            - Return JSON array only
            - Do not include explanations or extra text
            - Do not translate, rephrase, shorten, spell-correct, invent, or summarize words
            - Do not hallucinate or add words that are not in the comment
            - Do NOT translate or summarize
            - Keep original words exactly
            - Learn Bisaya, Tagalog, English, and mixed comments
            - Modifiers in Bisaya: 
                - Positive: Paspas, Nindot, Maayo kaayo, Buotan, Helpful
                - Negative: Hinay, Libog, Dili klaro, Rude, Delayed
            - Look at the example output for polarity reference
            - Adjectives appearing AFTER the target (e.g. "staff is rude", "line is long") are VALID
            - Adjectives that appear AFTER the target (e.g. "staff is rude", "line is long") are VALID and must be extracted as-is
            - Treat "long line", "long queue", "long waiting time" as NEGATIVE experience
            - Split sentences by ".", "and", "ug", "pero" and evaluate each clause independently
            - Always extract **modifier + target together**
            - Do NOT return modifiers alone
            - Only return phrases where **both sentiment modifier and target** are included 
            - Do NOT return modifiers alone
            - Only return phrases where **both sentiment modifier and target** are included 
            - Do NOT return modifiers alone
            - Only return phrases where **both sentiment modifier and target** are included 
            - Do NOT return modifiers alone
            - Only return phrases where **both sentiment modifier and target** are included 
            - Do NOT return modifiers alone
            - Only return phrases where **both sentiment modifier and target** are included 

            Example output:
            [
            {"word":"Paspas ang serbisyo","type":1},                            // Bisaya positive
            {"word":"Medyo libog ang proseso","type":0},                        // Bisaya negative
            {"word":"Nindot ang pamaagi sa pagtabang sa estudyante","type":1},  // Bisaya positive
            {"word":"Dili klaro ang instructions","type":0},                     // Bisaya/Eng mix negative
            {"word":"Malinis ang paligid","type":1},                             // Tagalog positive
            {"word":"Hinay ang proseso sa enrollment","type":0},                 // Bisaya negative
            {"word":"Buotan ug approachable ang staff","type":1},                // Bisaya positive
            {"word":"MAAYO KAAYO ANG SERBISYO SA ESKWELAHAN","type":1},          // Bisaya positive
            {"word":"Dili kaayo friendly ang uban staff","type":0},              // Bisaya negative
            {"word":"Grabe ka tagal ng pila","type":0},                           // Tagalog negative
            {"word":"Super fast ang registration","type":1},                      // Tagalog positive
            {"word":"Long waiting time sa cashier","type":0},                      // EngBis negative
            {"word":"Nice at maayos ang instructions","type":1},                  // Taglish positive
            {"word":"Confusing ang forms","type":0},                               // Taglish negative
            {"word":"Sobrang bagal ng internet","type":0},                         // Tagalog negative
            {"word":"Excellent customer service","type":1},                        // English positive
            {"word":"RUDE staff","type":0},                                       // English negative
            {"word":"Quick ug smooth ang proseso","type":1},                       // EngBis positive
            {"word":"Dili klaro ang system instructions","type":0}                 // Bisaya/Eng mix negative
            {"word":"Paspas ang serbisyo","type":1},
            {"word":"Hinay ang proseso sa enrollment","type":0},
            {"word":"Buotan ug approachable ang staff","type":1},
            {"word":"Dili kaayo friendly ang uban staff","type":0},
            {"word":"Medyo libog ang proseso","type":0},
            {"word":"Nindot ang pamaagi sa pagtabang sa estudyante","type":1},
            {"word":"Dili klaro ang instructions","type":0},
            {"word":"Maayo kaayo ang serbisyo sa eskwelahan","type":1},
            {"word":"Malinis ang paligid","type":1},
            {"word":"Hugaw ang comfort room","type":0},
            {"word":"Grabe ka tagal ng pila","type":0},
            {"word":"Mabilis ang proseso ng registration","type":1},
            {"word":"Sobrang bagal ng internet","type":0},
            {"word":"Maayos ang sistema ng serbisyo","type":1},
            {"word":"Hindi malinaw ang instructions","type":0},
            {"word":"Magalang ang staff","type":1},
            {"word":"Helpful staff","type":1},
            {"word":"Rude staff","type":0},
            {"word":"Slow internet","type":0},
            {"word":"Excellent customer service","type":1},
            {"word":"Long waiting time","type":0},
            {"word":"Nice at maayos ang instructions","type":1},
            {"word":"Confusing ang forms","type":0},
            {"word":"Super fast ang registration","type":1},
            {"word":"Ang bagal ng system response","type":0},
            {"word":"Quick ug smooth ang proseso","type":1},
            {"word":"Hinay kaayo ang system response","type":0},
            {"word":"Friendly kaayo ang staff","type":1},
            {"word":"Dili klaro ang system instructions","type":0},
            {"word":"the cashier staff is rude","type":0},
            {"word":"long line in cashier","type":0},
            {"word":"ang staff kay bastos","type":0},
            {"word":"hinay ang proseso","type":0},

            {"word":"rude cashier staff","type":0},
            {"word":"long line in cashier","type":0},
            {"word":"the cashier staff is rude","type":0},
            {"word":"long line in cashier","type":0},
            {"word":"Clean ang facility","type":1},
            {"word":"Messy ang paligid","type":0},
            {"word":"Organized ang proseso sa serbisyo","type":1},
            {"word":"Delayed ang service delivery","type":0},
            {"word":"Fast internet connection","type":1},
            {"word":"Slow internet speed","type":0},
            {"word":"Helpful staff","type":1},
            {"word":"Rude staff","type":0},
            {"word":"Friendly front desk staff","type":1},
            {"word":"Unapproachable staff","type":0},
            {"word":"Efficient enrollment process","type":1},
            {"word":"Confusing enrollment process","type":0},
            {"word":"Delayed enrollment process","type":0},
            {"word":"Smooth registration process","type":1},
            {"word":"Clear instructions","type":1},
            {"word":"Unclear instructions","type":0},
            {"word":"Well explained instructions","type":1},
            {"word":"Clean school environment","type":1},
            {"word":"Dirty surroundings","type":0},
            {"word":"Well maintained facilities","type":1},
            {"word":"Poorly maintained facilities","type":0},
            {"word":"Stable system performance","type":1},
            {"word":"System keeps crashing","type":0},
            {"word":"Slow system response","type":0},
            {"word":"Reliable online system","type":1},
            {"word":"Short waiting time","type":1},
            {"word":"Long waiting time","type":0},
            {"word":"Organized queue system","type":1},
            {"word":"Disorganized queue system","type":0},
            {"word":"Excellent customer service","type":1},
            {"word":"Poor customer service","type":0},
            {"word":"Professional service experience","type":1},
            {"word":"Frustrating service experience","type":0}
            ]

            Comment:
            "$comment"
            PROMPT;
        header('Content-Type: application/json');
        $payload = [
            "model" => "llama3",
            "prompt" => $prompt,
            "response" => '[{"word":"slow internet","type":0}]',
            "stream" => false,
            "done" => true
        ];

        $ch = curl_init("http://localhost:11434/api/generate");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 20
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) return;

        // STEP 1: Decode Ollama wrapper
        $ollama = json_decode($response, true);

        if (!isset($ollama['response'])) {
            log_message('error', 'Ollama response missing "response" field');
            return;
        }

        // STEP 2: Extract AI text
        $aiText = trim($ollama['response']);

        // Safety: extract JSON array only
        $start = strpos($aiText, '[');
        if ($start !== false) {
            $aiText = substr($aiText, $start);
        }

        // STEP 3: Decode AI JSON
        $results = json_decode($aiText, true);

        if (!is_array($results)) {
            log_message('error', 'AI output not valid JSON: ' . $aiText);
            return;
        }

        // STEP 4: Save results
        $original_comment = strtoupper($comment);
        foreach ($results as $row) {

            $word = trim($row['word']);

            // Reject single words
            if (str_word_count($word) < 2) continue;

            // Reject generic junk
            // $ban = ['SYSTEM', 'SERVICE', 'UNFORTUNATELY', 'LONG','INTERNET', 'PROCESS', 'STAFF', 'QUEUE', 'PILA','UG', 'AND', 'AT', 'PERO'];
            // if (in_array(strtoupper($word), $ban)) continue;
            // if (!str_contains($original_comment, $word)) continue;

            $this->saveSentimentWordAI($word, $row['type']);
        }
    }

    public function public_create_page($data = [])
    {
        // $level = $this->session->feedback_login_level;
        // $defaultPassword = $this->session->feedback_change_password;
        $uri = $this->session->feedback_login_uri;
        // if ($level != "") {
        //     if ($defaultPassword == 't') {
        //         return $this->load->view('interface/userpassword/layout/Page', $data, false);
        //     } else {
        //         return $this->load->view('interface/' . $uri . '/layout/Page', $data, false);
        //     }
        // }
        return $this->load->view('interface/' . $uri . '/layout/Page', $data, false);
    }

    public function user_create_page($data = [])
    {
        return $this->load->view('interface/user/layout/Page', $data, false);
    }

    public function redirect()
    {
        $login = $this->session->feedback_login_id;
        $defaultPassword = $this->session->feedback_change_password;
        $uri = $this->session->feedback_login_uri;
        $landing = $this->session->feedback_login_landing;
        if (!$login) {
            redirect(base_url('/'));
        }
        if (isset($login) && $this->uri->segment(1) != $uri) {
            if ($defaultPassword == 1) {
                redirect(base_url('userpassword/changepassword'));
            } else {
                redirect(base_url($uri . '/' . $landing));
            }
        }
    }

    public function redirect2()
    {
        $login = $this->session->feedback_login_id;
        if (!$login) {
            redirect(base_url('/'));
        }
    }

    public function redirect_home()
    {
        $level = $this->session->feedback_login_id;
        $defaultPassword = 0; #$this->session->feedback_change_password;
        $uri = $this->session->feedback_login_uri;
        $landing = $this->session->feedback_login_landing;
        // if (isset($this->session->feedback_login_id) && $this->uri->segment(1) == "" || $this->uri->segment(1) == "login" || $this->uri->segment(1) == "map") {
        if (isset($this->session->feedback_login_id) && $this->uri->segment(1) == "" || $this->uri->segment(1) == "login" || $this->uri->segment(1) == "map") {
            if ($level != "") {
                if ($defaultPassword == 1) {
                    redirect(base_url('userpassword/changepassword'));
                } else {
                    redirect(base_url($uri . '/' . $landing));
                }
            }
        }
    }

    public function redirect_session()
    {
        $login = $this->session->feedback_login_id;
        if (!$login) {
            redirect(base_url('/'));
        }
    }

    function getClientIP()
    {
        $ipaddress = '';

        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = "UNKNOWN";

        return $ipaddress;
    }


    public function getServerMacAddress()
    {
        // Windows
        @exec("getmac", $output);
        if (!empty($output)) {
            $mac = explode(' ', $output[0]);
            return trim($mac[0]);
        }

        // Linux / Ubuntu / CentOS
        @exec("cat /sys/class/net/eth0/address", $mac);
        if (!empty($mac)) {
            return trim($mac[0]);
        }

        return "UNKNOWN";
    }


    public function get_ip()
    {
        $ip = "";
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED"];
        } elseif (!empty($_SERVER["REMOTE_ADDR"])) {
            $ip = $_SERVER["REMOTE_ADDR"];
        }
        if ($ip == "::1") {
            $ip = "127.0.0.1";
        }
        return $ip;
    }

    public function confirmPassword($a)
    {
        $pwd = md5($a);
        $login_id = $this->session->feedback_login_id;
        $query = $this->db->query("SELECT 1 AS pwd FROM tbl_user WHERE id=$login_id AND password='$pwd' LIMIT 1");
        return $query->row("pwd");
    }

    public function now()
    {
        date_default_timezone_set("Asia/Manila");
        $now = date("Y-m-d H:i:s");
        return $now;
    }

    public function do_upload($input_name, $upload_path, $file_name)
    {
        $path = "";
        // $num = mt_rand(1, 1000000);

        $config['upload_path']      = $upload_path;
        $config['allowed_types']    = 'pdf|docx|xls|ppt|jpg|png|jpeg|txt';
        $config['max_size']         = '100000';
        $config['overwrite']        = true;
        $config['file_name']        = $file_name;
        // $config['max_width']         = '5000';
        // $config['max_height']        = '5000';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        $upload = $this->upload->do_upload($input_name);
        if ($upload) {
            $path = $file_name;
        }
        return $path;
    }

    public function userlog($action)
    {
        $login_id = $this->session->feedback_login_id;
        $login_alias = $this->session->feedback_login_uname;
        $now = $this->now();
        $action = addslashes($action);
        $ip = $this->get_ip();
        $data = [
            "date" => $now,
            "action" => $action,
            "user_id" => $login_id,
            "user_name" => $login_alias,
            "ip" => $ip,
        ];
        if ($login_id) {
            $this->db->insert("global.tbl_userlogs", $data);
        }
    }

    public function calculatePagination($requestData)
    {
        $limit = isset($requestData['length']) ? intval($requestData['length']) : 10;
        $offset = isset($requestData['start']) ? intval($requestData['start']) : 0;
        return array($limit, $offset);
    }


    public function uploadImg($pic, $picname, $path_, $dupload)
    {
        $newImageName = null;
        $isUploaded = false;

        if (isset($pic) && !$isUploaded) {
            $config['upload_path'] = "dist/img/$path_/";

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload($dupload)) {
                $myPic = null;
            } else {
                $isUploaded = true;
                $myPic = $this->upload->data();

                // Determine the file extension
                $extension = pathinfo($myPic['file_name'], PATHINFO_EXTENSION);

                // Final new image name
                $cleanName = preg_replace('/[^a-z0-9_-]/', '', strtolower($picname));
                $newImageName = $cleanName . "_" . time() . "." . $extension;
                $newImagePath = $config['upload_path'] . $newImageName;

                // Config for image_lib (to resize/copy)
                $config['image_library'] = 'gd2';
                $config['source_image'] = $myPic['full_path'];   // original uploaded file
                $config['new_image'] = $newImagePath;

                $this->load->library('image_lib', $config);
                $this->image_lib->resize();

                // 🔑 Remove the original uploaded file (with random/original name)
                if (file_exists($myPic['full_path']) && $myPic['full_path'] !== $newImagePath) {
                    unlink($myPic['full_path']);
                }

                return $newImagePath;
            }
        }
    }

    public function getImg($a)
    {
        // Check if the provided path is a URL
        if (filter_var($a, FILTER_VALIDATE_URL)) {
            $pathExists = get_headers($a);
            if ($pathExists && strpos($pathExists[0], '200')) {
                return $a;
            }
        } else {
            // Check if the provided path is a local file
            $localPath = realpath($a);
            if ($localPath && is_file($localPath)) {
                // Check if the file is an image
                $imageInfo = getimagesize($localPath);
                if ($imageInfo !== false) {
                    return base_url() . $a;
                }
            }
        }

        // Return the default image URL
        return base_url('dist/img/media/icons/1x1.png');
    }

    public function dateFormat($a)
    {
        $b = "-";
        if ($a != null) {
            $c = date_create($a);
            $b = date_format($c, "M d, Y");
        }
        return strtoUpper($b);
    }



    public function evaluate_feedback_sentiment($feedback_id, $comment)
    {
        if (!$feedback_id || !$comment) {
            return false;
        }

        /* -------------------------------
           1. Normalize comment
        ------------------------------- */
        $v_text = strtolower($comment);
        // $punctuations = ['.', ',', '!', '?', ';', ':'];
        // $v_text = str_replace($punctuations, ' ', $v_text);

        /* -------------------------------
           2. Delete previous analysis
        ------------------------------- */
        $this->db->where('feedback_id', $feedback_id)->delete('feedback_sentiment_words');
        $this->db->where('feedback_id', $feedback_id)->delete('feedback_sentiment');

        /* -------------------------------
           3. Fetch sentiment words (longest first)
        ------------------------------- */
        $sentiment_words = $this->db
            ->select('id, word, type, strength')
            ->from('sentiment_words')
            ->where_in('type', ['positive', 'negative'])
            ->order_by('LENGTH(word)', 'DESC')
            ->get()
            ->result_array();

        /* -------------------------------
           4. Match & insert words
        ------------------------------- */
        foreach ($sentiment_words as $sw) {

            $word = strtolower($sw['word']);

            if (strpos($v_text, $word) !== false) {

                $this->db->insert('feedback_sentiment_words', [
                    'feedback_id'        => $feedback_id,
                    'sentiment_word_id'  => $sw['id'],
                    'word'               => $sw['word'],
                    'type'               => $sw['type'],
                    'strength'           => $sw['strength']
                ]);

                // Remove matched word (same as REPLACE in MySQL)
                $v_text = str_replace($word, ' ', $v_text);
            }
        }

        /* -------------------------------
           5. Count POS / NEG
        ------------------------------- */
        $counts = $this->db
            ->select("
                SUM(CASE WHEN type = 'positive' THEN 1 ELSE 0 END) AS positive,
                SUM(CASE WHEN type = 'negative' THEN 1 ELSE 0 END) AS negative
            ", false)
            ->from('feedback_sentiment_words')
            ->where('feedback_id', $feedback_id)
            ->get()
            ->row();

        $v_positive = (int) $counts->positive;
        $v_negative = (int) $counts->negative;

        /* -------------------------------
           6. Determine final sentiment
        ------------------------------- */
        if ($v_positive > $v_negative) {
            $v_sentiment = 'positive';
        } elseif ($v_negative > $v_positive) {
            $v_sentiment = 'negative';
        } else {
            $v_sentiment = 'neutral';
        }

        /* -------------------------------
           7. Insert summary
        ------------------------------- */
        $this->db->insert('feedback_sentiment', [
            'feedback_id'    => $feedback_id,
            'positive_count' => $v_positive,
            'negative_count' => $v_negative,
            'sentiment'      => $v_sentiment,
            'analyzed_at'    => date('Y-m-d H:i:s')
        ]);

        return true;
    }

    public function evaluate_all_feedback_sentiment()
    {
        /* -------------------------------
           1. Get all feedback with comments
        ------------------------------- */
        $feedbacks = $this->db
            ->select('id, comment')
            ->from('feedback')
            ->where('comment IS NOT NULL', null, false)
            ->get()
            ->result_array();

        /* -------------------------------
           2. Loop & re-evaluate
        ------------------------------- */
        foreach ($feedbacks as $fb) {


            $this->evaluate_feedback_sentiment(
                $fb['id'],
                $fb['comment']
            );
        }

        return count($feedbacks);
    }



    public function saveSentimentWordAI($word, $type_int)
    {
        $this->db->trans_begin();
        $data = [];
        $word = strtoupper($word);
        $type_int = $type_int;


        $exist = $this->db->where("word", $word)->get("sentiment_words")->num_rows();

        $data = [
            "word" => $word,
            "type" => $type_int == 1 ? "positive" : "negative",
            "type_int" => $type_int,
            "created_by" => 'ai',
        ];

        if ($exist == 0) {
            $this->db->insert("sentiment_words", $data);
        }


        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */