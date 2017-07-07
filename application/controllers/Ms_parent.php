<?php
include_once 'School.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Ms_parent extends School_MS
{
    private $parent_id = NULL;
    private $parent_username = NULL;
    
    function __construct() {
        parent::__construct();

        $this->load->model('parent_model','Parent');
        
        $this->parent_username = $this->session->userdata('username');
        $this->parent_id = $this->Parent->get_parent_id($this->parent_username);
    }
    
    
    function index($page = "dashboard",$args =array(),$view_folder = "parent")
    {
        $page_data = ['title'=>'Parent'];
        $page_data = array_merge($page_data,$args);
        $page_data['page'] = (title_text($page));
         if(file_exists(APPPATH ."/views/{$view_folder}/{$page}.php"))
        {
             if($page == "dashboard")
             {
                 $page_data = array_merge($page_data,$this->Parent->get_parent_overview($this->parent_id));
             }
             $page_data['form_controller'] = "ms_parent";
             $page_data['page_content'] = $this->load->view($view_folder. '/'.$page,$page_data,TRUE);
        }
        else {
             $page_data['page_content'] = info_msg("Page under serious construction. Please chill..");
        }
        $this->load->view('templates/header',$page_data);
        $this->load->view("parent/{$page}");
        $this->load->view('angular_files');
        $this->load->view('templates/footer');
    }


    //redirect parent pages
    function p($page,$args = array())
    {
        $page_data = array();
        switch ($page)
        {
            case "wards":
            case "result_pin_validation":
                $my_wards = $this->Parent->get_wards($this->parent_id);
                if(sizeof($my_wards) > 0) {
                    $page_data['my_wards'] = $my_wards;
                }
                else {
                    $page_data['msg'] = info_msg("You have no wards to view. Contact Administrator to verify.");
                }
                break;
            case "message":
                $this->load->model("messages_model","Messages");
                $recipients = $this->Parent->get_wards_ids($this->parent_id);
                $recipients[] = $this->parent_id;
                $page_data['messages'] = $this->Messages->get_received_messages($recipients);
        }
        $page_data = array_merge($page_data,$args);
        $this->index($page,$page_data);
    }


    function view_ward($student_id)
    {
        $page_data = array();
        if(!$this->Parent->confirm_ward($this->parent_id,$student_id))
        {
            $page_data['msg'] = warning_msg("You are not allowed to view this page..");
            $this->p('wards',$page_data);
            return 0;
        }
        else
        {
            $record = $this->School_setup->get_student_pin($student_id);
            $data["parent_view"] = TRUE;
            if($record != NULL) {
            $data["pin_validated"] = TRUE;
            }
            else {
                $data['pin_validated'] = FALSE;
            }
            $this->view_stud_record($student_id,$data);
            return 0;
        }
        $this->p('student_record',$page_data);
    }
    
    
    function input_pin(){
        $data = $this->input->post();
        $page_data = array();
        if($this->School_setup->validate_pin($data['pin']))
        {
            $status = $this->School_setup->set_result_pin($this->curr_session_id,$this->curr_term_id,$data);
            $page_data['msg'] = $status;
        }
        else { $page_data['msg'] = warning_msg ("Pin does not exist or pin already taken. Contact Admin."); }
        $page_data['student_id'] = $data['student_id'];
        $this->p("result_pin_validation",$page_data);
    }
    
    
    function check_pin(){
        $data = $this->input->post();
        $record = $this->School_setup->get_student_pin($data['student_id']);
        $page_data = array();
        if($record != NULL){
            $page_data['pin'] = $record['pin'];
        }
        else {
            $page_data['msg'] = info_msg("Pin not set for student");
        }
        $page_data["student_id"] = $data['student_id'];
        $page_data["active_tab"] = "check_pin";
        $this->p("result_pin_validation",$page_data);
    }
    
    
    function send_message(){
        $form_data = $this->input->post();
        $message_data['message'] = $form_data['message'];
        $target = $form_data['target'];
        $message_data['recipients'] = $form_data['recipients'];
        $msg = $this->Messages->send_message($message_data,$this->parent_username);
        $page_data['msg'] = $msg;
        switch($target){
            case "message":
                $this->p("message",$page_data);
        }
    }


    /** NEW FEATURES */
    public function login(){
        $this->index("login");
    }

}