<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once 'school_ms_model.php';

class System_model extends School_MS_Model{
	
    function __construct()
    {
        parent::__construct();
    }
	
	function list_tables(){
		return $this->db->list_tables();
	}
	
	
	function reset_db(){
		//Get DB Files
		$CI = & get_instance();
		$db_name = $CI->db->database;
		$db_file_path = base_url($this->config->item("db_file_path"));
		
		//Read file to get queries
		$sql_lines = file($db_file_path);
		
		//Load dbforge
		$this->load->dbforge();
		$this->dbforge->drop_database($db_name); //Drop DB
		$this->dbforge->create_database($db_name); //CREATE NEW DB
		
		$this->db->query("USE ".$db_name); //Select DB to use
		
		//Parse lines and run query
		$templine = "";
		foreach($sql_lines as $line){
			if(substr($line,0,2) == "--" || $line == "--"){
				continue;
			}
			$templine .= $line;
			if (substr(trim($line), -1, 1) == ';'){
				$this->db->query($templine);
				$templine = "";
			}
		}
		return success_msg("System Reset Completed..");
	}
	
    
   
}