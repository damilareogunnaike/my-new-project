<?php
include_once 'school_ms_model.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Health_model extends School_MS_Model{
    
    public function __construct() {
        parent::__construct();
    }
    
   
    function save_health_records($data){
        $this->db->insert("health_records",$data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
    
    
    function view_health_records($student_id,$session_id,$term_id){
        $rs = $this->db->get_where("health_records",array('student_id'=>$student_id,
            'session_id'=>$session_id,'term_id'=>$term_id));
        return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
    }
    
    
    function delete_health_record($record_id){
        $this->db->delete("health_records",array("health_record_id"=>$record_id));
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }
}
