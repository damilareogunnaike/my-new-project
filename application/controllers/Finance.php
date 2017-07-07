<?php

//Get Base Class School which extends CI_Controller
include_once 'school.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Finance extends School_MS { 
    
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
            if($this->session->userdata('logged_in_role') !== "finance")
            {
                redirect();
            }
        }
        $this->load->model('student_model','Student');
        $this->load->model('finance_model','Finance');
    }
    
    public function index($page = "dashboard",$args =array(),$view_folder = "finance")
    {
        $page_data = ['title'=>'Finance'];
        $page_data = array_merge($page_data,$args);
        $page_data['page'] = (title_text($page));
        $page_data['curr_session_id'] = $this->curr_session_id;
         if(file_exists(APPPATH ."/views/{$view_folder}/{$page}.php"))
        {
             if($page == "dashboard")
             {
                 $page_data = array_merge($page_data,$this->Finance->get_finance_overview($this->curr_session_id,$this->curr_term_id));
             }
              $page_data['form_controller'] = "finance";
             $page_data['page_content'] = $this->load->view($view_folder. '/'.$page,$page_data,TRUE);
        }
        else {  $page_data['page_content'] = info_msg("Page under serious construction. Please chill.."); }
        $this->load->view('header',$page_data);
        $this->load->view("finance/index");
        $this->load->view('footer');
    }

    
    function p($page,$args = array())
    {
        $page_data = array();
        switch ($page)
        {
            case "fees_config":
                $page_data['payment_purposes'] = $this->Finance->get_fees_purposes();
                $page_data['classes'] = $this->School_setup->get_all_classes();
                break;
            case "payment_history":
                $page_data['school_sessions'] = $this->School_setup->get_school_sessions();
                $page_data['school_terms'] = $this->School_setup->get_school_terms();
                break;
            case "cash_payment":
                $page_data['payments_made'] = $this->Finance->get_cash_payments();
                break;
            case "unpaid_fees":
                $page_data['fees_payment_data'] = $this->Finance->get_students_payment_data($this->curr_session_id,$this->curr_term_id);
                break;
            case "pay_fees":
                    $page_data['students'] = $this->Student->get_all_students();
                    break;

        }

        $page_data['payment_purposes'] = $this->Finance->get_fees_purposes();
        $page_data = array_merge($page_data,$args);
        $this->index($page,$page_data);

    }

    

    

        /* Finance Configuration */

    

    function add_payment_purpose()

    {

        $data = $this->input->post();

        $saved = $this->Finance->add_payment_purpose($data);

        $msg = ($saved) ? success_msg("Saved..") : warning_msg("Not Saved. Retry..");

        $this->p('fees_config',array('msg'=>$msg));

    }
    
    
    function del_payment_purpose($purpose_id){
        $deleted = $this->Finance->del_payment_purpose($purpose_id);
        $msg = ($deleted) ? success_msg("Deleted..") : warning_msg("Not deleted. Retry..");
        $this->p('fees_config',array('msg'=>$msg));
    }

    

    

    function set_fees()

    {

        $msg = "";

        $data = $this->input->post();

        $this->load->library('form_validation');

        $this->form_validation->set_rules('amount','Amount','numeric');

        

        if($this->form_validation->run() === FALSE)

        {

            $msg = warning_msg(validation_errors());

        }

        else {

            $saved = $this->Finance->set_fees($data);

            $msg = ($saved) ? success_msg("Fees set successfully..") : error_msg("Not Set. Confirm duplicate entry");

        }

        $this->p('fees_config',array('active_tab'=>'set_fees','msg'=>$msg));

    }

    

    

    function view_fees()

    {

        $data = $this->input->post();

        $fees_settings = $this->Finance->get_fees_settings($data);

        $msg = ($fees_settings == NULL) ? info_msg("No settings found for selected parameters") : '';

        $this->session->set_userdata('last_search_data',$data);

        $this->p('fees_config',array('active_tab'=>'view_fees','msg'=>$msg,'fees_settings'=>$fees_settings));

    }

    

    function del_fees_settings($settings_id = NULL)

    {

        $msg = "";

        if($settings_id != NULL)

        {

            $deleted = $this->Finance->del_fees_settings($settings_id);

            $msg = ($deleted) ? success_msg("Settings deleted...") : warning_msg("Not deleted. check Well..");

        }

        $last_search_data = $this->session->userdata('last_search_data');

        $fees_settings = ($last_search_data != NULL) ? $this->Finance->get_fees_settings($last_search_data) : NULL;

        $this->p('fees_config',array('active_tab'=>'view_fees','msg'=>$msg,'fees_settings'=>$fees_settings));

    }

    

    

    function get_pending_fees()

    {

        $data = array();

        $student_id = $this->input->post('student_id');

        if($this->Student->confirm_valid_student($student_id)) {

            $data['student_id'] = $student_id;

            $student_class_id = $this->Student->get_class_id($student_id);

            $data['required_fees'] = $this->Finance->get_required_fees($student_class_id,$this->curr_session,$this->curr_term);

            $data['paid_fees'] = $this->Finance->get_paid_fees($student_id,$this->curr_session_id,$this->curr_term_id);

            $data['msg'] = sizeof($data['required_fees']) > 0 ? "" : info_msg("No fees set for this student's class!");

        }

        else {

            $data['msg'] = warning_msg("Invalid Student ID");

        }

        $this->p('pay_fees', $data);

    }

    

    

    function receive_payment()

    {

        $student_id = $this->input->post('student_id');

        $amount_paid = $this->input->post('amount_paid');

        $data = $this->input->post();

        $student_confirmed = $this->Student->confirm_valid_student($student_id);

        if($student_confirmed) {

            if((int)$amount_paid > 0) {

                $paid_status = $this->Finance->pay_fees($data,$this->curr_session_id,$this->curr_term_id);

                $data['msg'] = $paid_status['msg'];

                $data['print_receipt'] = $paid_status['status'];

            }

            else { $data['msg'] = warning_msg("Amount should be greater than 0"); }

        }

        else { $data['msg'] = warning_msg("Invalid Student ID"); }

        $student_class_id = $this->Student->get_class_id($student_id);

        $data['student_id'] = $student_id;

        $data['required_fees'] = $this->Finance->get_required_fees($student_class_id,$this->curr_session,$this->curr_term);

        $data['paid_fees'] = $this->Finance->get_paid_fees($student_id,$this->curr_session_id,$this->curr_term_id);

        $this->p('pay_fees',$data);

    }
    
    
    function get_payments_made($student_id = null, $args = array()){
        $student_id = ($this->input->post('student_id') != false) ? $this->input->post('student_id') : $student_id;
        $page_data = array();
        if($this->Student->confirm_valid_student($student_id)){
            $page_data['payments_made'] = $this->Finance->get_payments_made($student_id,$this->curr_session_id,$this->curr_term_id);
            $page_data['msg'] = ($page_data['payments_made'] === NULL) ? warning_msg("No records found!!") : "";
            $page_data['student_details'] = $this->Student->get_biodata($student_id);
            $page_data['student_id'] = $student_id;
        }
        else {
            $page_data['msg'] = warning_msg("Invalid Student ID");
        }
        $page_data['active_tab'] = "payment_receipt";
        $page_data = array_merge($page_data,$args);
        $this->p('pay_fees',$page_data);
    }
    
    
    function del_payment($student_id,$record_id){
        $msg = $this->Finance->delete_payment_record($record_id);
        $page_data['msg'] = $msg;
        $page_data['active_tab'] = "payment_receipt";
        $this->get_payments_made($student_id,$page_data);
    }
    

    function print_receipt($student_id){
        if($this->Student->confirm_valid_student($student_id)){
            $page_data['payments_made'] = $this->Finance->get_payments_made($student_id,$this->curr_session_id,$this->curr_term_id);
            $page_data['data_not_found'] = ($page_data['payments_made'] === NULL) ? FALSE : TRUE;
            $page_data['student_details'] = $this->Student->get_biodata($student_id);
            $page_data['student_id'] = $student_id;
            $page_data = array_merge($page_data,$this->School_setup->get_school_settings());
        }
        else {
            $page_data['msg'] = warning_msg("Invalid Student ID");
        }
        $this->load->view('header_files',$page_data);
        $this->load->view('finance/print_payment_receipt');
    }
    

    function view_payment_history(){
        $data = $this->input->post();
        $page_data = array();
        if(sizeof($data) > 1) { 
        $page_data['history'] = $this->Finance->get_payment_history($data); 
        }
        $this->p('payment_history',$page_data);
    }
    
    
    function add_cash_payment(){
        $data = $this->input->post();
        $page_data = array();
        if(sizeof($data) > 1 ) { 
            $saved = $this->Finance->add_cash_payment($data);
            $page_data['msg'] = ($saved) ? success_msg("Succesful...") : warning_msg("Error occured....");
            $page_data = array_merge($page_data,$data);
            $page_data['insert_id'] = $this->db->insert_id();
            $page_data['print_receipt'] = TRUE;
            $this->session->set_userdata("last_insert",$data);
        }
        $this->p('cash_payment',$page_data);
    }
  

    function print_cash_receipt($insert_id){
        $data = $this->Finance->get_last_cash_payment($insert_id);
        $page_data = $data;
        $page_data = array_merge($page_data,$this->School_setup->get_school_settings());
        $this->load->view('header_files',$page_data);
        $this->load->view("finance/print_cash_payment_receipt");
    }
    

    function del_cash_payment($cash_payment_id = NULL){
        $page_data = array();
        if($cash_payment_id != NULL) {
            $deleted = $this->Finance->delete_cash_payment($cash_payment_id);
            $page_data['msg'] = $deleted ? success_msg("Record deleted") : warning_msg("Record not deleted.");
        }
        $page_data['active_tab'] = "payments_made";
        $this->p('cash_payment',$page_data);
    }
}
