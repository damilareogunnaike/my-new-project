<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */class Report extends CI_Controller
 {
     
     function __construct() {
         parent::__construct();
         //Check if user logged in
        if($this->session->userdata('logged_in') === FALSE)
        {
            redirect();
        }

        $this->load->model('Student_model','Student');
        $this->load->model('School_setup_model','School_setup');
        $this->load->model('Result_model','Result');
        $this->load->model("Reports_model", "Reports");
        $this->load->model('classes_model','Classes');
        $this->load->model("SubjectSettings_model", "SubjectSettings");
     }

     function get_result_data($student_id, $session_id, $term_id){

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

         $page_data['is_cumulative'] = ($term_id == "all" || $term_id == 0) ? true : false;
         $page_data['student'] = $student;
         $page_data['class'] = $class;
         $page_data['subject_scores'] = $subject_scores;
         $page_data['student_report_overview'] = $student_report_overview;
         $page_data['class_report_overview'] = $class_report_overview;
         $page_data['cog_skills_report'] = $this->Reports->get_cog_skills_report($student_id,$session_id,$term_id);
         $page_data['result_info'] = $result_info;
         $page_data['school_info'] = $school_info;
         $page_data['result_display'] = $this->Result->get_student_data_display();

         $is_cumulative = $term_id == "all" || $term_id == 0 ? true : false;

         $page_data['is_cumulative'] = $is_cumulative;

         if($is_cumulative){
             $page_data = array_merge($page_data, $page_data['subject_scores']);
         }
         return $page_data;
     }


     function print_report($student_id, $session_id, $term_id){

         $page_data = $this->get_result_data($student_id, $session_id, $term_id);
         $class = $this->Student->get_class_for_student($student_id, $session_id);

         $report_page = strtolower($class['report_sheet_view']);
         $this->load->view('results/header_content',$page_data);
         $this->load->view("results/sheets/{$report_page}");
        
        /*
        //$html = $this->load->view("results/sheets/test", $page_data, true);

        $this->load->library("DompdfLib", null, "PDFLibrary");

        $this->PDFLibrary->load_html($html);
        if($this->PDFLibrary->convert()){
            $this->PDFLibrary->output($student['student_name']);
        }
        */

     }

     //Deprecated Method...
     function print_report_depr($student_id = NULL,$session_id = NULL,$term_id = NULL)
     {
        $page_data = array();       

        $student = $this->Student->get_student($student_id, $session_id);

        if($student != null){
            $page_data = $this->view_stud_record($student_id,$session_id,$term_id);

            $page_data = array_merge($page_data,$this->School_setup->get_school_settings());
            $page_data['result_display'] = $this->Result->get_student_data_display();

         }
         else {
            $page_data['msg'] = error_msg("Student with id {$student_id} not found!");
         }
         
         $page_data['title'] = "Report | ". $page_data['biodata']['surname'] . " " . $page_data['biodata']['first_name'] . " " .$page_data['biodata']['middle_name'];
         $this->load->view('results/header_content',$page_data);
         $this->load->view('results/print_result');
     } 
     

     
     function view_stud_record($student_id = NULL,$session_id,$term_id) {
        $data = $this->input->post();
        $page_data = array();
        
        $student_id = ($student_id != NULL) ? $student_id : $data['student_id'];
        $class = $this->Student->get_class_for_student($student_id, $session_id);

        $class_id = $class['class_id'];
        
        $page_data['student_id'] = $student_id;
        $page_data['biodata'] = $this->Student->get_biodata($student_id);
        $page_data['biodata']['class'] = $class['class_name'];
        
        $page_data['result_session'] = $this->School_setup->get_session_name($session_id);
        $page_data['result_term'] = $this->School_setup->get_term_name($term_id);
        
        if($term_id == "all")
        {
            $school_terms = $this->School_setup->get_school_terms();
            $page_data['student_result'] = $this->Result->get_student_session_result($student_id,$session_id,$school_terms,$class_id); 
        }
         else { 
            $page_data['student_result'] = $this->Result->get_student_result($student_id,$session_id,$term_id, $class_id);
        }

        if(sizeof($page_data['student_result']) > 0)
        {
            $page_data['student_result_summary'] = $this->Result->get_student_result_summary($student_id,$class_id,$session_id,$term_id);
            $page_data['class_result_summary'] = $this->Result->get_class_result_summary($class_id,$session_id,$page_data['student_result_summary']['total_score'],$term_id);
            $page_data['class_result_summary']['class_size'] = $this->Classes->get_class_size($class_id, $session_id);
        }
        if($term_id == "all")
        {
            $page_data['result_table'] = "all_terms";
        }
        $page_data['cog_skills_result'] = $this->Result->get_cog_skills_result($student_id,$session_id,$term_id); 
        return $page_data;
    }
    
    
    function print_(){
        $page_data = array();
        $page_data = array_merge($page_data,$this->School_setup->get_school_settings());
        $this->load->view('results/header_content',$page_data);
        $this->load->view('print_template/template');
        $this->load->view('results/footer_content');
    }


    function class_report($session_id, $term_id, $class_id){
        $page_data = $this->Report->get_class_students_report($session_id, $term_id, $class_id);
        $this->load->view('results/header_content',$page_data);
        $this->load->view("results/sheets/class_report");
    }
 }
