<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("Crud_model.php");


class SubjectSettings_model extends Crud_model{
    
    private $CI;

    public function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
    }

    public function get_subject_selection_mode($class_id){
        $rs = $this->_get_where("classes", array("class_id"=>$class_id));
        $class = $rs[0];
        return $class['subject_selection_mode'];
    }


    public function update_selection_mode($req_obj){
       return $this->_update("classes",array("class_id"=>$req_obj['class_id']),$req_obj);
    }


    public function save_class_subjects($req_obj, $curr_session_id){
    	$class_ids = $req_obj['class_ids'];
    	$subject_ids = $req_obj['subject_ids'];

    	$record = array("school_session_id"=>$curr_session_id);
    	$insert_array = array();
    	$update_array = array();
    	foreach($class_ids as $class_id) {
    		$record['class_id'] = $class_id;
    		foreach($subject_ids as $subject_id) {
    			$record['subject_id'] = $subject_id;
    		

	    		if(!($this->_record_exists("compulsory_class_subjects", $record))){
	    			$insert_array[] =  $record;
	    		}
	    		else {
	    			$update_array[] = $record;
	    		}
	    	}
    	}
    	if(sizeof($insert_array) > 0){
    		$this->db->insert_batch("compulsory_class_subjects",$insert_array);
    		if($this->db->affected_rows() > 0){
    			return true;
    		}
    	}

    	return sizeof($update_array) > 0 ? TRUE : FALSE;
    }


    /**
    @param @aggregate If set to false, it returns the compulsory subjects for this class, as well
    as all the optional subjects offered by the class students..
    */
    public function get_class_subjects($class_id, $curr_session_id, $aggregate = FALSE){

        if($class_id == NULL || $class_id == "") {
            return array();
        }

        $subject_selection_mode = $this->get_subject_selection_mode($class_id);
        switch ($subject_selection_mode) {
            case 1:
                $sql = "SELECT DISTINCT a.subject_id, b.* FROM subject_teacher a, subjects b WHERE a.class_id = {$class_id} AND a.subject_id = b.subject_id ORDER BY subject_name ASC";
                break;
            case 2:
                $sql = "SELECT * FROM compulsory_class_subjects a, subjects b WHERE a.class_id = {$class_id} ".
                 " AND a.school_session_id = {$curr_session_id} AND a.subject_id = b.subject_id ORDER BY subject_name ASC";
                 break;
            default:
                break;
        }

    	$subjects = $this->_query($sql);

        if($aggregate){
            return $this->get_class_aggregate_subjects($class_id, $curr_session_id, $subjects);
        }
    	return $subjects != null ? $subjects : array();
    }


    public function get_class_aggregate_subjects($class_id, $curr_session_id, $existing_subjects){

        if(is_array($existing_subjects) && sizeof($existing_subjects) > 0 ) {
            $existing_subject_ids = "";
            foreach($existing_subjects as $subject){
                $existing_subject_ids .= $subject['subject_id'] . ",";
            }
            $existing_subject_ids = substr($existing_subject_ids, 0, -1);
        }

        $sql = "SELECT c.* FROM students_class a, optional_student_subjects b, subjects c WHERE a.class_id = {$class_id} "
          . " AND a.school_session_id = {$curr_session_id} AND b.school_session_id = {$curr_session_id} "
          . "AND a.student_id = b.student_id AND b.subject_id = c.subject_id ";
        $sql .= (strlen($existing_subject_ids) > 0) ? " AND c.subject_id NOT IN ({$existing_subject_ids}) " : "";
        $sql .= "GROUP BY c.subject_id ";

        $optional_subjects = $this->_query($sql);
        if(is_array($optional_subjects) && sizeof($optional_subjects) > 0){
            $subjects = array_merge($existing_subjects, $optional_subjects);
            sort_multi_array($subjects, "subjects");
            return $subjects;
        }

        return $existing_subjects != null ? $existing_subjects : array();
    }


    public function remove_class_subjects($record_id, $curr_session_id){
    	
    	$this->db->where("record_id", $record_id);
    	$this->db->delete("compulsory_class_subjects");
    	return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }


    public function is_compulsory_subject_for_class($subject_id, $class_id, $session_id){
        $this->db->where("school_session_id", $session_id);
        $this->db->where("class_id", $class_id);
        $this->db->where("subject_id", $subject_id);
        $rs = $this->db->get_where("compulsory_class_subjects");
        return $rs->num_rows() > 0 ? TRUE : FALSE;
    }


    public function save_student_subjects($req_obj, $curr_session_id){
        $student_ids = $req_obj['student_ids'];
        $subject_ids = $req_obj['subject_ids'];

        $record = array("school_session_id"=>$curr_session_id);
        $insert_array = array();
        $update_array = array();
        foreach($student_ids as $student_id) {
            $record['student_id'] = $student_id;
            foreach($subject_ids as $subject_id) {
                $record['subject_id'] = $subject_id;
            

                if(!($this->_record_exists("optional_student_subjects", $record))){
                    $insert_array[] =  $record;
                }
                else {
                    $update_array[] = $record;
                }
            }
        }
        if(sizeof($insert_array) > 0){
            $this->db->insert_batch("optional_student_subjects",$insert_array);
            if($this->db->affected_rows() > 0){
                return true;
            }
        }

        return sizeof($update_array) > 0 ? TRUE : FALSE;
    }


    public function get_student_subjects($student_id, $curr_session_id, $class_id){
        $subject_selection_mode = $this->get_subject_selection_mode($class_id, $curr_session_id);

        $subjects = array();

        $class_subjects = $this->get_class_subjects($class_id, $curr_session_id);
        if(is_array($class_subjects) && sizeof($class_subjects) > 0) {
            $subjects = $class_subjects;
        }

        $optional_subjects = $this->get_student_optional_subjects($student_id, $curr_session_id);

        if(is_array($optional_subjects) && sizeof($optional_subjects) > 0) {
            $subjects = merge_array_remove_duplicate($class_subjects, $optional_subjects, "subject_id");
        }

        return sort_multi_array($subjects, "subjects");
    }


    public function get_student_optional_subjects($student_id, $curr_session_id){
         $sql = "SELECT * FROM optional_student_subjects a, subjects b WHERE a.student_id = {$student_id} ".
                   " AND a.school_session_id = {$curr_session_id} AND a.subject_id = b.subject_id ORDER BY subject_name ASC";
        $subjects = $this->_query($sql);
        return $subjects != null ? $subjects : array();
    }


    public function remove_student_subjects($record_id, $curr_session_id){
        
        $this->db->where("record_id", $record_id);
        $this->db->delete("optional_student_subjects");
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }


}

