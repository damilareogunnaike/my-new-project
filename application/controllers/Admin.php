<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once 'School.php';


class Admin extends School_MS
{   
    
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
            if($this->session->userdata('logged_in_role') !== "admin")
            {
                redirect();
            }
        }
        
        $this->load->model('admin_model','Admin');
        $this->load->model('parent_model','Parent');
        $this->load->model('finance_model','Finance');
        $this->load->model('staff_model','Staff'); 
		$this->load->library("myapp");
    }
    
    
    function index($page = "dashboard",$args =array(),$view_folder = "admin")
    {
        $page_data = ['title'=>'Admin'];
        $page_data = array_merge($page_data,$args);
        $page_data['page'] = (title_text($page));
         if(file_exists(APPPATH ."/views/{$view_folder}/{$page}.php"))
        {
             if($page == "dashboard")
             {
                 $page_data = array_merge($page_data,$this->get_admin_overview());
             }
             $page_data['form_controller'] = "admin";
             $page_data['page_content'] = $this->load->view($view_folder. '/'.$page,$page_data,TRUE);
        }
        else {  $page_data['page_content'] = info_msg("Page under serious construction. Please chill.."); }
        $this->load->view('header',$page_data);
        $this->load->view("admin/index");
        $this->load->view('footer');
    }
    
    
    //redirect admin pages
    function p($page,$args = array())
    {
        $page_data = array();
        switch ($page)
        {
            case "add_student":
				$page_data['states'] = $this->School_setup->get_all_states();
                $page_data['classes'] = $this->School_setup->get_all_classes();
                break;
            case "staff":
				$page_data['states'] = $this->School_setup->get_all_states();
                $page_data['staffs'] = $this->Staff->get_all_staff();
                $page_data['next_staff_id'] = $this->Staff->get_next_staff_id();
                break;
            case "assign_class_teacher":
                $page_data['staffs'] = $this->Staff->get_all_staff();
                $page_data['classes'] = $this->School_setup->get_all_classes();
                break;
            case "assign_subject_teacher":
                $page_data['staffs'] = $this->Staff->get_all_staff();
                $page_data['subjects'] = $this->School_setup->get_all_subjects();
            case "student_report":
                $page_data['classes'] = $this->School_setup->get_all_classes();
                break;            
            case "manage_parent_users":
                $page_data['next_parent_id'] = $this->Parent->get_next_insert_id();				
				$page_data['parent_users'] = $this->Parent->get_all_parents();
                case "view_student":
                $page_data['students'] = $this->Student->get_all_students();
                break;
            case "fees_config":
                $page_data['payment_purposes'] = $this->Finance->get_fees_purposes();
                $page_data['classes'] = $this->School_setup->get_all_classes();
                break;
            case "manage_term":
                $page_data['school_sessions'] = $this->School_setup->get_school_sessions();
                $page_data['school_terms'] = $this->School_setup->get_school_terms();
                $page_data['current_session'] = $this->curr_session;
                $page_data['current_term'] = $this->curr_term;
                break;
            case "school_setting":
                $page_data['school_settings'] = $this->School_setup->get_school_settings();
                break;
            case "report_settings":
                $page_data['cognitive_skills'] = $this->Result->get_skills();
                $page_data['gradings'] = $this->Result->get_gradings();
               // $page_data['result_display'] = $this->Result->get_result_display();
                $page_data['student_display'] = $this->Result->get_student_data_display();
                break;
            case "change_class":
                $page_data['classes'] = $this->School_setup->get_all_classes();
                $page_data['uses_angular'] = true;
                break;
            case "administrators":
                $page_data['admins'] = $this->Admin->get_all_admins();
                break;
            case "principal_comments":
                $page_data['p_comments'] = $this->Result->get_principal_comments();
                break;
            case "result_pins":
                $page_data['uses_angular'] = true;
                break;
            case "student_parent":
                $page_data['students'] = $this->Student->get_all_students();
                $page_data['parents'] = $this->Parent->get_all_parents();
                break;
        }
        $page_data = array_merge($page_data,$args);
        $this->index($page,$page_data);
    }
    
    /* Manage Staff */
    function add_staff()
    {
        $data = $this->input->post();
        if(isset($data['surname']))
        {
            $saved = $this->Staff->add_staff($data);
            $msg = ($saved) ? success_msg("Staff Saved") : error_msg("Error occured");
        }
        else { $msg = error_msg("Error... Retry!!"); }
        $this->p('staff',array('msg'=>$msg));
    }
    
    
    
    function del_staff($username){
        $status = $this->Staff->del_staff($username);
        if($status['deleted']){
            //Delete staff from login table
            $this->load->model("user_model","User");
            $this->User->delete_login($username);
			$page_data['msg'] =  success_msg("Staff Deleted..");
        }
		else {
			$page_data['msg'] = $status['msg'];
		}
        $this->p('staff',$page_data);
    }
	
	/* Gets a staff for viewing. */
	function view_staff($staff_id,$args = array()){
		$page_data['staff_info'] = $this->Staff->get_staff_info($staff_id);
		$page_data['subjects'] = $this->Staff->get_my_subjects($staff_id);
		$page_data['classes'] = $this->Staff->get_my_classes($staff_id);
		$page_data['states'] = $this->School_setup->get_all_states();
		$page_data = array_merge($page_data,$args);
		$this->p("staff_details",$page_data);
	}
    
	
	function update_staff_info(){
		$form_data = $this->input->post();
		$msg = $this->Staff->update_details($form_data['staff_id'],$form_data);
		$page_data['msg'] = $msg;
		$this->session->set_flashdata("msg",$msg);
		redirect("admin/view_staff/".$form_data['staff_id']);
	}
    
    
    function assign_class_teacher()
    {
        $data = $this->input->post();
        if(isset($data['class_id']))
        {
            $saved = $this->School_setup->save_class_teacher($data);
            $msg = ($saved) ? success_msg("Class Teacher Assigned...") : error_msg("Error. Retry");
        }
        else $msg = error_msg ("Form not submitted");
        $this->p('assign_class_teacher',array('msg'=>$msg));
    }
    
    
    function view_class_teacher()
    {
        $data = $this->input->post();
        $page_data = array();
        if(isset($data['view_class']))
        {
            $page_data['class_teacher'] = $this->School_setup->get_teacher_class($data['staff_id']);
            $page_data['query_sent'] = TRUE;
        }
        else if(isset($data['view_teacher']))
        {
            $page_data['class_teacher'] = $this->School_setup->get_class_teacher($data['class_id']);
            $page_data['query_sent'] = TRUE;
        }
        else $page_data['msg'] = warning_msg ("No data selected...");
        $page_data['active_tab'] = "view_teacher";
        $this->p('assign_class_teacher',$page_data);
    }
    
    
    /* Manage Subjects */
    function assign_subject_teacher()
    {
        $data = $this->input->post();
        if(isset($data['subject_id']))
        {
            $saved = $this->School_setup->save_subject_teacher($data);
            $msg = ($saved) ? success_msg("Subject Teacher Assigned...") : error_msg("Error. Retry");
        }
        else $msg = error_msg ("Form not submitted");
        $this->p('assign_subject_teacher',array('msg'=>$msg));
    }
    
    
    function view_subject_teacher($v_data = NULL,$v_page_data = array())
    {
        $data = ($v_data != NULL) ? $v_data : $this->input->post();
        $page_data = array();
        if(isset($data['view_class']))
        {
            $page_data['subject_teacher'] = $this->School_setup->get_teacher_subject($data['staff_id']);
            $page_data['query_sent'] = TRUE;
        }
        else if(isset($data['view_teacher']))
        {
            $page_data['subject_teacher'] = $this->School_setup->get_subject_teacher($data['subject_id']);
            $page_data['query_sent'] = TRUE;
        }
        else if(isset($data['view_class_subject_teacher']))
        {
            $page_data['subject_teacher'] = $this->School_setup->get_class_subject_teacher($data['class_id']);
            $page_data['query_sent'] = TRUE;
        }
        else $page_data['msg'] = warning_msg ("No data selected...");
        $page_data['active_tab'] = "view_teacher";
        $page_data = array_merge($page_data,$v_page_data);
        $this->p('assign_subject_teacher',$page_data);
    }
	

    function remove_subject_teacher($subject_id,$staff_id,$class_id){
        $msg = $this->School_setup->remove_subject_teacher($subject_id,$class_id,$staff_id);
        $this->view_subject_teacher(array("view_class_subject_teacher"=>true,"class_id"=>$class_id),array("msg"=>$msg));
    }
	
	
	function remove_teacher($category = NULL,$class_id = NULL,$staff_id = NULL,$subject_id = NULL){
		if(($category == "subject" && $subject_id == NULL) || $class_id == NULL || $staff_id == NULL || $category == NULL){
			$msg = info_msg("Invalid arguments supplied. Check again!");
			return $this->index();
		}
		else {
			if($category == "subject"){
				$msg = $this->School_setup->remove_subject_teacher($subject_id,$class_id,$staff_id);
			}
			else {
				$msg = $this->Staff->remove_class_teacher($class_id,$staff_id);
			}
			$prev_page = $this->input->get("prev_page");
			$prev_page = urldecode($prev_page); 
			$this->session->set_flashdata("active_tab","academic_info");
			$this->session->set_flashdata("msg",$msg);
			redirect($prev_page);
		}
	}
    
    /* Manage Students */
    function add_student()
    {
        $data = $this->input->post();
        $upload_file = $this->upload_file('file','uploads/student/','jpg|png');
        if($upload_file['uploaded'] == TRUE) { $data['image'] = $upload_file['file_path']; }
        $saved = $this->Student->add_student($data, $this->curr_session_id);
        $msg = ($saved) ? success_msg("Student Added..") : error_msg("Student not added");
        $this->p('add_student',array('msg'=>$msg));
    }
    
    
    function del_student($id)
    {
        $deleted = $this->Student->del_student($id);
        $msg = ($deleted) ? success_msg("Student deleted..") : error_msg("Operation not successful. Retry!");
        $this->p('view_student',array('msg'=>$msg));
    }  
       
    
    function update_student_record()
    {
        $data = $this->input->post();
        $page_data = array();
        $page_data['msg'] = "";
        $upload_file = $this->upload_file('file','uploads/student/','jpg|png');
        if($upload_file['uploaded'] == TRUE) { $data['image'] = $upload_file['file_path']; }
        //else { $page_data['msg'] = info_msg($upload_file['msg']); }
        $student_id = $data['student_id'];
        unset($data['student_id']);
        $updated = $this->Student->update_student_record($student_id,$data);
        $page_data['msg'] .= ($updated) ? success_msg("Student record updated..") : info_msg("Student record not updated");
        $page_data['active_tab'] = NULL;
        $this->view_stud_record($student_id,$page_data);
    }
    
    
     function upload_file($field_name,$upload_path,$allowed_types)
    {
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = $allowed_types;
        $this->load->library('upload',$config);
        
        if(!$this->upload->do_upload($field_name))
        {
            $status['uploaded'] = FALSE;
            $status['msg'] = $this->upload->display_errors();
            return $status;
        }
        else 
        {
            $status['uploaded'] = TRUE;
            $upload_data = $this->upload->data();
            $status['file_path'] = $config['upload_path'] . $upload_data['file_name'];
        }
        return $status;
    }
    
    
    /* Session and Term Settings */
    function add_school_session()
    {
        $data = $this->input->post();
        $saved = $this->School_setup->add_school_session($data);
        $msg = ($saved) ? success_msg("Session Saved..") : warning_msg("Not saved. Retry..");
        $this->p('manage_term',array('msg'=>$msg,'active_tab'=>'school_session'));
    }
    
    function add_school_term()
    {
        $data = $this->input->post();
        $saved = $this->School_setup->add_school_term($data);
        $msg = ($saved) ? success_msg("Term Saved..") : warning_msg("Not saved. Retry..");
        $this->p('manage_term',array('msg'=>$msg,'active_tab'=>'school_session'));
    }
    
    
    function set_current_session()
    {
        $data = $this->input->post();
        $saved = $this->School_setup->set_current_session($data);
        $msg = ($saved) ? success_msg("Current Session Set..") : warning_msg("Not set. Retry..");
        $this->p('manage_term',array('msg'=>$msg));
    }
    
    function set_current_term()
    {
        $data = $this->input->post();
        $saved = $this->School_setup->set_current_term($data);
        $msg = ($saved) ? success_msg("Current Term Set..") : warning_msg("Not set. Retry..");
        $this->p('manage_term',array('msg'=>$msg));
    }
    
    
    /* Parent Users Adding */
    function add_parent_user()
    {
        $page_data = array();
        $data = $this->input->post();
        $this->load->model('parent_model','Parent');
        $saved = $this->Parent->add_parent_user($data);
		$page_data['active_tab'] = "add_parent_user";
        $page_data['msg'] = ($saved) ? success_msg("Parent user saved. "
                . "Username/Password is <strong>{$data['parent_user_pass']}</strong>"
                . "") : warning_msg("Not successful. Retry");
        $this->p('manage_parent_users',$page_data);
    }
	
	
	function view_parent($parent_id,$args = array()){
		$this->load->model("parent_model","Parent");
		$page_data['parent_info'] = $this->Parent->get_parent($parent_id);
		$page_data['wards'] = $this->Parent->get_wards($parent_id);
		$page_data['parent_id'] = $parent_id;
		$page_data = array_merge($page_data,$args);
		$this->index("parent_details",$page_data);
	}
    
	
	function update_parent_info(){
		$form_data = $this->input->post();
		$msg = $this->Parent->update_info($form_data['parent_id'],$form_data);
		$page_data['msg'] = $msg;
		$this->session->set_flashdata("msg",$msg);
		redirect("admin/view_parent/".$form_data['parent_id']);
	}
	
	
	function delete_parent($parent_id){
		$page_data['msg'] = $this->Parent->delete_parent($parent_id);
		$this->p("manage_parent_users",$page_data);
	}
    
    function get_admin_overview()
    {
        $data = array();
        $data['total_staffs'] = $this->Staff->get_staff_count();
        $data['total_students'] = $this->Student->get_students_count();
        $data['total_male_students'] = $this->Student->get_male_students_count();
        $data['total_female_students'] = $this->Student->get_female_students_count();
        $data['total_classes'] = $this->School_setup->get_class_count();
        $data['total_subjects'] = $this->School_setup->get_subject_count();
        $data['total_parents'] = $this->Parent->get_parent_count();
        return $data;
    }
    
    
    function school_setting()
    {
        $data = $this->input->post();
        $page_data = array();
        if(sizeof($data) > 0 )
        {
            $upload_file = $this->upload_file('school_logo', "uploads/", "gif|png|jpg");
            if($upload_file['uploaded'] == TRUE)
            {
                $data['school_logo'] = $upload_file['file_path'];
            }
            $saved = $this->School_setup->save_school_settings($data);
            $page_data['msg'] = ($saved) ? success_msg("Settings Saved") : warning_msg("Setting not saved. Error occured!");
        }
        $this->p('school_setting',$page_data);
    }
    
    
    function class_students()
    {
        $data = $this->input->post();
        $session_id = isset($data['session_id']) ? $data['session_id'] : $this->curr_session_id;
        $term_id = isset($data['term_id']) ? $data['term_id'] : $this->curr_term_id;
            
        $this->load->model("Reports_model", "Reports");

        if(isset($data['class_id'])){ // Form Submitted
            $class_id = $data['class_id'];
            $class_students_result = $this->Reports->get_class_students_report($session_id, $term_id, $class_id);
            $page_data['msg'] = ($class_students_result != NULL) ? success_msg("Showing " . sizeof($class_students_result) . ""
           . " result(s).") : info_msg("No student found for this class");
            $page_data['students_results'] = $class_students_result;
            $page_data['class_name'] = $this->School_setup->get_class_name($class_id);
            $page_data['session_name'] = $this->School_setup->get_session_name($session_id);
            $page_data['term_name'] = $this->School_setup->get_term_name($term_id);
            $page_data['class_id'] = $class_id;
            $page_data['term_id'] = $term_id;
        }
        
       $page_data['curr_session_id'] = $session_id;
       $page_data['curr_term_id'] = $term_id;
       $page_data['school_sessions'] = $this->School_setup->get_school_sessions();
       $page_data['school_terms'] = $this->School_setup->get_school_terms();
       $this->p('student_report',$page_data);
    }
    
    
    function add_skill()
    {
        $data = $this->input->post();
        $saved = $this->Result->save_skill($data['skill']);
        $page_data['msg'] = ($saved) ? success_msg("Skill Saved...") : info_msg("Not saved. Retry!!");
        $this->p('report_settings',$page_data);
    }
   
    
    function del_skill($record_id){
        $deleted = $this->Result->delete_skill($record_id);
        $page_data['msg'] = ($deleted) ? success_msg("Record deleted..") : error_msg("Record not deleted. Retry..");
        $this->p('report_settings',$page_data);
    }
    
    
    function add_grading()
    {
        $data = $this->input->post();
        $saved = $this->Result->add_grading($data);
        $page_data['msg'] = ($saved) ? success_msg("Record added") : info_msg("Record not added..");
        $this->p('report_settings',$page_data);
    }
    
    function del_grade($record_id){
        $deleted = $this->Result->delete_grade($record_id);
        $page_data['msg'] = ($deleted) ? success_msg("Record deleted..") : error_msg("Record not deleted. Retry..");
        $this->p('report_settings',$page_data);
    }
    
    
    function save_student_display_settings(){
        $data = $this->input->post();
        $saved = $this->Result->save_student_display($data['student_display']);
        $page_data['msg'] = ($saved) ? success_msg("Record saved..") : info_msg("Not saved. Retry..");
        $this->p('report_settings',$page_data);
    }
    
    
    
    /*
     * Gets all students in a class. Retrieves data from $_POST array
     */
    function get_class_students(){
        $page_data = array();
        if($this->form_submitted()) {
            $data = $this->input->post();
            $class_id = $data['class_id'];
            $page_data['school_session_id'] = $school_session_id = $data['school_session_id'];
            $page_data['class_id'] = $class_id;
            $page_data['class_students'] = $this->School_setup->get_class_students($class_id, $school_session_id);
        }
        $this->p('change_class',$page_data);
    }
    
    /**
     * Calls School Setup function to change students' class
     */
    function change_class(){
        $page_data = array();
        $data = $this->input->post();
        if($this->form_submitted()){
            $school_session_id = $data['school_session_id'];
            $updated_record = $this->School_setup->change_class($data['student_ids'],$data['new_class_id'],$school_session_id);
            $page_data['class_id'] = $data['new_class_id'];
            $page_data['class_students'] = $this->School_setup->get_class_students($data['new_class_id'],$school_session_id);
            $page_data['msg'] = ($updated_record != FALSE) ? success_msg("Updated " . $updated_record . " record(s)") : info_msg("No record updated.");
        }
        $this->p('change_class',$page_data);
    }
    
    /**
     * Adds new admin to DB
     */
    function add_admin(){
        $page_data = $this->input->post();
        if($this->form_submitted()){
            $data = $this->input->post();
            $saved = $this->Admin->add_admin($data);
            $page_data['msg'] = ($saved) ? success_msg("Admin saved.") : info_msg("Admin not saved. Retry.");
        }
        $this->p('administrators',$page_data);
    }
    
    
    /**
     * Calls function to delete administrator from database
     * @param int $admin_id ID of record to be deleted
     */
    function del_admin($admin_id = NULL){
        $page_data = array();
        if($admin_id != NULL) {
            $deleted = $this->Admin->del_admin($admin_id);
            $page_data['msg'] = ($deleted) ? success_msg("Admin deleted...") : info_msg("Not deleted. Retry..");
        }
        $this->p('administrators',$page_data);
    }
    
    
    /**
     * Calls function to save principals comment
     */
    function add_p_comment(){
        $data = $this->input->post();
        if($this->form_submitted()){
            $saved = $this->Result->save_principals_comment($data);
            $page_data['msg'] = ($saved) ? success_msg("Comment Saved...") : info_msg("Comment not saved..");
        }
        $this->p('principal_comments',$page_data);
    }
    
    
    function del_p_comment($comment_id = NULL){
        $page_data = array();
        if($comment_id != NULL){
            $deleted = $this->Result->delete_principals_comment($comment_id);
            $page_data['msg'] = ($deleted) ? success_msg("Comment deleted..") : warning_msg("Not deleted. Retry");
        }
        $this->p('principal_comments',$page_data);
    }
    
    
    function generate_pin(){
        $length = $this->input->post('length');
        $number_of_pins = $this->input->post('number_of_pins');
        $saved = $this->School_setup->generate_pins($length,$number_of_pins);
        $page_data = array();
        $page_data['msg'] = $saved ? success_msg($length . " Result pins generated....") : info_msg("Pins not saved..");
        $this->p("result_pins",$page_data);
    }
    
    
    function view_parent_wards($parent_id = NULL,$args = array()){
        $parent_id = ($parent_id != NULL) ? $parent_id : $this->input->post('parent_id');
        $data = array();
        if($parent_id != NULL) {
        $data['parent_wards'] = $this->Parent->get_wards($parent_id);
        $data['parent_name'] = $this->Parent->get_name($parent_id);
        $data['parent_id'] = $parent_id;
        }
        $data = array_merge($data,$args);
        $this->p("student_parent",$data);
    }
    
    
    function add_parent_wards(){
        $data = array();
        $student_ids = $this->input->post("students");
        $parent_id = $this->input->post("parent_id");
        $added = $this->Parent->add_student_parent($parent_id,$student_ids);
        $data['msg'] = ($added) ? success_msg("Wards added!") : 
            info_msg("Wards not added. Check if student has been assigned to another parent");
        $this->view_parent_wards($parent_id,$data);
    }
    
    
    function del_parent_ward($parent_id,$student_id){
        $deleted = $this->Parent->remove_ward($parent_id,$student_id);
        $data['msg'] = ($deleted) ? success_msg("Ward removed") : info_msg("Not removed. Retry!");
		$redr_url = $this->input->get("redirect_url");
		if($redr_url != NULL){
			$this->session->set_flashdata("msg",$data['msg']);
			redirect($redr_url);
		}
		else {
			$this->view_parent_wards($parent_id,$data);
		}
    }
    
    function view_wards_parent($student_id = NULL,$args = array()){
        $student_id = ($student_id != NULL) ? $student_id :  $this->input->post("student_id");
        $data = array();
        if($student_id != NULL) { 
            $data['student_id'] = $student_id;
            $data['ward_parent'] = $this->Parent->get_wards_parent($student_id);
            $data['student_name'] = $this->Student->get_student_name($student_id);
        }
        else {
            $data['msg'] = warning_msg("Invalid Student ID");
        }
        $data = array_merge($data,$args);
        $data['active_tab'] = "ward_parent";
        $this->p("student_parent",$data);
    }
    
    
    function del_ward_parent($student_id,$parent_id){
        $deleted = $this->Parent->remove_ward($parent_id,$student_id);
        $data['msg'] = ($deleted) ? success_msg("Parent removed") : info_msg("Not removed. Retry!");
        $this->view_wards_parent($student_id,$data);
    }
   
    
    function add_ward_parent(){
        $student_id = $this->input->post("student_id");
        $parent_id = $this->input->post("parent_id");
        $added = $this->Parent->add_student_parent($parent_id,array($student_id));
        $data['msg'] = ($added) ? success_msg("Parent Assigned") : info_msg("Parent not assigned.");
        $this->view_wards_parent($student_id,$data);
    }
	
	
	
	/*
		Delete session or term
	*/
	function del($which,$record_id){
		
		$msg = $this->School_setup->delete($which,$record_id);
		$page_data['msg'] = $msg;
		$page_data["active_tab"] = "school_session";
		$this->p("manage_term",$page_data);
		
	}
	
    function system_setup($action = NULL){
//        $this->load->model("system_model","System");
//        $form_data = $this->input->post();
//        $data = array();
//        switch($action){
//                case "reset_db":
//                        $data['msg'] = $this->System->reset_db($form_data);
//                        break;
//        }
//        $data['db_tables'] = $this->System->list_tables();
        $this->p("system_setup",$data);
    }

}
