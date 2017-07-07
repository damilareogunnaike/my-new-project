<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function login($data)
    {
        //
        $username = $data['username'];
        $rs = $this->db->get_where(LOGIN_TB,$data);
        if($rs->num_rows() > 0)
        {
            $record = $rs->row_array();
            //Due to recent notices, staff has been deleted from staff table
            //but not from login table. Since we don't want to be going into
            //the database to correct that now, let us attempt getting the name
            //and if that doesn't exist, then return null
            $username = $this->get_user_real_name($record['username'], $record['role']);
            if($username === NULL){
                return NULL;
            }
            else { 
                return $record; 
                
            }
        }
        return NULL;
    }
    
    function get_user_real_name($username,$role)
    {
        if($role === "staff"){
            $this->db->select("CONCAT (title, ' ', surname, ' ' ,first_name, ' ', last_name) AS real_name",FALSE);
            $table = "staff";
        }
        else if($role === "ms_parent"){
            $this->db->select("parent_name AS real_name");
            $table = "parents";
        }
        else{
            $this->db->select("username AS real_name");
            $table = "login";
        }
        $rs = $this->db->get_where($table,array('username'=>$username));
        if($rs->num_rows() > 0){
        $row = $rs->row_array();
        return $row['real_name'];       
        }
        else {
            return NULL;
        }
    }
    
    
    function delete_login($username){
        $this->db->where("username",$username);
        $this->db->delete("login");
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }
    
	
    function is_valid_password($user_id,$password){
       $data = array("username"=>$user_id,"password"=>$password);
       $user = $this->login($data); //Attempt login in with this user and the password
       if($user == NULL){
               return FALSE;
       }
       else return TRUE ;
    }


    function update_password($username,$new_password){
        $rs = $this->db->get("login");
        $this->db->where("username",$username);
        $this->db->update(LOGIN_TB,array("password"=>$new_password));
        return $this->db->affected_rows() > 0 ? success_msg("Password updated successfully!") : info_msg("Error updating password!");
    }


    //This method retrieves the name of a user
    function get_user_profile($user_id){
        //Search through login table
        
        $user = $this->get_profile($user_id);
        if($user != NULL) {
            $user_profile = $this->load->view("partials/user_profile",array("user"=>$user),true);
            return $user_profile;
        }        
        else {
            return "Unknown user!";
        }
    }
    
    
    function get_profile($user_id){
        $rs = $this->db->get_where("login",array("username"=>$user_id));
        if($rs->num_rows() > 0){
            $record = $rs->row_array();
            unset($record['password']);
            $record['user_id'] = $user_id;
            $record['username'] = $this->get_user_real_name($record['username'], $record['role']);
            return $record;
        }
        else {
            return NULL;
        }
    }
    
    
    /* Newly Added Methods */
    public function get($user_id){
        $user = $this->get_profile($user_id);
        $userObj = new User($user);
        return $userObj;
    }
}


class User {
    private $id         = null;
    private $user_id    = null;
    private $username   = null;
    private $role       = null;
    private $folder     = null; //Name of sub folder under views folder where user's files are saved
    
    /**
     * Creates a new user object by setting it's member variables from 
     * values in the array if available.
     * @param Array $user 
     */
    
    public function __construct($user  = null){
        if(is_array($user)){
           
            $vars = get_class_vars(get_class());
            foreach($user as $k => $v){
                if(array_key_exists($k, $vars)){
                    $this->$k = $v;
                }
            }
            $this->setFolder($this->role);
        }
    }
    
    
    public  function getId(){
        return $this->id;
    }
    
    
    public function getUserId() {
        return $this->user_id;
    }
    
    
    public function getUsername(){
        return $this->username;
    }
    
     public function getRole(){
        return $this->role;
    }

    public function setFolder($folder){
        $this->folder = ($folder == "ms_parent") ? "parent" : $folder;
    }
    
    public function getFolder(){
        return $this->folder;
    }
    
    
    public function isAdmin(){
        return $this->getRole() == "admin" ? TRUE : FALSE;
    }
    
    
    public function toArray(){
        $vars = array_keys(get_class_vars(get_class()));
        $info = array();
        foreach($vars as $k){
            $info[$k] = $this->$k;
        }
        return $info;
    }
}

