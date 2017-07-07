<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class School_MS_Model extends CI_Model{
    function __construct() {
        parent::__construct();
    }
    
    
     
    function record_exists($table,$record)
    {
        $rs = $this->db->get_where($table,$record);
        if($rs->num_rows() > 0) { return TRUE; }
        else { return FALSE; } 
    }
}

