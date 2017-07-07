<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "controllers/Rest_Ctrl.php");

class School_setup extends Rest_Ctrl {
    
    public $User;
    
    public function __construct() {
        parent::__construct();
            
        $this->load->model("User_model","Users");
        $this->load->model("School_setup_model","School_setup");
        $this->load->model("Classes_model","Classes");
        $this->load->model("Subjects_model","Subjects");
        $this->load_model("SubjectSettings");
        $this->load_model("Student");

        $this->load->library("Myapp");

        $this->User = $this->Users->get($this->session->userdata("username"));
    }
    

    public function classes_get(){
        $classes = $this->Classes->get_all();
        $this->response(array("data"=>$classes));
    }


    public function subjects_get(){
        $subjects = $this->Subjects->get_all();
        $this->response(array("data"=>$subjects));
    }


    public function sessions_get(){
        $sessions = $this->School_setup->get_school_sessions();
        $this->response(array("data"=>$sessions));
    }


    public function school_terms_get(){
        $terms = $this->School_setup->get_school_terms();
        $this->response(array("data"=>$terms));
    }


    public function class_setting_put(){
        $req_obj = $this->_put_args;
        $response =$this->Classes->update($req_obj);
        $this->response($response);
    }


    public function class_subjects_post(){
        $req_obj = $this->input->post();
        $status = $this->SubjectSettings->save_class_subjects($req_obj, $this->_current_session_id);
        $this->response(array("status"=>$status));
    }


    public function class_subjects_get($class_id){
        $subjects = $this->SubjectSettings->get_class_subjects($class_id, $this->_current_session_id);
        $this->response(array("data"=>$subjects));
    }


   public function class_subjects_aggregate_get($class_id){
        $subjects = $this->SubjectSettings->get_class_subjects($class_id, $this->_current_session_id, TRUE);
        $this->response(array("data"=>$subjects));
    }


    public function class_subjects_delete($record_id){
        $status = $this->SubjectSettings->remove_class_subjects($record_id, $this->_current_session_id);
        $this->response(array("status"=>$status));
    }


    public function student_subjects_post(){
        $req_obj = $this->input->post();
        $status = $this->SubjectSettings->save_student_subjects($req_obj, $this->_current_session_id);
        $this->response(array("status"=>$status));
    }

    public function student_subjects_get($student_id){
        $student = $this->Student->get_student($student_id, $this->_current_session_id);
        $class_subjects = $this->SubjectSettings->get_class_subjects($student['class']['class_id'], $this->_current_session_id);

        if($student['class']['subject_selection_mode'] == 2){
            $optional_subjects = $this->SubjectSettings->get_student_optional_subjects($student_id, $this->_current_session_id, $student['class']['class_id']);
        }
        else {
            $optional_subjects = array();
        }

        $subjects = array("compulsory"=>$class_subjects,"optional"=>$optional_subjects);
        $this->response(array("subjects"=>$subjects));
    }

    public function student_subjects_delete($record_id){
        $status = $this->SubjectSettings->remove_student_subjects($record_id, $this->_current_session_id);
        $this->response(array("status"=>$status));
    }


    public function load_model($model, $model_name = null){
        $model_name = ($model_name == null) ? $model : $model_name;
        $this->load->model($model . "_model",$model_name);
    }

}
