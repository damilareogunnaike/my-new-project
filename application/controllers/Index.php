<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {

    private $parent_id = NULL;
        
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model','Users');
	}
        

	public function index($page = null,$args = [])
	{
        if($page == null){
            redirect("ms_parent");
        } 
        //If Admin is logged in, redirect to profile page insteads
        if($this->is_user_logged_in() != FALSE){
                redirect($this->session->userdata("logged_in_role"));
        }
        $data = ['title'=>'School MS'];
        $data = array_merge($data,$args);
        $this->load->view('header',$data);
        $this->load->view($page);
        $this->load->view('footer');
	}
        
        
	function login($actor = "admin")
	{
        $data = $this->input->post();
        $msg = "";
        if(isset($data['username']) && isset($data['password']) )
        {
                $data['password'] = md5($data['password']);
                $remember_me = FALSE;
                if(isset($data['remember_me']))
                {
                    $remember_me = TRUE;
                    unset($data['remember_me']);
                }

                $user = $this->Users->login($data);
                if($user != NULL)
                {       
                    $user_real_name = $this->Users->get_user_real_name($user['username'],$user['role']);
                    $this->session->set_userdata($user);
                    $this->session->set_userdata("logged_in",TRUE);
                    $this->session->set_userdata('logged_in_role',$user['role']);
                    $this->session->set_userdata('user_real_name',$user_real_name); 
                    log_message('debug', "User with name " . $user['username'] . " just logged in");
                    redirect($user['role']);
                }
                else $msg = error_msg("Invalid login details...");
        }

        $default_page = "login";
        switch($actor){
            case "parent":
                $default_page = "parent/login";
                break;
        }
        $this->load->view($default_page,array('actor'=>$actor,'title'=> "User | " . " Login",'msg'=>$msg));
	}
	
	
	function logout()
	{
		$this->session->unset_userdata($this->session->all_userdata());
		redirect();
	}
	

	function is_user_logged_in(){
		return ($this->session->userdata("logged_in"));
	}

}

/* End of file welcome.php */