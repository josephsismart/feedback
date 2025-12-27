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
        $defaultPassword = 0;#$this->session->feedback_change_password;
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
            $config['upload_path'] = "dist/img/media/$path_/";

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
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */