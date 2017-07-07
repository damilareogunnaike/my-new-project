<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class School_setup extends CI_Controller
{
    function __construct() {
        parent::__construct();
        
        $this->load->model('school_setup_model','School_setup');
    }
    
    function index($page = "",$args = [])
    {
        $data = ['title'=>ucfirst($page)];
        $data = array_merge($args,$data);
        $data['page'] = title_text($page);
        $data['page_content'] = $this->load->view('admin/'.$page,$data,TRUE);
        $this->load->view('header',$data);
        $this->load->view('admin/index');
        $this->load->view('footer');
    }
    
    
    function manage_class($args = [])
    {
        $data['classes'] = $this->School_setup->get_all_classes();
        $data = array_merge($args,$data);
        $this->index('manage_class',$data);
    }
    
    
    function add_class()
    {
        $data = $this->input->post();
        $msg = "";
        if(isset($data['class_name']))
        {
            $saved = $this->School_setup->add_class($data);
            $msg = ($saved) ? success_msg("Class Saved...") : error_msg("Error. Retry...");
        }
        $this->manage_class(array('msg'=>$msg));
    }
    
    
    /** Subjects **/
    
    function manage_subject($args = [])
    {
        $data['subjects'] = $this->School_setup->get_all_subjects();
        $data = array_merge($args,$data);
        $this->index('manage_subject',$data);
    }
    
    
    function add_subject()
    {
        $data = $this->input->post();
        $msg = "";
        if(isset($data['subject_name']))
        {
            $saved = $this->School_setup->add_subject($data);
            $msg = ($saved) ? success_msg("Subject Saved...") : error_msg("Error. Retry...");
        }
        $this->manage_subject(array('msg'=>$msg));
    }
    
    
    function del($what,$record_id)
    {
        $func_name = "del_".$what;
        $deleted = $this->School_setup->$func_name($record_id);
        $msg = ($deleted) ? success_msg("Deleted...") : error_msg("Not deleted.. Retry.");
        $func_name = "manage_".$what;
        $this->$func_name(array('msg'=>$msg,'active_tab'=>'view_'.$what));
    }
    
    
    function view_class_students($class_id = NULL){
        $page_data['class_name'] = $this->School_setup->get_class_name($class_id);
        $page_data['class_students'] = $this->School_setup->get_students_in_class($class_id);
        $this->index("class_students",$page_data);
    }
    
}
