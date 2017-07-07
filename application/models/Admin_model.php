<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once 'School_ms_model.php';

class Admin_model extends School_MS_Model{
    function __construct()
    {
        parent::__construct();
    }
    
    
    function add_admin($data){
        $data['password'] = md5($data['password']);
        if(!$this->record_exists("login",array("username"=>$data['username'],'role'=>'admin'))){
            $this->db->insert("login",$data);
        }
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
    
    
    /**
     * Gets all administrators from database
     * @return Array Array of admins in database
     */
   function get_all_admins(){
	   $this->db->where("role !=","ms_parent");
       $rs = $this->db->get('login');
       return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
   }
   
   
   function del_admin($admin_id ){
       $admins = $this->get_all_admins();
       if(sizeof($admins) > 1) { // There must be at least one adminstrator 
            $this->db->where("id",$admin_id);
            $this->db->delete("login");
       }
       return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
   }
   
   
}