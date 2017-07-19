<?php   
include_once 'CODE_MAKER.php';
include_once(APPPATH . "models/Crud_model.php"); 

class School_setup_model extends Crud_model
{
    private $CI;


    function __construct()
    {
        parent::__construct();
        $this->CI = & get_instance();
    }
    
    function add($table,$data)
    {
        $this->db->insert($table,$data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }


    /** CLASSES **/
    
    function add_class($data)
    {
        if(!$this->record_exists('classes',$data)) {
            return $this->add('classes',$data);
        }
        else return TRUE;
    }
    

    function get_class_name($class_id)
    {
        $rs = $this->db->get_where('classes',array('class_id'=>$class_id));
        if($rs->num_rows() > 0)
        {
            $data = $rs->row_array();
            return $data['class_name'];
        }
        return FALSE;
    }
    
    
    function get_all_classes()
    {
        $this->db->order_by('class_name','ASC');
        $rs = $this->db->get('classes');
        return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
    }
    
    
   function del_class($record_id)
   {
       $this->db->delete('classes',array('class_id'=>$record_id));
       return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
   }
   
   
   function save_class_teacher($data)
   {
       $rs = $this->db->get_where('class_teacher',$data);
       if($rs->num_rows() <= 0)
       {
           $this->db->insert('class_teacher',$data);
           return ($this->db->affected_rows() > 0)? TRUE : FALSE;
       }
       return TRUE;
   }
   
   
   
   //Get Teacher teaching a particular class
   function get_class_teacher($class_id)
   {
       $this->db->order_by('surname','ASC');
       $this->db->order_by('class_name','ASC');
       $this->db->where(array(
          'class_teacher.class_id'=>$class_id,
           'class_teacher.staff_id'=>'staff.staff_id',
           'classes.class_id'=>'class_teacher.class_id',
       ),NULL,FALSE);
       $this->db->select("staff.title, surname,class_name, first_name,"
               . "last_name, CONCAT (title,' ',surname, ' ', first_name, ' ',last_name)"
               . "AS staff_name",FALSE);
       $this->db->from('classes,class_teacher,staff');
       $rs = $this->db->get();
       return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
   }
   
   
   function get_class_teacher_names($class_id)
   {
       $teachers = $this->get_class_teacher($class_id);
       $value = array();
       if(sizeof($teachers) > 0)
       {
           foreach($teachers as $teacher)
           {
               $value[] = $teacher['staff_name'];
           }
       }
       $return_val = implode(",",$value);
       return $return_val;
   }
    
   
   //Get Teacher teaching a particular class
   function get_teacher_class($staff_id)
   {
       $this->db->order_by('surname','ASC');
       $this->db->order_by('class_name','ASC');
       $this->db->where('class_teacher.staff_id',$staff_id);
       $this->db->where(array('class_teacher.staff_id'=>'staff.staff_id','classes.class_id'=>'class_teacher.class_id'),NULL,FALSE);
       $this->db->from('classes,class_teacher,staff');
       $rs = $this->db->get();
       return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
   }
   
   /** SUBJECTS **/
   
   function add_subject($data)
    {
        $this->db->insert('subjects',$data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
    
    function get_subject_name($subject_id)
    {
        $rs = $this->db->get_where('subjects',array('subject_id'=>$subject_id));
        if($rs->num_rows() > 0)
        {
            $data = $rs->row_array();
            return $data['subject_name'];
        }
        return FALSE;
    }
    
    
    function get_all_subjects()
    {
        $this->db->order_by('subject_name','ASC');
        $rs = $this->db->get('subjects');
        return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
    }
    
    
   function del_subject($record_id)
   {
       $this->db->delete('subjects',['subject_id'=>$record_id]);
       return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
   }
    
    
   function save_subject_teacher($data)
   {
       $values = array();
       $classes = $data['class_id'];
       $staff_id = $data['staff_id'];
       $subject_id = $data['subject_id'];
       foreach($classes as $class)
       {
           $record = array('class_id'=>$class,'subject_id'=>$subject_id,'staff_id'=>$staff_id);
           if(!$this->record_exists('subject_teacher', $record))
           {
               $values[] = $record;
           }
       }
       if(sizeof($values) > 0)
       {
           $this->db->insert_batch('subject_teacher',$values);
           return ($this->db->affected_rows() > 0)? TRUE : FALSE;
       }
       return TRUE;
   }
   
   
   function get_subject_teacher_or_teacher_subject($where,$value)
   {
       $this->db->order_by('surname','ASC');
       $this->db->order_by('subject_name','ASC');
       $this->db->where($where,$value);
       $this->db->where(array(
           'subject_teacher.staff_id'=>'staff.staff_id',
           'subjects.subject_id'=>'subject_teacher.subject_id',
           'classes.class_id'=>'subject_teacher.class_id'
       ),NULL,FALSE);
       $this->db->select("class_name,CONCAT(title,' ',surname, ' ', "
               . "first_name, ' ',last_name) AS staff_name,subject_name, subject_teacher.subject_id, subject_teacher.class_id,subject_teacher.staff_id, subject_teacher_id,",FALSE);
       $this->db->from('subjects,subject_teacher,staff,classes');
       $rs = $this->db->get();
       return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
   }
   
   
   //Get Teacher teaching a particular subject
   function get_subject_teacher($subject_id)
   {
       return $this->get_subject_teacher_or_teacher_subject('subject_teacher.subject_id',$subject_id);
   }
    
   
   //Get Teacher teaching a particular class
   function get_teacher_subject($staff_id)
   {
       return $this->get_subject_teacher_or_teacher_subject('subject_teacher.staff_id',$staff_id);
   }
   
   //Get Teacher teaching a particular class
   function get_class_subject_teacher($class_id)
   {
       return $this->get_subject_teacher_or_teacher_subject('subject_teacher.class_id',$class_id);
   }
   
   
   
   function remove_subject_teacher($subject_id,$class_id,$staff_id){
	   $this->db->where(array("subject_id"=>$subject_id,"class_id"=>$class_id,"staff_id"=>$staff_id));
	   $this->db->delete("subject_teacher");
	   return $this->db->affected_rows() > 0 ? success_msg("Operation successful!") : error_msg("Operation not successful. Retry");
   }
   
   
   
   /* Sessions and Term */
   function add_school_session($data)
   {
       if(!$this->record_exists('school_session', $data))
       {
           return $this->add('school_session', $data);
       }
   }
   
   
   function get_school_sessions()
   {
       $this->db->order_by('school_session_name','ASC');
       $rs = $this->db->get('school_session');
       return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
   }
 
   
   function add_school_term($data)
   {
       if(!$this->record_exists('school_term', $data))
       {
           return $this->add('school_term', $data);
       }
   }
   
   function get_school_terms(){
       $this->db->where("active", 1);
       $this->db->order_by('order','ASC');
       $rs = $this->db->get('school_term');
       return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
   }
   
   
   /**
    * This method updates session/ term table to set current session/term
    * @param Array $data Form data which contains id of record to be altered
    * @param String $table Table whose data is to be altered
    * @param String $col  Column in table whose value is compared to be changed
    * @return boolean TRUE if successful. False Otherwise
    */
   function set_current($data,$table,$col)
   {
       $row_id = $data[$col]; //Row to be changed
       
       //Set all others current field to 0
       $this->db->where("{$col} !=",$row_id);
       $this->db->update($table,array('current'=>0));
       
       //Set this selection's current field to 1
       $this->db->where($col,$row_id); 
       $this->db->update($table,array('current'=>1));
       return ($this->db->affected_rows() > 0) ? TRUE: FALSE;
   
   }
   
   function set_current_session($data)
   {
       return $this->set_current($data,'school_session','school_session_id');
   }
   
   function set_current_term($data)
   {
       return $this->set_current($data,'school_term','school_term_id');
   }
   
   
   /**
    * Gets the current session or term set 
    * @param type $table Table (session/term) whose current value is to be fetched
    * @param type $col Column in table to be returned
    * @return mixed Current Session/Semester as string if set, NULL otherwise 
    */
   function get_current($table,$col)
   {
       $rs = $this->db->get_where($table,array('current'=>1));
       if($rs->num_rows() > 0)
       {
           $record = $rs->row_array();
           return $record[$col];
       }
   }
   
   
   function delete($which,$record_id){
	   if($which == "session"){
		   $this->db->delete("school_session",array("school_session_id"=>$record_id));
	   }
	   else if($which == "term"){
		   $this->db->delete("school_term",array("school_term_id"=>$record_id));
	   }
	   return $this->db->affected_rows() > 0 ? success_msg("Record deleted!") : info_msg("Record not deleted!");
   }
   
   function get_session_name($session_id  = NULL)
   {
	   if($session_id == NULL) {
		   return NULL;
	   }
       $rs = $this->db->query("SELECT school_session_name FROM school_session WHERE school_session_id = {$session_id}");
       $record = $rs->row_array();
     return $record['school_session_name'];
   }
   
   function get_current_session_id()
   {
       return $this->get_current('school_session', 'school_session_id');
   }
   
   function get_term_name($term_id)
   {
       if($term_id == "all") { return "First - Third"; }
       $rs = $this->db->query("SELECT school_term_name FROM school_term WHERE school_term_id = '{$term_id}'");
       if($rs->num_rows() > 0)
       {
            $record = $rs->row_array();
            return $record['school_term_name'];
       }
       else 
       {
           return NULL;
       }
   }
   
   function get_current_term_id()
   {
       return $this->get_current('school_term', 'school_term_id');
   }
   
   /* Global Functions */
   function record_exists($table,$data)
    {
        $rs = $this->db->get_where($table,$data);
        return ($rs->num_rows() > 0) ? TRUE : FALSE;
    }
    
    function get_class_students($class_id, $school_session_id = null)
    { 
        $school_session_id = ($school_session_id == null) ? $this->CI->myapp->get_current_session_id() : $school_session_id;
        $sql = "SELECT CONCAT(surname, ' ', first_name, ' ', middle_name) AS student_name, a.student_id, " .
            "getStudentsClass(a.student_id, {$school_session_id}) AS current_class FROM students_class a LEFT JOIN student_biodata b ON a.student_id = b.id " .
            "WHERE a.class_id = {$class_id} AND a.school_session_id = {$school_session_id} AND b.deleted = 0 " .
            "ORDER BY b.surname ASC, b.first_name ASC, b.middle_name ASC";
        $rs =$this->db->query($sql);
        return ($rs->num_rows() >  0 )? $rs->result_array() : NULL;
    }
    
    
     function get_class_count()
    {
        $rs = $this->db->query("SELECT COUNT(class_id) AS total_count FROM classes");
        $row = $rs->row_array();
        return $row['total_count'];
    }
    
     function get_subject_count()
    {
        $rs = $this->db->query("SELECT COUNT(subject_id) AS total_count FROM subjects");
        $row = $rs->row_array();
        return $row['total_count'];
    }
    
    
    function save_school_settings($data)
    {
        $rs = $this->db->get('school_settings');
        if($rs->num_rows() > 0)
        {
            $row = $rs->row_array();
            $this->db->where('school_setting_id',$row['school_setting_id']);
            $this->db->update('school_settings',$data);
        }
        else {
            $this->db->insert('school_settings',$data);
        }
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
    
    
    function get_school_settings()
    {
        $rs = $this->db->get('school_settings');
        return ($rs->num_rows() > 0) ? $rs->row_array() : NULL;
    }
    
    
    /**
     * Change Students' class to another.
     * Recently, it was discovered that promotion of students poses 
     * a problem, as promotion of a class leads to data disintegrity as fields are not balanced.
     * The solution is to have a seperate table that stores student's classes per session.
     * @param Array $student_ids ids of students whose class is to be changed
     * @param int $class_id Class Id of class to move students to
     */
    function change_class($student_ids,$class_id,$school_session_id){
      $insert_data = array();
      $students_class_insert_data = array();
      $students_class_update_data = array();
      $stud_class_data = array();
        if(sizeof($student_ids) > 0) {
            $update_data = array();
            foreach($student_ids as $student_id){
                $record['id'] = $stud_class_data['student_id'] = $student_id;
                $record['class_id'] = $class_id;
                $update_data[] = $record;
                $stud_class_data['school_session_id'] = $school_session_id;
                if ($this->record_exists("students_class", $stud_class_data)){

                  $this->db->set("class_id",$class_id);
                  $this->db->where(array("student_id"=>$student_id,"school_session_id"=>$school_session_id));
                  $this->db->update("students_class");
                 
                }
                else {
                  $stud_class_data['class_id'] = $class_id;
                  $students_class_insert_data[] = $stud_class_data;
                }

            }
            $this->db->update_batch("student_biodata",$update_data,"id");

            if (sizeof($students_class_insert_data) > 0){
               $this->db->insert_batch("students_class", $students_class_insert_data);
            }
            return $this->db->affected_rows() > 0 ? $this->db->affected_rows() : FALSE;
        }
    }
    
    
    //Checks if pin exists in database
    function validate_pin($pin){
        $this->db->where('pin',$pin);
        $this->db->where("student_id","");
        $rs = $this->db->get("activation_pins");
        return $rs->num_rows() > 0 ? TRUE : FALSE;
    }
    
    
    function set_result_pin($session_id,$term_id,$data){
        //Check if students pin has been set for this session and term
        $record = array("session_id"=>$session_id,'term_id'=>$term_id,
            'student_id'=>$data['student_id']);
        $this->db->where($record);
        $rs = $this->db->get("activation_pins");
        if($rs->num_rows() > 0){ // Student pin has already been set
            $row = $rs->row_array();
            $msg = info_msg("Student Pin already set. Pin is " . $row['pin']);
        }
        else { //Set student pin
            $this->db->where("pin",$data['pin']);
            $this->db->update("activation_pins",$record);
            $msg = $this->db->affected_rows() > 0 ? success_msg("Result pin activated..") : warning_msg("Something bad happened. Retry");
        }
        return $msg;
    }
    
    function get_student_pin($student_id)
    {
        $this->db->where("student_id",$student_id);
        $rs = $this->db->get("activation_pins");
        return ($rs->num_rows() > 0) ? $rs->row_array() : NULL;
    }
    
    function generate_pins($length,$num_of_pins){
        $pin_generator = new CODE_MAKER();
        $pin_generator->allow_chars = FALSE;
        $pin_generator->allow_symbols = FALSE;
        $pin_generator->prefix = "EM";
        $pin_generator->length = $length;
        $pin_generator->quantity = $num_of_pins;
        $pins = $pin_generator->generate();
        $record = array();
        foreach($pins as $pin){
            $record[] = array("pin"=>$pin);
        }
        $this->db->insert_batch("activation_pins",$record);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
  

    function get_students_in_class($class_id){
        $sql = "SELECT CONCAT(surname, ' ', first_name, ' ', middle_name) AS student_name, c.id AS student_id,parent_name, e.username, e.phone_no " .
              "FROM students_class a, school_session b, student_biodata c " .
              " LEFT JOIN student_parent d ON c.id = d.student_id LEFT JOIN parents e ON d.parent_id = e.parent_id ".
              "WHERE a.school_session_id = b.school_session_id ANd b.current = 1 AND a.student_id = c.id AND c.deleted = 0 AND a.class_id = {$class_id}";
        $rs = $this->db->query($sql);
        return $rs->num_rows() > 0 ? $rs->result_array() : NULL;
    }
	
	
  	function get_all_states(){
  		$rs = $this->db->get("states_db");
  		return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
  	}


    public function get_current_session(){
      $rs = $this->_get_where("school_session", array("current"=>1));
      return sizeof($rs > 0) ? $rs[0] : null;
    }


    public function get_current_term(){
      $rs = $this->_get_where("school_term", array("current"=>1));
      return sizeof($rs > 0) ? $rs[0] : null;
    }
}
?>