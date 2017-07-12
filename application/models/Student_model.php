<?php



/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once(APPPATH . "models/Crud_model.php"); 

class Student_model extends Crud_model
{
    private $CI;

    function __construct()
    {
        parent::__construct();
        $this->CI = & get_instance();
        $this->CI->load->model("Classes_model", "Classes");
        $this->CI->load->model("SubjectSettings_model", "SubjectSettings");

    }  


    function add_student($data, $current_session_id)
    {
        $rs = $this->db->get_where('student_biodata',$data);

        if($rs->num_rows() > 0)
        {
            return FALSE;
        }
        $data['first_name'] = strtoupper($data['first_name']);
        $data['middle_name'] = strtoupper($data['middle_name']);
        $data['surname'] = strtoupper($data['surname']);
        
        $student = $this->_add('student_biodata',$data, "id");
        if($this->db->affected_rows() > 0){
            //Then add student class record to student's class table..
            $class_record = array("student_id"=>$student['id'], 
                "class_id"=>$student['class_id'], 
                "school_session_id"=>$current_session_id);
            $this->_add("students_class",$class_record,"record_id");
        }
        return TRUE;
    }

    function get_student($student_id, $curr_session_id = null){

        if($curr_session_id == null){
            $curr_session_id = $this->CI->myapp->get_current_session_id();
        }

        $data = $this->_get_where("student_biodata",array("id"=>$student_id));
        if(sizeof($data) > 0) {
            $student = $data[0];
            $student['student_name'] = $this->get_name($student);
            $class = $this->get_class_for_student($student_id, $curr_session_id);
            $student['class'] = $class;
            return $student;
    }
        else {
            return null;
        }
    }


