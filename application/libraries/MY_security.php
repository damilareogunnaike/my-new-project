<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_security extends CI_Security {
    
    public function __construct() {
        parent::__construct();
    }

    public function is_authorized($role)
    {
        if($this->session->userdata('logged_in') === FALSE){
            redirect();
        }
        else{
            //Check to ensure admin is logged in
            if($this->session->userdata('logged_in_role') !== $role)
            {
                redirect();
            }
        }
    }
}