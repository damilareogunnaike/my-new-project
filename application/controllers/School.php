<?php


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
   abstract class School_MS extends CI_Controller {
    
    protected $curr_session = NULL;
    protected $curr_session_id = NULL;
    protected $curr_term = NULL;
    protected $curr_term_id = NULL; 
	
    private $user_id = NULL;
    private $admin_username = NULL;
    
    
    function __construct(){
        parent::__construct();
    
        $this->load->model('result_model','Result');
        $this->load->model('school_setup_model','School_setup');
        $this->load->model('student_model','Student');
        $this->load->model('finance_model','Finance');
        $this->load->model("user_model","Users");
        $this->load->model("messages_model","Messages");
		
        $this->curr_session_id = $this->School_setup->get_current_session_id();
        $this->curr_term_id = $this->School_setup->get_current_term_id();
        $this->curr_session = $this->School_setup->get_session_name($this->curr_session_id);
        $this->curr_term = $this->School_setup->get_term_name($this->curr_term_id); 
		
        $this->user_id = $this->session->userdata("id");
        $this->admin_username = $this->session->userdata("username");
    }
    
    /*
     * Must be implemented by all sub class.
     * Presents an entry point when controller is called
     */
    abstract public function index();
            
    
    function view_stud_record($student_id = NULL,$args = array()) {
		
        $data = $this->input->post();
        $page_data = array();
		$student_id = ($student_id != NULL) ? $student_id : $data['student_id'];
        
        if(isset($data['student_id'])) { 
			$page_data['active_tab'] = "reports";
		}
		if(!$this->Student->is_valid_student($student_id)){
			$page_data['biodata'] = NULL;
			$this->index('student_record',$page_data,'student');
			return NULL;
		}
		
        $data['session_id'] = isset($data['session_id']) ? $data['session_id'] : $this->curr_session_id;
        $data['term_id'] = isset($data['term_id']) ? $data['term_id'] : $this->curr_term_id;

        $class_id = $this->Student->get_class_id($student_id);
        
        $page_data['admin'] = TRUE;
        $page_data['student_id'] = $student_id;
        $page_data['curr_session_id'] = $this->curr_session_id;
        $page_data['curr_term_id'] = $this->curr_term_id;
        $page_data['classes'] = $this->School_setup->get_all_classes();
        $page_data['biodata'] = $this->Student->get_biodata($student_id);
        $page_data['biodata']['class'] = $this->School_setup->get_class_name($page_data['biodata']['class_id']);
        $page_data['school_sessions'] = $this->School_setup->get_school_sessions();
        $page_data['school_terms'] = $this->School_setup->get_school_terms();
        
        $page_data['result_session'] = $this->School_setup->get_session_name($data['session_id']);
        $page_data['result_term'] = $this->School_setup->get_term_name($data['term_id']);
        
        if($data['term_id'] == "all"){
            $page_data['student_result'] = $this->Result->get_student_session_result($student_id,$data['session_id'],$page_data['school_terms'],$class_id); 
        }
         else { 
             $page_data['student_result'] = $this->Result->get_student_result($student_id,$data['session_id'],$data['term_id']); 
             }
        
        if(sizeof($page_data['student_result']) > 0){
            $page_data['student_result_summary'] = $this->Result->get_student_result_summary($student_id,$class_id,$data['session_id'],$data['term_id']);
            $page_data['class_result_summary'] = $this->Result->get_class_result_summary($class_id,$data['session_id'],$page_data['student_result_summary']['total_score'],$data['term_id']);
        }
        if($data['term_id'] == "all")
        {
            $page_data['result_table'] = "all_terms";
        }
        $page_data['result_display'] = $this->Result->get_student_data_display();
        $page_data['payment_history'] = $this->Finance->get_payment_history(array("session_id"=>$this->curr_session_id,
           "term_id"=>$this->curr_term_id,"student_id"=>$student_id));
        $page_data['print_url'] = "$student_id/{$data['session_id']}/{$data['term_id']}";
        $page_data = array_merge($page_data,$args);
        $this->index('student_record',$page_data,'student');
    }
    
    
    function form_submitted($data = NULL){
		$data = ($data != NULL) ? $data : $this->input->post();
		return (sizeof($data) > 0) ? TRUE : FALSE;
    }
	
	
	/* Assists the administrator in changing password */
	function change_password(){
		$form_data = $this->input->post();
		$data = array();
		if($this->form_submitted($form_data)){
			
			$form_data['old_password'] = md5($form_data['old_password']);
			if($this->Users->is_valid_password($this->admin_username,$form_data['old_password']))
			{	
				if($form_data['new_password'] == $form_data['confirm_password']){
					$data['msg'] = $this->Users->update_password($this->admin_username,md5($form_data['new_password']));
				}
				else {
					$data['msg'] = error_msg("New Password and Confirm Password do not match. Retry.");
				}
			}
			else {
				$data['msg'] = error_msg("Invalid password. Try again.");
			}
		}
		$this->index("change_password",$data,"admin");
	}
}