    function get_class_for_student($student_id, $curr_session_id){
        $sql = "CALL getStudentsClass({$student_id}, {$curr_session_id})";
        $rs = $this->db->query($sql);
        if($rs->num_rows() > 0) {
            $class = $rs->row_array();
            $rs->next_result();
            $rs->free_result();
            return $class;
        }
        else return null;
    }

	
    function update_student_record($student_id,$data)
    {
        $this->db->where('id',$student_id);
        $this->db->update('student_biodata',$data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

	
    function get_all_students($offset = 0,$limit = 100)
    {
        $sql = "SELECT id, id AS student_id,surname, first_name, middle_name, email, "
                . "CONCAT(surname, ' ', first_name, ' ',middle_name) AS fullname,"
                . "classes.class_name AS class FROM student_biodata LEFT JOIN classes ON student_biodata.class_id"
                . " = classes.class_id WHERE student_biodata.deleted = 0"
                . " ORDER BY surname ASC, first_name ASC, middle_name ASC";
        $rs = $this->db->query($sql);
        return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
    }
	

    function get_students_count($clause = "")
    {
        $rs = $this->db->query("SELECT COUNT(id) AS total_count FROM student_biodata WHERE deleted < 1"
                . " {$clause}");
        $row = $rs->row_array();
        return $row['total_count'];
    }
    

    function get_male_students_count(){
        return $this->get_students_count(" AND gender='Male'");
    }

    
    function get_female_students_count(){
        return $this->get_students_count(" AND gender='Female'");
    }


    function del_student($record_id)
    {
        $rs = $this->_get_where("student_biodata", array("id"=>$record_id));
        if($rs->num_rows() > 0){
            $student = $rs->row_array();
            $this->db->insert("deleted_students", $student);

            $this->db->where("id", $record_id);
            $this->db->delete("student_biodata");

            return $this->db->affected_rows() > 0 ? TRUE : FALSE;
        }
        return FALSE;
    }

    
    function get_biodata($id)
    {
        $this->db->where('id',$id);
        $this->db->where('deleted',0);
        $rs = $this->db->get('student_biodata');
        return ($rs->num_rows() > 0) ? $rs->row_array() : NULL;
    }
    

    function get_class_id($student_id)
    {
        $this->db->select('class_id');
        $rs = $this->db->get_where('student_biodata',array('id'=>$student_id));
        if($rs->num_rows() > 0)
        {
            $row = $rs->row_array();
            return $row['class_id'];
        }
        else return NULL;
    }
    

    function get_student_name($student_id)
    {
        $this->db->select("CONCAT (surname, ' ' , first_name , ' ', middle_name) AS student_name",FALSE);
        $rs = $this->db->get_where('student_biodata',array('id'=>$student_id));
        $row = $rs->row_array();
        return $row['student_name'];
}


    /**
     * Checks if a student ID is actually attached to a student
     * @param type $student_id ID of student to be validated
     */
    function confirm_valid_student($student_id){
        $this->db->where("id",$student_id);
        $this->db->where("deleted",0);
        $rs = $this->db->get('student_biodata');
        return ($rs->num_rows() > 0) ? TRUE : FALSE;
    }

	
    /* Searches for student using student ID or name */ 
    function search_student($keyword)
    {
        
		if(strlen($keyword) <= 0){
			return null;
		}
        $sql = "SELECT * FROM student_biodata WHERE id = '{$keyword}'"
        . " OR first_name LIKE '%{$keyword}%' OR surname LIKE '%{$keyword}%'"
        . " OR middle_name LIKE '%{$keyword}%' ORDER BY surname ASC, middle_name ASC, first_name ASC";
        $rs = $this->db->query($sql);
        return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
    }
	
	
	function is_valid_student($student_id){
		return ($this->search_student($student_id) != NULL) ? TRUE : FALSE;
	}


    /* NEW METHODS --- GRADUALLY REPLACING ALL METHODS */

    /**
     * Returns basic details about a student such as
     * name, class
     * @param $student_id
     */
    function get($student_id){
        return $this->get_student($student_id);
    }

    function search($keyword, $page_no, $page_size){
        
        if($page_no == null && $page_size == null){
            $page_no = 1;
            $page_size = 50;
        }

       $start_index = ($page_no - 1) * $page_size;
       $end_index = $start_index + $page_size;
       $keyword = $this->db->escape_str($keyword);
       $sql = "CALL searchForStudent('{$keyword}', {$start_index}, {$end_index})";
       $data = $this->_query($sql);
       return $data == null ? array() : $data;
    }


    public function update_class($session_id, $class_id, $student_ids) {
        $response = array();
        $insert_data = array();
        $update_data = array();
        foreach($student_ids as $student_id){
            $record = array("student_id"=>$student_id,"school_session_id"=>$session_id);
            $existing_record = $this->_get_where("students_class", $record);
            $record['class_id'] = $class_id;
            if( !is_array($existing_record) || sizeof($existing_record) < 1){
                $insert_data[] = $record;
            }
            else {
                $pre_record = $existing_record[0];
                $pre_record = array_merge($pre_record, $record);
                $update_data[] = $pre_record;
            }

            //Quickly update the classID of this student, in the result view..
            $sql = "UPDATE results SET class_id = {$class_id} WHERE session_id = {$session_id} AND student_id = {$student_id}";
            $this->db->query($sql);
        }
        if(sizeof($insert_data) > 0){
            $response['inserted'] = sizeof($insert_data);
            $this->db->insert_batch("students_class", $insert_data);
        }

        if(sizeof($update_data) > 0){
            $response['updated'] = sizeof($update_data);
            $this->db->update_batch("students_class", $update_data, "record_id");
        }
        return $response;
    }


    public function get_students_by_class_and_session($class_id, $session_id){
        $sql = "SELECT b.*, b.id AS student_id, CONCAT(b.surname, ' ', b.first_name, ' ', b.middle_name) AS student_name FROM students_class a, student_biodata b WHERE a.school_session_id = {$session_id} "
            . "AND a.class_id = {$class_id} AND a.student_id = b.id GROUP BY b.id ORDER BY b.surname ASC, b.middle_name ASC, b.first_name ASC";
        $students = $this->_query($sql);
        return $students;
    }


    public function get_by_session_and_class_and_subject($session_id, $class_id, $subject_id){

        $students = array();
        $class_subject_selection_mode = $this->CI->SubjectSettings->get_subject_selection_mode($class_id);     
        if($class_subject_selection_mode == 1){
            $students = $this->get_students_by_class_and_session($class_id, $session_id);
        }
        else if($class_subject_selection_mode == 2){

            if($this->SubjectSettings->is_compulsory_subject_for_class($subject_id, $class_id, $session_id)) {
                $students = $this->get_students_by_class_and_session($class_id, $session_id);
            }
            else {
                $students = $this->get_students_by_optional_subject($subject_id, $class_id, $session_id);
            }
        }
        //var_dump($students);
        return $students;
    }


    public function get_students_by_optional_subject($subject_id, $class_id, $session_id){
        $this->db->from("optional_student_subjects a");
        $this->db->from("student_biodata b");
        $this->db->from("students_class c");
        $this->db->where("a.subject_id", $subject_id);
        $this->db->where("c.class_id", $class_id);
        $this->db->where("a.school_session_id", $session_id);
        $this->db->where("c.school_session_id", $session_id);
        $this->db->where("a.student_id", "c.student_id", FALSE);
        $this->db->where("a.student_id", "b.id", FALSE);
        $this->db->order_by("b.surname", "ASC");
        $this->db->order_by("b.middle_name", "ASC");
        $this->db->order_by("b.first_name", "ASC");

        $rs = $this->db->get();
        return $rs->num_rows() > 0 ? $rs->result_array() : array();
    }


    public function get_name($student){
        $student_name = $student['surname'] . ' ' . $student['middle_name'] . ' ' . $student['first_name'];
        return $student_name;
    }



}