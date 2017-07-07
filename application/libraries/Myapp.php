<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Myapp {
	
	private  $CI;

	private $curr_session_id;

	private $curr_session;

	private $curr_term_id;

	private $curr_term;
	
	public function __construct(){
            $this->CI = & get_instance();
            $this->CI->load->model('school_setup_model','School_setup');
            
            $current_session = $this->CI->School_setup->get_current_session();
        	$this->curr_session_id = $current_session['school_session_id'];

            $current_term = $this->CI->School_setup->get_current_term();
            $this->curr_term_id = $current_term['school_term_id'];
            $this->curr_term = $current_term['school_term_name'];
	}

    public function session_dropdown( $curr_session_id = NULL)
    {
		$curr_session_id = ($curr_session_id == NULL) ? $this->CI->School_setup->get_current_session_id() : $curr_session_id;
		$sessions = $this->CI->School_setup->get_school_sessions();
		$dropdown = print_dropdown($sessions,"school_session_id","school_session_name",$curr_session_id);
		echo $dropdown;
    }
	
	
	public function term_dropdown()
    {
		$curr_term_id = $this->CI->School_setup->get_current_term_id();
		$terms = $this->CI->School_setup->get_school_terms();
		$dropdown = print_dropdown($terms,"school_term_id","school_term_name",$curr_term_id);
		return $dropdown;
    }
	
	
	public function class_dropdown()
    {
		$classes = $this->CI->School_setup->get_all_classes();
		$dropdown = print_dropdown($classes,"class_id","class_name");
		return $dropdown;
    }
	
	
	public function subjects_dropdown()
    {
		$subjects = $this->CI->School_setup->get_all_subjects();
		$dropdown = print_dropdown($subjects,"subject_id","subject_name");
		return $dropdown;
    }


    public function get_current_session_id() {
    	return $this->curr_session_id;
    }

    public function get_current_term_id() {
    	return $this->curr_term_id;
    }

     public function get_current_term_name() {
    	return $this->curr_term;
    }
}


?>