<?php
include_once 'School_ms_model.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Parent_model extends School_MS_Model
{
    function __construct() {
        parent::__construct();
    }
    
    function get_next_insert_id()
    {
        $this->db->select('parent_id');
        $this->db->order_by('date_added DESC');
        $this->db->limit(1);
        $rs = $this->db->get('parents');
        if($rs->num_rows() > 0)
        {
            $data = $rs->row_array();
            $last_insert = $data['parent_id'];
            $next_num = $last_insert + 1;
        }
        else $next_num = 1;
        $prefix = "EVP";
        return $prefix . pad_number($next_num);
    }
    
    
    function add_parent_user($data)
    {
        $data['username'] = $data['parent_user_pass'];
        $data['password'] = md5($data['parent_user_pass']);
        unset($data['parent_user_pass']);
        $parent_id = $this->add_parent($data);
        if($parent_id != NULL)
        {
            $this->add_parent_login($data['username'],$data['password']);
            return $this->db->affected_rows() > 0 ? TRUE : FALSE;
        }
        else return FALSE;
    }
    
    
    function add_parent($data)
    {
        $rs = $this->db->get_where('parents',$data);
        if($rs->num_rows() > 0)
        {
            $record = $rs->row_array();
            return $record['parent_id'];
        }
        else 
        {
            $this->db->insert('parents',$data);
            return $this->db->insert_id();
        }
    }
	
	
	function get_parent($parent_id){
		$rs = $this->db->get_where("parents",array("parent_id"=>$parent_id));
		return $rs->num_rows() > 0 ? $rs->row_array() : NULL;
	}
	
	function update_info($parent_id,$form_data){
		$this->db->where("parent_id",$form_data['parent_id']);
		$this->db->update("parents",$form_data);
		return $this->db->affected_rows() > 0 ? success_msg("Record Updated") : info_msg("Record not updated. Retry!");
	}
    
	
	function delete_parent($parent_id){
		//To delete a parent, check to make sure no student is assigned to this parent.
		$wards_count = $this->get_wards_count($parent_id);
		if($wards_count > 0){
			//Then this parent has wards. Notify to remove wards before proceeding
			return info_msg("Wards still assigned to this parent. Remove wards to delete parent!");
		}
		else {
			$this->db->delete("parents",array("parent_id"=>$parent_id));
			return $this->db->affected_rows() >  0 ? success_msg("Record deleted!") : info_msg("Records not deleted. Retry!");
		}
	}
    
    function is_valid_parent($parent_id){
        $rs = $this->get_name($parent_id);
        return ($rs != NULL) ? TRUE : FALSE;
    }
    
    function add_student_parent($parent_id,$students)
    {
        $values = array();
        foreach($students as $stud)
        {
            $record = array('student_id'=>$stud);
            if(!$this->record_exists('student_parent', $record)){
                $record['parent_id'] = $parent_id;
                $values[] = $record;
            }
        }
        
        //Parent id actually exists
        if(!$this->is_valid_parent($parent_id)){
            return FALSE;
        }
        if(sizeof($values) > 0) {
            $this->db->insert_batch('student_parent',$values);
            return $this->db->affected_rows() > 0 ? TRUE : FALSE;
        }
        return FALSE;
    }
    
    function add_parent_login($username,$password)
    {
        $record = array('username'=>$username,'password'=>$password,'role'=>'ms_parent');
        if(!$this->record_exists("login", $record))
        {
            $this->db->insert("login",$record);
            return TRUE;
        }
        else { return FALSE; }
    }
    
    
    
    function get_wards($parent_id)
    {
        $sql = "SELECT student_biodata.id AS student_id, CONCAT(surname, ' ' , first_name, ' ', middle_name) "
                . "AS full_name, image, classes.class_id, class_name, date_of_birth FROM student_parent,student_biodata LEFT JOIN "
                . "classes ON student_biodata.class_id = classes.class_id"
                . " WHERE student_parent.student_id"
                . " = student_biodata.id AND student_parent.parent_id = {$parent_id} AND student_biodata.deleted = 0 ";
        $rs = $this->db->query($sql);
        return ($rs->num_rows() > 0)? $rs->result_array() : NULL;
    }
    
    
    /**
     * Returns an array containing ids of this parent's wards
     * @param type $parent_id ParentID for whom wards are to be retrieved
     */
    function get_wards_ids($parent_id){
        $this->db->select("student_id");
        $rs = $this->db->get_where("student_parent",array("parent_id"=>$parent_id));
        if($rs->num_rows() > 0){
            $ids = $rs->result_array();
            $values = array();
            foreach($ids as $row){
                $values[] = $row['student_id'];
            }
            return $values;
        }
        return NULL;
    }
    
    
    function remove_ward($parent_id,$student_id){
        $this->db->delete("student_parent",array("parent_id"=>$parent_id,"student_id"=>$student_id));
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }
    
    
    function confirm_ward($parent_id,$student_id)
    {
        $this->db->where(array(
            'student_parent.parent_id'=>"{$parent_id}",
            'student_parent.student_id'=>$student_id
        ),NULL,FALSE);
        $this->db->from('parents,student_parent');
        $rs  = $this->db->get();
        return ($rs->num_rows() > 0)? TRUE : FALSE;
    }
    
    function get_wards_parent($student_id){
        $sql = "SELECT * FROM student_parent,parents WHERE student_parent.student_id = "
                . "{$student_id} AND parents.parent_id = student_parent.parent_id";
        $rs = $this->db->query($sql);
        return ($rs->num_rows() > 0) ? $rs->row_array() : NULL;
    }
    
    function get_parent_overview($parent_id)
    {
       $val = array();
       $val['total_msgs'] = 10;
       $val['total_wards'] = $this->get_wards_count($parent_id);
       //$val['total_events'] = $this->get_class_count($parent_id);
       return $val;
    }
    
    
    function get_wards_count($parent_id)
    {
        $sql = "SELECT COUNT(student_parent.student_id) AS total_wards FROM student_parent,student_biodata WHERE "
                . "student_parent.parent_id = {$parent_id} AND student_biodata.id = "
                . "student_parent.student_id AND student_biodata.deleted = 0";
        $rs = $this->db->query($sql);
        $row = $rs->row_array();
        return $row['total_wards'];
    }
    
    
    function get_name($parent_id){
        $rs = $this->db->get_where("parents",array("parent_id"=>$parent_id));
        if($rs->num_rows() > 0) {
            $row = $rs->row_array();
            return  $row['parent_name'];
        }
        else {
            return FALSE;
        }
    }
    
    function get_parent_id($username)
    {
        $this->db->select('parent_id');
        $rs = $this->db->get_where('parents',array('username'=>$username));
        if($rs->num_rows() > 0)
        {
            $record = $rs->row_array();
            return $record['parent_id'];
        }
        return NULL;
    }
    
    
    function get_parent_count()
    {
        $rs = $this->db->query("SELECT COUNT(parent_id) AS total_count FROM parents");
        $row = $rs->row_array();
        return $row['total_count'];
    }			
    
    function get_all_parents(){		
        $sql = "SELECT * FROM parents, login WHERE parents.username = login.username ORDER BY parent_name ASC";		
        $rs = $this->db->query($sql);		
        return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
    }

    /* UPDATES MADE TO V2 */

    public function get_mobile_numbers($seperator = ","){
        $this->db->select("GROUP_CONCAT(phone_no) AS numbers",FALSE);
        $this->db->WHERE("phone_no !="," ''",FALSE);
        $rs = $this->db->get(PARENTS_TB);
        $data = $rs->row_array();
        return $data['numbers'];
    }
}
