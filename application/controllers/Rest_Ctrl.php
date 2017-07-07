<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('./application/libraries/REST_Controller.php');

/**
* This Controller is to be extended by any class implementing rest calls.
* The Controller sets header values to accept cross-origin requests.
* This should be looked into in the future, to avoid attacks.
*/
class Rest_Ctrl extends REST_Controller {

	public $_current_session_id;
	public $_current_term_id;
	
	function __construct(){
		header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method"); 
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE"); 
        $method = $_SERVER['REQUEST_METHOD']; 
        header('Content-type: application/json');

        if($method === "OPTIONS") { 
			die();
		} 
		parent::__construct();

		$this->load->library("Myapp");
		$this->_current_session_id = $this->myapp->get_current_session_id();
		$this->_current_term_id = $this->myapp->get_current_term_id();


	}

	public function _load_model($model, $model_name = null){
        $model_name = ($model_name == null) ? $model : $model_name;
        $this->load->model($model . "_model",$model_name);
    }
	
}
