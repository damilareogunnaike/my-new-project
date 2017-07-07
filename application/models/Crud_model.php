<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_model extends CI_Model {
	
	function __construct(){
        parent::__construct();
    }


    //Used for fetching a query where one row is expected
    function _get($table){
        $rs = $this->db->get($table);
        if($rs->num_rows() > 0 ) {
            $data = $rs->result_array();
            return $data[0];
        }
        else {
            return NULL;
        }
    }

    function _get_all($table){
        $rs = $this->db->get($table);
        return $rs->num_rows() > 0 ? $rs->result_array() : NULL;
    }


    function _get_where($table,$clause){
        $rs = $this->db->get_where($table,$clause);
        return $rs->num_rows() > 0 ? $rs->result_array() : NULL;
    }


    function _add($table,$form_data,$record_id_tag){
        if(isset($form_data[$record_id_tag])){
            $this->db->where($record_id_tag,$form_data[$record_id_tag]);
            $this->db->update($table,$form_data);
            $insert_id = $form_data[$record_id_tag];
        }
        else {
            $this->db->insert($table,$form_data);
            $insert_id = $this->db->insert_id();
        }
        if($insert_id > 0){
            $rs = $this->db->get_where($table,array($record_id_tag=>$insert_id));
            return $rs->row_array();
        }
        else {
            return null;
        }	
    }

    /**
     * 
     * @param type $table Table from where record is to be deleted
     * @param type $where Array of key=>value pairs used to form where clause
     * @return type TRUE if record is deleted or FALSE otherwise
     */
    function _delete($table,$where){
        $this->db->where($where);
        $this->db->delete($table);
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }
    
    
    function _record_exists($table,$record){
        $rs = $this->db->get_where($table,$record);
        return ($rs->num_rows() > 0) ? TRUE : FALSE;
    }
    
    
    function _get_size($table_name = null){
        $class_name = get_class($this);
        $table_name = ($table_name == null) ? str_replace("_model","",$class_name) : $table_name;
        $count = $this->db->count_all($table_name);
        return $count;
    }
    
    
    function _query($sql){
        $rs = $this->db->query($sql);
        if($rs->num_rows() > 0){
            $data = $rs->result_array();
            return $data;
        }
        else {
            NULL;
        }
    }


    function _update($table_name, $where_clause, $new_data){
        $this->db->where($where_clause);
        $this->db->update($table_name, $new_data);
        if($this->db->affected_rows() > 0){
            return TRUE;
        }
        else {
            return FALSE;
        }
    }


    function _get_if_exists($table, $where_clause){
        $rs = $this->db->get_where($table, $where_clause);
        if($rs->num_rows() > 0) {
            return $rs->row_array();
        }
        else return null;
    }
    

	
}
