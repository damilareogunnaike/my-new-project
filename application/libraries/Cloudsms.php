<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cloudsms {

    private $user_id;
    private $password;
    private $msg_type;
    private $sender;
    private $message;
    private $destination;
    private $api = "http://developers.cloudsms.com.ng/api.php?userid={user_id}&password={password}&type=={type}&destination={destination}&sender={sender}&message={message}";


    public function __construct(){
   		
   		$CI = & get_instance();

   		$CI->config->load("bulk_sms",TRUE);
        $bulk_sms = $CI->config->item("bulk_sms");
        $cloudsms = $bulk_sms['cloudsms'];
    	$this->user_id = $cloudsms['user_id'];
    	$this->password = $cloudsms['password'];
    	$this->msg_type = $cloudsms['type'];



    	$this->api = str_replace("{user_id}",$this->user_id,$this->api);
    	$this->api = str_replace("{password}",$this->password,$this->api);
    	$this->api = str_replace("{type}",$this->msg_type,$this->api);
    }


    public function send_sms($sender,$destination,$message){
    	$this->sender = $sender;
    	$this->destination = $destination;
    	$this->message = $message;

    	$this->destination = "234" . substr($this->destination,1,strlen($this->destination));
    	$this->api = str_replace("{destination}",$this->destination,$this->api);
    	$this->api = str_replace("{sender}",$this->sender,$this->api);
    	$this->api = str_replace("{message}",$this->message,$this->api);

    	$api = $this->api;
    	$response = "";
    	$response = file_get_contents($api);
    	return $response;
    }
}