<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Subject_model extends CI_Model{
    
    function get_subject_students($subject_id,$class_id){
        $sql = "SELECT a.id as student_id, a.* FROM student_biodata a, subject_teacher b WHERE b.subject_id = {$subject_id}"
        . " AND b.class_id = {$class_id} AND a.class_id = {$class_id}";
        $rs = $this->db->query($sql);
        return $rs->num_rows() > 0 ? $rs->result_array() : NULL;
    }
}

