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

    public function student_report_get(){
        $req = $this->input->get();
        
        $student_id = "";
        if(isset($req['student_id']) && $req['student_id'] != ""){
            $student_id = $req['student_id'];
        }
        
        $session_id = isset($req['session_id']) && is_valid_string($req['session_id']) ? $req['session_id'] : $this->myapp->get_current_session_id();
        $term_id = isset($req['term_id']) && is_valid_string($req['term_id']) ? $req['term_id'] : $this->myapp->get_current_term_id();

        $student = $this->Student->get_student($student_id, $session_id);
        $class = $this->Student->get_class_for_student($student_id, $session_id);
        $class_id = $class['class_id'];

        $student_subjects = $this->SubjectSettings->get_student_subjects($student_id, $session_id, $class_id);

        $subject_scores = $this->Reports->get_students_subject_report($session_id, $term_id, $class_id, $student_id, $student_subjects);
        $student_report_overview = $this->Reports->get_students_report_overview($session_id, $term_id, $class_id, $student_id);
        $class_report_overview = $this->Reports->get_class_report_overview($session_id, $term_id, $class_id);
        $result_info = array("term"=>$this->School_setup->get_term_name($term_id));
        $result_info['session'] = $this->School_setup->get_session_name($session_id);
        $school_info = $this->School_setup->get_school_settings();

        $data['student'] = $student;
        $data['class'] = $class;
        $data['subject_scores'] = $subject_scores;
        $data['student_report_overview'] = $student_report_overview;
        $data['class_report_overview'] = $class_report_overview;
        $data['cog_skills_report'] = $this->Reports->get_cog_skills_report($student_id,$session_id,$term_id);
        $data['result_info'] = $result_info;
        $data['school_info'] = $school_info;

        //$data['result_display'] = $this->Result->get_student_data_display();

        $this->response(rest_success($data));
    }
}