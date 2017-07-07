<?php
include_once 'School.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("App_Controller.php");

class Staff extends App_Controller
{
    
    private $staff_id;
    private $staff_username;
    
    function __construct() {
        parent::__construct();
        
        //Check if user logged in
        if($this->session->userdata('logged_in') === FALSE)
        {
            redirect();
        }
        else
        {
            //Check to ensure admin is logged in
            if($this->session->userdata('logged_in_role') !== "staff")
            {
                redirect();
            }
        }
        $this->load->model('staff_model','Staff');
        
        $this->staff_username = $this->session->userdata('username');
        $this->staff_id = $this->Staff->get_staff_id($this->staff_username);
    }
    
    
    function index($page = "dashboard",$args = array(),$view_folder = "staff")
    {
        $page_data = ['title'=>'Staff'];
        if($page == "dashboard")
        {
            $args = array_merge($args,$this->Staff->get_staff_overview($this->staff_id));
        }
        $page_data = array_merge($page_data,$args);
        $page_data['page'] = (title_text($page));
        if(file_exists(APPPATH ."/views/{$view_folder}/{$page}.php"))
        {
            $page_data['form_controller'] = "staff";
             $page_data['page_content'] = $this->load->view($view_folder . '/'.$page,$page_data,TRUE);
        }
        else $page_data['page_content'] = info_msg("Page under serious construction. Please chill..");
        $this->load->view('header',$page_data);
        $this->load->view("staff/index");
        $this->load->view('footer');
    }
    
    
    
    function p($page,$args = array())
    {
        $page_data = array();
        switch ($page)
        {
            case "subjects":
                $page_data['my_subjects'] = $this->Staff->get_my_subjects($this->staff_id);
                break;
            case "classes":
                $page_data['my_classes'] = $this->Staff->get_my_classes($this->staff_id);
                break;
            case "message":
                $page_data['message'] = $this->Messages->get_inbox($this->User->getUserId());
                break;
        }
        $page_data = array_merge($page_data,$args);
        $this->index($page,$page_data);
    }
    
    
    function subject_student_message($subject_id,$class_id,$page_data = array()){
        $this->load->model("subject_model","Subject");
        $page_data['subject_students'] = $this->Subject->get_subject_students($subject_id,$class_id);
        $page_data['class_name'] = $this->School_setup->get_class_name($class_id);
        $page_data['subject_name'] = $this->School_setup->get_subject_name($subject_id);
        $page_data['subject_id'] = $subject_id;
        $page_data['class_id'] = $class_id;
        $this->index("subject_students_message",$page_data);
    }
	
	
    function class_students_message($class_id,$page_data = array()){
        $this->load->model("classes_model","Classes");
        $page_data['class_students'] = $this->Classes->get_class_students($class_id);
        $page_data['class_id'] = $class_id;
        $page_data['class_name'] = $this->Classes->get_class_name($class_id);
        $this->index("class_students_message",$page_data);
    }
    
