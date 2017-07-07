<?php


class Test extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model("Staff_model","Staffs");
	}


	function index(){
		echo "Please enter a test method!";
	}


	function staff_numbers(){
		$this->Staffs->get_mobile_numbers();
	}
}
