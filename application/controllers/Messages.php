<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once("App_Controller.php");

class Messages extends App_Controller{
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model("Messages_model","Messages");
    }
    
    
    public function index($page = "message",$args = array()){
        
        $page_data = array();
        $page_data['uses_angular'] = true;
        $page_data['page_content'] = $this->load->view("messages/".$page,$page_data,true);
        $page_data['page'] = $page_data['title'] = ucfirst(str_replace("-"," ",$page));
        $this->load->view("header",$page_data);
        $this->load->view($this->User->getFolder() . "/index");
        $this->load->view("footer");
    }
    
    
    function sms(){
        $this->index("sms-message",array("title"=>"SMS Messaging"));
    }
    
}