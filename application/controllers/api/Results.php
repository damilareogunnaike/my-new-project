<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "controllers/Rest_Ctrl.php");

class Results extends Rest_Ctrl {
    
    public $User;
    
    public function __construct() {
        parent::__construct();

        $this->_load_model("User","Users");
        $this->_load_model("School_setup");
        $this->_load_model("Student","Students");
        $this->_load_model("SubjectSettings");
        $this->_load_model("Results");
        
        $this->User = $this->Users->get($this->session->userdata("username"));
    }

    public function students_result_get(){
        $student_id = $this->input->get("student_id");
        $session_id = $this->input->get("session_id");
        $term_id = $this->input->get("term_id");
        $class = $this->Students->get_class_for_student($student_id, $session_id);

        $student_subjects = $this->SubjectSettings->get_student_subjects($student_id, $session_id, $class['class_id']);
        //die();
        $subject_scores = $this->Results->get_student_subject_scores($student_id, $session_id, $term_id, $class['class_id'], $student_subjects);
        $this->response(array("form"=>$subject_scores));
    }
    

    public function students_result_put(){
        $req_obj = $this->_put_args;
        $session_id = $req_obj['session_id'];
        $term_id = $req_obj['term_id'];
        $student_id = $req_obj['student_id'];
        $scores = $req_obj['scores'];
        $class = $this->Students->get_class_for_student($student_id, $session_id);

        $status = $this->Results->save_student_subject_scores($session_id, $term_id, $class['class_id'], $student_id, $scores);
        $this->response(array("status"=>$status));
    }


    public function class_subject_result_get(){
        $session_id = $this->input->get("session_id");
        $term_id = $this->input->get("term_id");
        $class_id = $this->input->get("class_id");
        $subject_id = $this->input->get("subject_id");


        $subject_students = $this->Students->get_by_session_and_class_and_subject($session_id, $class_id, $subject_id);
        $student_scores = $this->Results->get_subject_students_scores($subject_id, $class_id, $term_id, $session_id, $subject_students);
        $this->response(array("form"=>$student_scores));
    }


    public function class_subject_result_put(){
        $req_obj = $this->_put_args;
        $session_id = $req_obj['selection']['session_id'];
        $term_id = $req_obj['selection']['term_id'];
        $class_id = $req_obj['selection']['class_id'];
        $subject_id = $req_obj['selection']['subject_id'];
        $scores = $req_obj['scores'];

        $status = $this->Results->save_class_subject_scores($session_id, $term_id,$class_id, $subject_id, $scores);
        $this->response(array("status"=>$status));

    }


    public function compute_result_steps_get(){
        $this->response(array("next_step"=>0,"steps"=>$this->result_computation_steps));
    }


    public function compute_results_get(){
        $session_id = $this->input->get("session_id");
        $term_id = $this->input->get("term_id");
        $class_id = $this->input->get("class_id");
        $step = $this->input->get("step");

        $compute_completed = false;

        $executed_step = $this->result_computation_steps[$step];
        $executed_step['index'] = $step;
        $executed_step['completed'] = true;
        $remark = "";

        $class_students = $this->Students->get_students_by_class_and_session($class_id, $session_id);
        $response = array("status"=>false);
        switch ($step) {
            case 0:
                $remark = "Request interpreted!";
                break;
            case 1:
                $response = $this->Results->compute_student_result($session_id, $term_id, $class_id, $class_students);
                if($response['status'] == true){
                    $remark = "Results computed for " . $response['records'] . " student(s)!";
                }
                break;
            case 2:
                $response = $this->Results->compute_subject_result($session_id, $term_id, $class_id);
                if($response['status'] == true){
                    $remark = "Subjects overview computation completed successfully!";
                }
                break;
            case 3:
                $response = $this->Results->compute_class_result_overview($session_id, $term_id, $class_id, $class_students);
                if($response['status'] == true){
                    $remark = "Class overview computation completed successfully!";
                }
                break;
            case 4:
                $remark = "Successful";
                break;
            case 5:
                $remark = "All completed. You can now view reports!";
                break;
        }

        $executed_step['remark'] = $remark;
        $compute_completed = ($step >= 5) ? true : false;

        $this->response(array("executed_step"=>$executed_step, "compute_completed"=>$compute_completed));

    }

   private $result_computation_steps = array(
        array("name"=>"Interpreting Request..","completed"=>false,"next_step"=>1,"in_progress"=>true),
        array("name"=>"Computing Student Reports..","completed"=>false,"next_step"=>2),
        array("name"=>"Computing Subject Reports..","completed"=>false,"next_step"=>3),
        array("name"=>"Computing Class Reports..","completed"=>false,"next_step"=>4),
        array("name"=>"Finishing up.. ","completed"=>false,"next_step"=>5),
        array("name"=>"Completed","completed"=>false)
        );
}