    /**
     * Sends message from this staff.
     * Expects a vital form value with the  name "target", that determines
     * how the recipients are going to be extracted from the form data
     */
    function send_message(){
        $form_data = $this->input->post();
        $message_data['message'] = $form_data['message'];
        $target = $form_data['target'];
        $message_data['recipients'] = $form_data['recipients'];
        $message_data['sender_id'] = $this->staff_id;
        $msg = $this->Messages->send_message($message_data,$this->staff_username);
        switch ($target){
            case "subject_students":
                $this->subject_student_message($form_data['subject_id'], $form_data['class_id'],array("msg"=>$msg));
                break;
            case "class_students_message":
                $this->class_students_message($form_data['class_id'],array("msg"=>$msg));
                break;
            case "class_students":
                $this->class_students($form_data['class_id'],array("msg"=>$msg));
                break;
        }
    }
            
    
    function subject_class($subject_id,$class_id)
    {
        $page_data = array();
        if($this->Staff->confirm_class_subject_teacher($this->staff_id,$subject_id,$class_id))
        {
            $page_data['subject_id'] = $subject_id;
            $page_data['class_id'] = $class_id;
            $page_data['class_name'] = $this->School_setup->get_class_name($class_id);
            $page_data['subject_name'] = $this->School_setup->get_subject_name($subject_id);
            $page_data['subject_students'] = $this->Result->get_subject_result_form($subject_id,$class_id,$this->curr_session_id,$this->curr_term_id);
            $page_data['msg'] = sizeof($page_data['subject_students']) > 0 ? "" : info_msg("No student found for this subject");
        }
        else 
        {
            $page_data['msg'] = info_msg("You have not been assigned this subject. But why?");
        }
        $this->index('subject_students',$page_data);
    }
    
    
    function save_subject_result()
    {
        $data = $this->input->post();
        $page_data = array();
        $saved = $this->Result->save_subject_result($data,$this->curr_session_id,$this->curr_term_id);
        $page_data['msg'] = ($saved) ? success_msg("Result Saved..") : warning_msg("Not saved. Retry");
        if($this->Staff->confirm_class_subject_teacher($this->staff_id,$data['subject_id'],$data['class_id']))
        {
            $page_data['subject_id'] = $data['subject_id'];
            $page_data['class_id'] = $data['class_id'];
            $page_data['class_name'] = $this->School_setup->get_class_name($data['class_id']);
            $page_data['subject_name'] = $this->School_setup->get_subject_name($data['subject_id']);
            $page_data['subject_students'] = $this->Result->get_subject_result_form($data['subject_id'],$data['class_id'],$this->curr_session_id,$this->curr_term_id);
            //$page_data['subject_students'] = $this->Staff->get_subject_students($subject_id,$class_id);
        }
        $page_data['uses_angular'] = TRUE;
        $this->index('subject_students',$page_data);
    }
    
    
    function class_students($class_id,$page_data = array())
    {
        if($this->Staff->confirm_class_teacher($this->staff_id,$class_id))
        {
            $my_class_students = $this->School_setup->get_class_students($class_id);
             $data['msg'] = ($my_class_students != NULL) ? success_msg("Showing " . sizeof($my_class_students) . ""
                . " result(s).") : info_msg("No student found for this class");
            $page_data['class_students'] = $my_class_students;
            $page_data['class_name'] = $this->School_setup->get_class_name($class_id);
            $page_data['class_id'] = $class_id;
            $this->session->set_flashdata('prev_page',"staff/class_students/".$class_id);
        }
        
        else
        {
            $page_data['msg'] = info_msg("You have not been assigned this class....");
        }
        $this->index('class_students',$page_data);
    }
    
    
    function input_result($class_id,$student_id)
    {
         $student_subjects = $this->Result->get_student_result_form($student_id,$class_id,$this->curr_session_id,$this->curr_term_id);
         $page_data['msg'] = (sizeof($student_subjects) > 0) ? "" : info_msg("No subjects for this student...");
         $page_data['student_subjects'] = $student_subjects;
         $page_data['student_id'] = $student_id;
         $page_data['class_id'] = $class_id;
         $page_data['student_name'] = $this->Student->get_student_name($student_id);
         $page_data['cog_skills_result'] = $this->Result->get_cog_skills_result($student_id,$this->curr_session_id,$this->curr_term_id);
         $page_data['uses_angular'] = true;
         $this->index('input_student_result',$page_data);
    }
    
    
    function save_student_result()
    {
        $data = $this->input->post();
        $page_data = array();
        if($this->form_submitted()) {
            if($this->Staff->confirm_class_teacher($this->staff_id,$data['class_id']))
            {
                $saved = $this->Result->save_student_result($data,$this->curr_session_id,$this->curr_term_id);
                $cog_skills_saved = $this->Result->save_cog_skills_result($data,$this->curr_session_id,$this->curr_term_id);
                $page_data['msg'] = ($saved) ? success_msg("Result Saved..") : warning_msg("Not saved. Retry");
                $page_data['student_id'] = $data['student_id'];
                $page_data['class_id'] = $data['class_id'];
                $page_data['student_name'] = $this->Student->get_student_name($data['student_id']);
                $page_data['student_subjects'] = $this->Result->get_student_result_form($data['student_id'],$data['class_id'],$this->curr_session_id,$this->curr_term_id);
                $page_data['cog_skills_result'] = $this->Result->get_cog_skills_result($data['student_id'],$this->curr_session_id,$this->curr_term_id);
            }
        }
        $this->index('input_student_result',$page_data);
    }
    
}