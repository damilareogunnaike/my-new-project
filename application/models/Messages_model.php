<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("Crud_model.php");

define("KEY_RECORD_ID","message_id");


class Messages_model extends Crud_model{
    
    private $table = "messages";
    public function __construct() {
        parent::__construct();
    }
    
    function send_message($msg_data,$sender_id){
        $insert_data = array();
        foreach($msg_data['recipients'] as $recipient){
            $record = array("recipient_id"=>$recipient,"message"=>$msg_data['message'],"sender_id"=>$sender_id);
            $insert_data[] = $record;
        }
        $this->db->insert_batch($this->table,$insert_data);
        return $this->db->affected_rows() > 0 ? success_msg("Message sent to " . $this->db->affected_rows() . " "
                . "recipient(s)") : error_msg("Message not sent. Retry!");
    }
    
    /**
     * Retrieves messages sent for an id. The reason why an array was used as argument
     * is in case of parents who have multiple wards. Messages sent are sent to wards and 
     * not parents in most cases so the parents get to view the messages sent to their
     * wards
     * @param type $recipients An array of recipients whose message is to be fetched. 
     */
    function get_received_messages($recipients){
        if(is_array($recipients)){
            $this->db->where("recipient_id",array_pop($recipients));
            foreach($recipients as $recipient){
                $this->db->or_where("recipient_id",$recipient);   
            }
            $this->db->order_by("date_added","DESC");
            $rs = $this->db->get("messages");
            if($rs->num_rows() > 0){
                $this->load->model("user_model","Users");
                $messages = array();
                foreach($rs->result_array() as $row){
                    $message = $row;
                    $message['recipient'] = $this->Users->get_user_profile($row['sender_id']);
                    $messages[] = $message;
                }
                return $messages;
            }
            return NULL;
        }
        return NULL;
    }
    
    /**
     * Saves a new message in DB
     * @param Array $message Message data to be sent containing sender_id, recipients and content
     */
    public function save($message){
        // In some cases, recipients may not be set which means the message
        // is a draft message. We don't want errors to occur here so this check.. ** winks **
        if(isset($message['recipients'])) {
            $recipients = $message['recipients'];
            unset($message['recipients']);
        }
        
        // Then save the message
        $svd_message = $this->_add(MESSAGE_TB,$message,KEY_RECORD_ID);
        
        // Now if recipient was found, send the message to the recipients
        if(strlen($recipients) > 0 && $recipients != ""){
            $this->post_recipient_message($svd_message['message_id'], $recipients);
            $svd_message['recipients'] = $recipients;
        }
        return $svd_message;
    }
    
    
    private function post_recipient_message($message_id,$recipients){
        $recipients_arr = explode(",",$recipients);
        foreach($recipients_arr as $v){
            $insert_data[] = array("message_id"=>$message_id,"user_id"=>$v);
        }
        $this->db->insert_batch(RECIPIENTS_TB,$insert_data);
    }
    
    
    public function get_inbox($user_id){
        $this->db->select("message_id")->where(array("user_id"=>$user_id,"deleted"=>0));
        $sub_sql = $this->db->get_compiled_select(RECIPIENTS_TB);
        $this->db->where_in("message_id",$sub_sql,FALSE);
        $rs = $this->db->get(MESSAGE_TB);
        return $rs->num_rows() > 0 ? $rs->result_array() : array();
    }
    
    
    public function get_outbox($user_id,$is_draft = 0){
        $this->db->select("GROUP_CONCAT(user_id)");
        $this->db->where(MESSAGE_TB . ".message_id", RECIPIENTS_TB. ".message_id",FALSE);
        $sub_sql = $this->db->get_compiled_select(RECIPIENTS_TB);
        $this->db->select(MESSAGE_TB . ".*")->select("(" . $sub_sql . ") AS recipients");
        $this->db->where("sender_id",$user_id);
        $this->db->where("is_draft",$is_draft,FALSE);
        $rs = $this->db->get(MESSAGE_TB);
        return $rs->num_rows() > 0 ? $rs->result_array() : array();
    }
    
    
    public function delete_message($user_id,$message_id) {
        $message = $this->get_message($message_id);
        if($message['sender_id'] == $user_id){
            //Then this is an outbox message, we set deleted column to true in 
            //recipients tables
            $this->db->where(array("sender_id"=>$user_id,"message_id"=>$message_id));
            $this->db->delete(MESSAGE_TB);
        }
        else {
            //This is an inbox message
            $this->db->where(array("user_id"=>$user_id,"message_id"=>$message_id));
            $this->db->update(RECIPIENTS_TB,array("deleted"=>1));
        }
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }
    
    
    
    function get_trash($user_id){
        //
    }
    
    
    public function get_draft($user_id){
        return $this->get_outbox($user_id,1);
    }
    
    
    public function get_message($message_id) {
        $rs = $this->_get_where(MESSAGE_TB,array("message_id"=>$message_id));
        return $rs[0];
    }
}

