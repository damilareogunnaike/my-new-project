<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("Crud_model.php");


class Classes_model extends Crud_model{
    
    private $CI;

    public function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
    }


    function get_all(){
        $this->db->order_by("class_name","ASC");
        $rs = $this->db->get("classes");
        return $rs->num_rows() > 0 ? $rs->result_array() : array();
    }

	function get_class_name($class_id)
    {
        $rs = $this->db->get_where('classes',array('class_id'=>$class_id));
        if($rs->num_rows() > 0)
        {
            $data = $rs->row_array();
            return $data['class_name'];
        }
        return FALSE;
    }
	
	
    function get_class_students($class_id){
        $sql = "SELECT a.id as student_id, a.* FROM student_biodata a WHERE a.class_id =  {$class_id} AND a.deleted = 0";
        $rs = $this->db->query($sql);
        return $rs->num_rows() > 0 ? $rs->result_array() : NULL;
    }


    function get_class_size($class_id, $curr_session_id = null){
        $curr_session_id = $curr_session_id == null ? $this->CI->myapp->get_current_session_id() : $curr_session_id;
        $sql = "SELECT COUNT(*) AS count FROM students_class a, student_biodata b WHERE a.class_id = {$class_id} 
        AND a.school_session_id = {$curr_session_id} AND a.student_id = b.id";
        $rs = $this->db->query($sql);
        $row = $rs->row_array();
        return $row['count'];
    }


    function get_class_subjects($class_ids){
        $class_ids = $class_ids["class_ids"];
        if(sizeof($class_ids) > 0){
            $str_class_ids = implode($class_ids,",");
            $sql = "SELECT DISTINCT b.subject_id,b.subject_name FROM subject_teacher a, subjects b 
            WHERE a.class_id IN ({$str_class_ids}) AND a.subject_id = b.subject_id ORDER BY subject_name ASC";
            return $this->_query($sql);
        }
        else {
            return array();
        }
       
    }


    function update($class_obj){
        $this->db->where("class_id", $class_obj['class_id']);
        $this->db->update("classes", $class_obj);
        if($this->db->affected_rows() > 0){
            $response = array("updated"=>true, "class"=>$class_obj);
            return $response;
        }
        else return array("updated"=>FALSE);
    }
}

