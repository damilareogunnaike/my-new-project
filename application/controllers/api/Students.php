<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "controllers/Rest_Ctrl.php");

class Students extends Rest_Ctrl {
    
    public $User;
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model("User_model","Users");
        $this->load->model("Staff_model","Staff");
        $this->load->model("Parent_model","Parents");
        $this->load->model("School_setup_model","School_setup");

        $this->load_model("Student","Students");
        
        $this->User = $this->Users->get($this->session->userdata("username"));
    }
    

    function index_get(){
        $req_data = $this->input->get();
        if(!isset($req_data['id']) || $req_data['id'] == ""){
            $this->response("Resource not found", 404);
        }

        $student_id = $req_data['id'];

        $student_profile = $this->Students->get($student_id);
        $this->response(rest_success($student_profile));
    }


    function search_get(){

        $req_obj = $this->input->get();
        $keyword = get_arr_value($req_obj,'keyword');
        $page_no = get_arr_value($req_obj, "page");
        $page_size = get_arr_value($req_obj, "pageSize");
        $class_id = get_arr_value($req_obj,"classId");
        $session_id = get_arr_value($req_obj,"sessionId");

        $students = array();
        
        if($session_id != null && $class_id != null){
            $students = $this->School_setup->get_class_students($class_id,$session_id,$page_no,$page_size);
        }

        else if($keyword != null) {
            $students = $this->Students->search($keyword, $page_no, $page_size);
        }

        $this->response(array("data"=>$students,"count"=>sizeof($students)));
    }

    public function update_class_put(){
        $req_obj = $this->_put_args;
        $response = $this->Students->update_class($req_obj['sessionId'], $req_obj['classId'], $req_obj['studentIds']);
        $this->response($response);
    }

    private function load_model($model, $model_name = null){
        $model_name = ($model_name == null) ? $model : $model_name;
        $this->load->model($model . "_model",$model_name);
    }
    
    
}
