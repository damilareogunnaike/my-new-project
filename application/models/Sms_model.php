<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("Crud_model.php");

define("CONTACT_REC_ID","contact_id");
define("GROUP_REC_ID","group_id");

class Sms_model extends Crud_model{
    
    private $sms_table      = "sms_messages";
    private $contacts_table = "sms_contacts";
    private $groups_table   = "sms_contact_groups";
    
    public function __construct() {
        parent::__construct();
    }
    
    
    public function save_contact($form_data){
        return $this->_add($this->contacts_table, $form_data, CONTACT_REC_ID);
    }
   

    public function delete_contact($contact_id){
        return $this->_delete($this->contacts_table,array(CONTACT_REC_ID=>$contact_id));
    }
    

    public function get_contacts(){
        return $this->_get_all($this->contacts_table);
    }
    
    
    public function save_group($form_data) {
        return $this->_add($this->groups_table, $form_data, GROUP_REC_ID);
    }
    

    public function get_groups(){
        return $this->_get_all($this->groups_table);
    }
    

    public function delete_group($group_id){
        return $this->_delete($this->groups_table,array(GROUP_REC_ID=>$group_id));
    }
    

    public function update_group($form_data) {
        return $this->_add($this->groups_table, $form_data, GROUP_REC_ID);
    }


    public function send_SMS($msg){
        $this->load->library("cloudsms");
        $destination = $msg['recipients'];
        $destinations = explode(",",$destination);
        $response = array();
        $sent_count = 0;
        foreach($destinations as $recipient){
            $response = "";
            $response  = $this->cloudsms->send_sms(urlencode($msg['sender']),$recipient,urlencode($msg['message']));
            $sent_count += ($response == "101") ? 1 : 0;
        }
        return array("sent"=>$sent_count,"numbers"=>$destinations);
    }
    
}

