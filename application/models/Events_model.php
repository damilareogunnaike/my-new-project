<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("Crud_model.php");

define("EVENTS_TABLE","events");
define("KEY_RECORD_ID","event_id");

class Events_model extends Crud_model{
    
    private $table = "events";
    public function __construct() {
        parent::__construct();
    }
    
    public function save($event) {
        return $this->_add($this->table,$event,KEY_RECORD_ID);
    }
    
    public function get($event_id) {
        $this->db->select("*");
        $rs = $this->_get_where(EVENTS_TABLE,array("event_id"=>$event_id));
        return ($rs != NULL) ? $rs[0] : NULL;
    }
    
    public function delete($event_id) {
        return $this->_delete(EVENTS_TABLE, array("event_id"=>$event_id));
    }
    
    
    public function get_all(){
        $this->db->select("event_id, title,is_recurring,start_date,end_date,start_time,end_time");
        return $this->_get_all(EVENTS_TABLE);
    }
    
    
    public function get_event_for_day($form_data){
        $this->db->where("start_date >=",$form_data['start_date']);
        $this->db->where("start_date <=",$form_data['end_date']);
        $this->db->or_where("end_date >=",$form_data['start_date']);
        $this->db->where("end_date <=",$form_data['end_date']);
        $rs = $this->db->get(EVENTS_TABLE);
        return $rs->num_rows() > 0 ? $rs->result_array() : array();
    }
}

