<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once("App_Controller.php");

class Base extends App_Controller{
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model("Classes_model","Classes");
        $this->load->model("Reports_model","Reports");
    }
    
    
    public function index($page = "classes",$args = array(), $page_title = null){
        
        $page_data = array();
        $page_data['uses_angular'] = true;
        $page_data['page_content'] = $this->load->view($page,$page_data,true);
        $page_title = ($page_title == null) ? ucfirst(str_replace("-"," ",$page)) : $page_title;
        $page_data['page'] = $page_data['title'] = $page_title;
        $this->load->view("header",$page_data);
        $this->load->view($this->User->getFolder() . "/index");
        $this->load->view("footer");
    }
    
    

    public function messages($page = "message"){
        $page_data = array();
        $this->index("messages/".$page, $page_data, "Messaging");
    }
    

    public function classes($page = "classes"){
        $page_data = array();
        $this->index("classes/".$page, $page_data, "Classes");
    }


    public function subject_config(){
        $page_data = array();
        $this->index("acad-config/subject-config",$page_data, "Subject Configuration");
    }


    public function results($page = "view-results"){
        $page_data = array();
        $title = ucwords(title_url($page));
        $this->index("results/".$page, $page_data, $title);
    }


    public function reports($page = "basic-report", $action="view", $session_id = "", $term_id = "", $class_id = ""){
        $page_data = array();
        $title = ucwords(title_url($page));
        

        if($action == "print"){
            $this->load->model("School_setup_model","School_setup");
            $page_data['class_result'] = $this->Reports->get_class_students_report($session_id, $term_id, $class_id);
            $page_data['school_info'] =  $this->School_setup->get_school_settings();
            $result_info = array("term"=>$this->School_setup->get_term_name($term_id));
            $result_info['session'] = $this->School_setup->get_session_name($session_id);
            $result_info['class'] = $this->Classes->get_class_name($class_id);
            $page_data['result_info'] = $result_info;
            $this->load->view('results/header_content',$page_data);
            $this->load->view("results/sheets/class_report");
        }
        else {
            $this->index("reports/".$page, $page_data, $title);
        }
    }

    
    
}