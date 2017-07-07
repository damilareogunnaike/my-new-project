<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("Crud_model.php");


class Subjects_model extends Crud_model{
    
    private $CI;

    public function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
    }


    function get_all(){
        $this->db->order_by("subject_name","ASC");
        $rs = $this->db->get("subjects");
        return $rs->num_rows() > 0 ? $rs->result_array() : array();
    }

}

