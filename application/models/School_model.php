<?php 
	
	include_once("Crud_model.php");

	class School_model extends Crud_model {

		function __construct(){
			parent::__construct();
		}


		public function get_info(){
			return $this->_get(SCHOOL_INFO_TB);
		}
	}
?>