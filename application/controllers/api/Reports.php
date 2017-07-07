<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "controllers/Rest_Ctrl.php");

class Reports extends Rest_Ctrl {
    
    public $User;
    
    public function __construct() {
        parent::__construct();

        $this->_load_model("User","Users");
        $this->_load_model("School_setup");
        $this->_load_model("Student","Students");
        $this->_load_model("SubjectSettings");
        $this->_load_model("Results");
        $this->_load_model("Reports");
        
        $this->User = $this->Users->get($this->session->userdata("username"));
    }
    

    public function class_report_get(){

        $session_id = $this->input->get("session_id");
        $term_id = $this->input->get("term_id");
        $class_id = $this->input->get("class_id");


        $data = $this->Reports->get_class_students_report($session_id, $term_id, $class_id);
        $msg = sizeof($data) > 0 ? success_msg("Returned " . sizeof($data) . " record(s)." ) : info_msg("No record found for selection!");

        $this->response(array("data"=>$data, "msg"=>$msg));
       
    }
}