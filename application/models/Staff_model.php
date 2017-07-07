<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Staff_model extends CI_Model
{
    function __construct() {
        parent::__construct();
    }
    
    function add_staff($data)
    {
        $this->db->trans_start();
        $username = $data['username'];
        $password = md5($username);
        if($this->add_staff_login($username, $password))
        {
            $this->db->insert(STAFF_TB,$data);
        }
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE || $this->db->affected_rows() < 1){
            return FALSE;
        }
        else {
            return TRUE;
        }
    }
    
    
    function add_staff_login($username,$password)
    {
        $this->db->insert('login',array('username'=>$username,'password'=>$password,'role'=>'staff'));
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }
    
	
	function get_staff_info($staff_id){
		$rs = $this->db->get_where("staff",array("staff_id"=>$staff_id));
		return $rs->num_rows() > 0 ? $rs->row_array() : NULL;
	}
	
	
    function get_all_staff(){
        $rs = $this->db->get(STAFF_TB);
        return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
    }
    

    function get_staff_count(){
        $rs = $this->db->query("SELECT COUNT(staff_id) AS total_count FROM staff");
        $row = $rs->row_array();
        return $row['total_count'];
    }
    
    
    function get_staff_id($username){
        $this->db->select('staff_id');
        $rs = $this->db->get_where('staff',array('username'=>$username));
        if($rs->num_rows() > 0)
        {
            $record = $rs->row_array();
            return $record['staff_id'];
        }
        return NULL;
    }
   
   
	function update_details($staff_id,$data){
		$this->db->where("staff_id",$staff_id);
		$this->db->update("staff",$data);
		return $this->db->affected_rows() > 0 ? success_msg("Record Updated") : error_msg("Record not updated!");
	}

    
    function get_next_staff_id()
    {
        $this->db->select('staff_id');
        $this->db->order_by('date_added DESC');
        $this->db->limit(1);
        $rs = $this->db->get('staff');
        if($rs->num_rows() > 0)
        {
            $data = $rs->row_array();
            $last_insert = $data['staff_id'];
            $next_num = $last_insert + 1;
        }
        else $next_num = 1;
        $prefix = "EVST";
        return $prefix . pad_number($next_num);
    }
    
    
    function get_my_subjects($staff_id){
        $this->db->from('classes c,subjects s,subject_teacher t');
        $this->db->select('t.subject_id,s.subject_name, '
                . 'c.class_name,c.class_id');
        $this->db->where(array(
            't.staff_id'=>"'".$staff_id."'",
            'c.class_id'=>'t.class_id',
            's.subject_id'=>'t.subject_id'
        ),NULL,FALSE);
        $rs = $this->db->get();
        return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
    }
    
   
    /**
     * Confirms if a teacher has been assigned to teach a subject for  particular class
     * @param int $staff_id ID of staff to be confirmed to teach a class a subject
     * @param int $subject_id ID of subject whose  teacher is to be confirmed
     * @param int $class_id ID of class whose subject teacher is to be confirmed 
     */
    function confirm_class_subject_teacher($staff_id,$subject_id,$class_id)
    {
        $rs = $this->db->get_where('subject_teacher',array('class_id'=>$class_id,
            'staff_id'=>$staff_id,'subject_id'=>$subject_id));
        return ($rs->num_rows() > 0) ? TRUE : FALSE;
    }
    
    
    function confirm_class_teacher($staff_id,$class_id)
    {
        $rs = $this->db->get_where('class_teacher',array('class_id'=>$class_id,'staff_id'=>$staff_id));
        return ($rs->num_rows() > 0) ? TRUE : FALSE;
    }
    
    
    function get_subject_students($subject_id,$class_id)
    {
        $this->db->select("CONCAT (surname,' ',first_name, ' ',middle_name) AS student_name",FALSE);
        $this->db->order_by('student_name ASC');
        $rs = $this->db->get_where('student_biodata',array('class_id'=>$class_id));
        return ($rs->num_rows() > 0 ) ? $rs->result_array() : NULL;
    }
    
    
    function get_staff_overview($staff_id)
    {
       $val = array();
       $val['total_msgs'] = $this->get_msg_count($staff_id);
       $val['total_subjects'] = $this->get_subject_count($staff_id);
       $val['total_classes'] = $this->get_class_count($staff_id);
       return $val;
    }
    
    
    function get_subject_count($staff_id)
    {
        $sql = "SELECT COUNT(subject_id) AS total_subjects FROM subject_teacher WHERE "
                . "subject_teacher.staff_id = {$staff_id}";
        $rs = $this->db->query($sql);
        $row = $rs->row_array();
        return $row['total_subjects'];
    }
    
    
    function get_my_classes($staff_id)
    {
        $sql = "SELECT class_name, class_teacher.class_id FROM classes, class_teacher WHERE class_teacher.staff_id = {$staff_id}"
        . " AND class_teacher.class_id = classes.class_id";
        $rs = $this->db->query($sql);
        return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
    }
    
    function get_class_count($staff_id)
    {
        $sql = "SELECT COUNT(class_id) AS total_classes FROM class_teacher WHERE "
                . "class_teacher.staff_id = {$staff_id}";
        $rs = $this->db->query($sql);
        $row = $rs->row_array();
        return $row['total_classes'];
    }
    
    
    function get_msg_count($staff_id)
    {
       return 0;
    }
	
	
	function remove_class_teacher($class_id,$staff_id){
		$this->db->where(array("class_id"=>$class_id,"staff_id"=>$staff_id));
		$this->db->delete("class_teacher");
		return $this->db->affected_rows() > 0 ? success_msg("Operation successful!") : error_msg("Operation not successful. Retry");
	}
    
    
    function del_staff($staff_id){
		$status = array();
		$class_count = $this->get_class_count($staff_id);
		$subject_count = $this->get_subject_count($staff_id);
		if($class_count > 0 || $subject_count > 0){
			$status['deleted'] = FALSE;
			$status['msg'] = info_msg("Could not delete record. Staff has not been relieved of all duties. Remove classes and subjects assigned to this teacher.");
			return $status;
		}
        $this->db->delete('staff',array('staff_id'=>$staff_id));
		$status['deleted'] =  $this->db->affected_rows() > 0 ? TRUE : FALSE;
        return $status;
    }

    /* UPDATES MADE TO V2 */
    public function get_mobile_numbers($seperator = ","){
        $this->db->select("GROUP_CONCAT(mobile_no) AS numbers",FALSE);
        $this->db->WHERE("mobile_no !="," ''",FALSE);
        $rs = $this->db->get(STAFF_TB);
        $data = $rs->row_array();
        return $data['numbers'];
    }
}