<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("School.php");

class App_Controller extends School_MS {
    
    public $folder       = "";
    public $uses_angular = false;
    public $page_title   = "";
    public $User;
    
    public function __construct(){
        parent::__construct();
        
        $this->load->model("User_model","Users");
        $this->User = $this->Users->get($this->session->userdata("username"));
        
        $this->is_authorized();
        
    }
    
    /**
     * Entry point for every controller. Most pages use similar
     * code in the index function and any page or controller who has different 
     * implementation can override this implementation
     * @param type $page Page to be viewed
     * @param type $args Extra data to be passed to page.
     */
     public function index($page = "",$args = array()){
        
        $page = ($page == "") ? "dashboard" : $page;
        $page_data = array();
        $page_data['uses_angular'] = $this->uses_angular;
        $page_data['page_content'] = $this->load->view($this->folder,$page_data,true);
        $page_data['page'] = $page_data['title'] = $this->page_title;
        $this->load->view("header",$page_data);
        $this->load->view($this->User->getFolder() . "/index");
        $this->load->view("footer");
    }
    
    
    public function is_authorized() {
        
         //Check if user logged in
        if($this->session->userdata('logged_in') === FALSE){
            redirect();
        }
        else
        {
            //Check to ensure admin is logged in
            if($this->session->userdata('logged_in_role') !== $this->User->getRole()){
                redirect();
            }
        }
    }
}
