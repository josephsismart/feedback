<?php
class mainModel extends CI_Model {

        public $title;
        public $content;
        public $date;

        public function insert($table, $arrayName){
                $this->db->insert($table, $arrayName);
        }

        public function update($table, $arrayName, $table_id, $id_val){
                $this->db->where($table_id, $id_val);
                $this->db->update($table, $arrayName);
        }

        public function delete3($table, $table_id, $id_val, $table_id2, $id_val2, $table_id3, $id_val3){
                $this->db->where($table_id, $id_val);
                $this->db->where($table_id2, $id_val2);
                $this->db->where($table_id3, $id_val3);
                $this->db->delete($table);      
        }

        public function delete($table, $table_id, $id_val){
                $this->db->where($table_id, $id_val);
                $this->db->delete($table);
        }

        public function get_max($table, $table_id){
                $this->db->select_max($table_id);
                $query = $this->db->get($table);  
                return $query;    
        }
        
        public function get_maxs($table, $table_id){
                $this->db->select('schoolyear_end, max(schoolyear_id) as `schoolyear_id`');
                $query = $this->db->get($table);  
                return $query;    
        }

        public function sum($table, $table_id, $table_id2, $id_val2){
                $query = $this->db->select_sum($table_id, $table_id);
                $this->db->where($table_id2, $id_val2);
                $query = $this->db->get($table);  
                return $query;    
        }

        public function get_maxss($table){
                $this->db->select('*, max(records_month_num) as `max`');
                $query = $this->db->get($table);  
                return $query;    
        }

        public function get_table($table){
                $query =  $this->db->get($table);
                return $query;
        }
        
        public function sel_table($table, $table_id, $id_val){
                $this->db->where($table_id, $id_val);
                $query =  $this->db->get($table);
                return $query;
        }

        public function sel_tableBetween($table, $table_id, $id_val, $id_val2){
                $this->db->where("$table_id BETWEEN $id_val AND $id_val2");
                $query =  $this->db->get($table);
                return $query;
        }

        public function sel_tableor($table, $table_id, $id_val, $table_id2){
                $this->db->order_by($table_id2, "asc");
                $this->db->where($table_id, $id_val);
                $query =  $this->db->get($table);
                return $query;
        }

        public function get_tableor($table, $table_id2,$orderby){
                $this->db->order_by($table_id2, $orderby);
                $query =  $this->db->get($table);
                return $query;
        }

        public function sel2_table($table, $table_id, $id_val, $table_id2, $id_val2){
                $this->db->where($table_id, $id_val);
                $this->db->where($table_id2, $id_val2);
                $query =  $this->db->get($table);
                return $query;
        }

        public function sel3_table($table, $table_id, $id_val, $table_id2, $id_val2, $table_id3, $id_val3){
                $this->db->where($table_id, $id_val);
                $this->db->where($table_id2, $id_val2);
                $this->db->where($table_id3, $id_val3);
                $query =  $this->db->get($table);
                return $query;
        }

        public function sel4_table($table, $table_id, $id_val, $table_id2, $id_val2, $table_id3, $id_val3, $table_id4, $id_val4){
                $this->db->where($table_id, $id_val);
                $this->db->where($table_id2, $id_val2);
                $this->db->where($table_id3, $id_val3);
                $this->db->where($table_id4, $id_val4);
                $query =  $this->db->get($table);
                return $query;
        }

        public function cust($table){
                $this->db->select("max(schoolyear_id) as `max`");
                $this->db->where("schoolyear_status", '1');
                $query =  $this->db->get($table);
                return $query;
        }

}