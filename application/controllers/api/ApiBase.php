<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "controllers/Rest_Ctrl.php");

class ApiBase extends Rest_Ctrl {

	public function __construct(){
		parent::__construct();
	    $this->load->library("Myapp");
	}


    public function load_model($model, $model_name = null){
        $model_name = ($model_name == null) ? $model : $model_name;
        $this->load->model($model . "_model",$model_name);
    }

}