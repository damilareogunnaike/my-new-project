<?php
//Get Base Class School which extends CI_Controller
include_once 'school.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Health extends School_MS{ 
    
    public function __construct(){
        parent::__construct();
        
         //Check if user logged in
        if($this->session->userdata('logged_in') === FALSE)
        {
            redirect();
        }
        else
        {
            //Check to ensure health is logged in
            if($this->session->userdata('logged_in_role') !== "health")
            {
                redirect();
            }
        }
        
        $this->load->model('student_model','Student');
        $this->load->model('health_model','Health');
    }
    
    public function index($page = "health_records",$args =array(),$view_folder = "health")
    {
        $page_data = ['title'=>'Health'];
        $page_data = array_merge($page_data,$args);
        $page_data['page'] = (title_text($page));
        $page_data['curr_session_id'] = $this->curr_session_id;
         if(file_exists(APPPATH ."/views/{$view_folder}/{$page}".EXT))
        {
             if($page == "dashboard")
             {
                 $page_data = array_merge($page_data,$this->get_health_overview());
             }
             $page_data['form_controller'] = "health";
             $page_data['page_content'] = $this->load->view($view_folder. '/'.$page,$page_data,TRUE);
        }
        else {  $page_data['page_content'] = info_msg("Page under serious construction. Please chill.."); }
        
        $this->load->view('header',$page_data);
        $this->load->view("health/index");
        $this->load->view('footer');
    }
    
    
    
    function p($page,$args = array())
    {
        $page_data = array();
        switch ($page)
        {
            
        }
        $page_data = array_merge($page_data,$args);
        $this->index($page,$page_data);
    }
    
    
    function search_student($tab = 1)
    {
        $page_data = array();
        $student_id = $this->input->post("student_id");
        $students = $this->Student->search_student($student_id);
        if(sizeof($students) > 0){
            $page_data['students'] = $students;
        }
        else {
            $page_data['msg'] = info_msg("No student found for search!");
        }
        if($tab === "tab2") { $page_data['active_tab'] = "check_health_records"; }
        $this->p("health_records",$page_data);
    }
    
    
    function enter_health_record($student_id){
        $student_details = $this->Student->get_biodata($student_id);
        $page_data['student_details'] = $student_details;
        $page_data['student_details']['class_name'] = $this->School_setup->get_class_name($page_data['student_details']['class_id']);
        $this->p('health_records',$page_data);
    }
    
    
    
    function save_health_records(){
        $data = $this->input->post();
        $data["session_id"] = $this->curr_session_id;
        $data["term_id"] = $this->curr_term_id;
        $saved = $this->Health->save_health_records($data);
        $page_data['msg'] = ($saved) ? success_msg("Record saved!!") : warning_msg("Not saved.");
        $this->p("health_records",$page_data);
    }
    
    
    
    function view_health_records($student_id){
        $records = $this->Health->view_health_records($student_id,$this->curr_session_id,$this->curr_term_id);
        $page_data['msg'] = (sizeof($records) > 0) ? success_msg("Health Records.") : info_msg("No records fetched!"); 
        $page_data['health_records'] = $records;
        $page_data['active_tab'] = "check_health_records";
        $this->p("health_records",$page_data);
    }
    
    
    function delete_record($id){
        $deleted = $this->Health->delete_health_record($id); 
        $page_data['msg'] = ($deleted) ? success_msg("Record deleted..") : warning_msg("Record not deleted!");
        $page_data['active_tab'] = "check_health_records";
        $this->p("health_records",$page_data);
    }
    
}