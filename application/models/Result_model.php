<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define('STAFF_NAME',"CONCAT(title, ' ', staff.surname, ' ', staff.first_name, ' ', staff.last_name) AS staff_name");

class Result_model extends CI_Model
{
    function __construct() {
        parent::__construct();
    }

      /**
     * Ok, I want to fetch the subjects of this student first and to do so, I need to get
     * the students class, then get the subjects that have been assigned a class teacher
     * then get the results for that subject for the class for the stuet
     * @param type $student_id
     * @param type $class_id
     * @param type $session_id
     * @param type $term_id
     * @return type
     */
    function get_student_result_form($student_id,$class_id,$session_id,$term_id)
    {
        $sql = "SELECT subjects.subject_id, ca1,ca2,exam, subject_name FROM subject_teacher,subjects LEFT JOIN results ON"
                . " results.subject_id = subjects.subject_id AND results.session_id = "
                . "{$session_id} AND results.term_id = {$term_id} AND results.student_id"
                . " = {$student_id} WHERE subject_teacher.class_id = {$class_id}"
        . " AND subjects.subject_id = subject_teacher.subject_id GROUP BY results.subject_id ORDER BY subject_name ASC";
        $rs = $this->db->query($sql);
        return ($rs->num_rows() > 0 ) ? $rs->result_array() : NULL;
    }
    
    function get_subject_result_form($subject_id,$class_id,$session_id,$term_id)
    {
        $sql = "SELECT CONCAT (surname,' ',first_name, ' ',middle_name) AS student_name, ca1, ca2, exam, "
                . "student_biodata.id AS student_id FROM students_class a, student_biodata "
                . " LEFT JOIN results ON student_biodata.id = results.student_id"
                . " AND results.class_id = student_biodata.class_id AND results.subject_id = "
                . "{$subject_id} AND results.session_id = {$session_id} AND results.term_id = {$term_id}"
                . " WHERE a.class_id = {$class_id} AND a.school_session_id = {$session_id} "
                . " AND a.student_id = student_biodata.id AND student_biodata.deleted = 0 ORDER BY student_name ASC";
        $rs = $this->db->query($sql);
        return ($rs->num_rows() > 0 ) ? $rs->result_array() : NULL;
    }


     function save_student_result($data,$session_id,$term_id)
    {
        $student_id = $data['student_id'];
        $class_id = $data['class_id'];
        $subjects = $data['subjects'];
        $subject_scores = $data['subject_scores'];
        $insert_array = array();
        $update_data = array();
        $updated_record = FALSE;
        $record = array("session_id"=>$session_id,"term_id"=>$term_id,"class_id"=>$class_id,"student_id"=>$student_id);
        foreach($subjects as $subject_id)
        {   
            $ca1 = $subject_scores[$subject_id]['ca1'];
            $ca2 = $subject_scores[$subject_id]['ca2'];
            $exam = $subject_scores[$subject_id]['exam'];
            if($ca1 != 0 || $ca2 != 0 || $exam != 0)
            {
                $record_id = NULL;
                $record['subject_id'] = $subject_id;
                $scores = array('ca1'=>$ca1,'ca2'=>$ca2,'exam'=>$exam);
                $record_id = $this->record_exists('results', $record,true,"result_id");
                if($record_id != NULL && $record_id != FALSE && $record_id !== FALSE)
                {
                   $update_data[] = array_merge(array("result_id"=>$record_id),$scores);
                   $updated_record = TRUE;
                }
                else 
                {
                   $insert_array[] = array_merge($record,$scores);
                    
                }   
            }
        }
        if(sizeof($update_data) > 0) {
            $this->db->update_batch("results",$update_data,"result_id");
        }
        return $this->commit_result($insert_array, $updated_record);
    }
    

    
    function save_subject_result($result_data,$session_id,$term_id)
    {
        $students = $result_data['students'];
        $stud_score = $result_data['student'];
        $subject_id = $result_data['subject_id'];
        $class_id = $result_data['class_id'];
        $insert_array = array();
        $updated_record = FALSE;
        $update_data = array();
        $record = array("session_id"=>$session_id,"term_id"=>$term_id,"class_id"=>$class_id,"subject_id"=>$subject_id);
        foreach($students as $student_id){
            $record['student_id'] = $student_id;
            $stud_scores = $stud_score[$student_id];
            if($stud_scores['ca1'] >= 0 || $stud_scores['ca2'] >= 0 || $stud_scores['exam'] >= 0){
                 $scores = array(
                'ca1'=>$stud_score[$student_id]['ca1'],'ca2'=>$stud_score[$student_id]['ca2'],
                'exam'=>$stud_score[$student_id]['exam']);
                $record_id = $this->record_exists('results', $record,true,"result_id");
                if($record_id == FALSE){
                    $insert_array[] = array_merge($record,$scores);
                }
                else {
                    $update_data[] = array_merge(array("result_id"=>$record_id),$scores);
                    //$this->db->where($record);
                    //$this->db->update('results',$scores);
                    $updated_record = TRUE;
                }
            }
           
        }
        if(sizeof($update_data) > 0){
            $this->db->update_batch('results',$update_data,"result_id");
        }
        return $this->commit_result($insert_array, $updated_record);
    }
    
    
    
    function commit_result($insert_array,$updated_record = FALSE)
    {
        if(sizeof($insert_array) > 0) // insert new records
        {
            $this->db->insert_batch('results',$insert_array);
        }
        return (($this->db->affected_rows() > 0) || $updated_record) ? TRUE : FALSE;
    }
    
    
    function record_exists($table,$record,$return_key = FALSE,$key_col_name = NULL)
    {
        $rs = $this->db->get_where($table,$record);
        if($rs->num_rows() > 0 && $return_key){
            $data = $rs->row_array();
            return $data[$key_col_name];
        }
        return ($rs->num_rows() > 0) ? TRUE : FALSE;
    }
    
    
    /*
     * Gets result for a particular term in a session for a particular student
     */
    function get_student_result($student_id,$session_id,$term_id, $class_id = null)
    {

        if($class_id == null) {
          return null;
        }
        $sql = "SELECT b.*, c.subject_name, getStudentSubjectPosition({$session_id},{$term_id},{$class_id},{$student_id},b.subject_id, b.total_score) AS position,"
              . " d.* FROM subjects c, subject_teacher a "
              . " LEFT JOIN result_view b ON b.session_id = {$session_id} AND b.class_id = {$class_id} AND b.term_id = {$term_id}"
              . " AND b.subject_id = a.subject_id"
              . " AND b.student_id = {$student_id} LEFT JOIN subject_stats_view d ON d.session_id = b.session_id AND"
              . " d.term_id = b.term_id AND d.class_id = b.class_id AND d.subject_id = b.subject_id  WHERE a.class_id = {$class_id} AND a.subject_id = c.subject_id";
        $rs = $this->db->query($sql);
        $result = array();
        if($rs->num_rows() > 0)
        {
            foreach($rs->result_array() as $record)
            {
               //$record['position'] = $this->get_student_subject_position($record['total_score'], $record['subject_id'], $record['class_id'], $session_id, $term_id);
              $record['ca1'] = ($record['ca1'] == NULL) ? 0 : $record['ca1'];
              $record['ca2'] = ($record['ca2'] == NULL) ? 0 : $record['ca2'];
              $record['exam'] = ($record['exam'] == NULL) ? 0 : $record['exam'];
               $result[]  = $record;
            }
            return $result;
        }
        return NULL;
    }
    
    
    //Gets variosus statistics metrics for a subject for a class for a session and term
    function get_class_score_ranges($subject_id,$class_id,$session_id,$term_id = NULL)
    {
        $sql = "SELECT MAX(total_score) AS max_score, AVG(total_score) AS avg_score,"
                . "MIN(total_score) AS min_score FROM result_view WHERE subject_id = {$subject_id} "
                . "AND class_id = {$class_id} AND session_id = {$session_id}";
        $sql .= ($term_id != NULL) ? " AND term_id = {$term_id}" : "";
        
        //Cant remember why I grouped by student_id, but I removed it so that correct
        //values for min and max is shown
        //$sql .= " GROUP BY student_id";
        $rs = $this->db->query($sql);
        $result = $rs->row_array();
        return $result;
    }
    
    
    //Gets a students position for a particular subject in a particular class and session and term
    function get_student_subject_position($student_score,$subject_id = NULL,$class_id,$session_id,$term_id = NULL)
    {
        $this->db->select("SUM(total_score) AS total_score",FALSE);
        $this->db->where(array(
            'a.subject_id'=>$subject_id,
            'b.class_id'=>$class_id,
            'a.session_id'=>$session_id
        ));
        $this->db->where("a.student_id =",'b.id',FALSE);
        $this->db->where("b.deleted =",0);
        if($term_id != NULL) { $this->db->where('term_id',$term_id); }
        $this->db->order_by('total_score','DESC');
        $this->db->group_by('a.student_id');
        $this->db->from("student_biodata b, result_view a");
        $rs = $this->db->get();
        $score_values = $rs->result_array();
        $position = $this->linear_search($score_values, $student_score);
        if($position == NULL) { return sizeof($score_values); }
        return $position;
    }
    
    
    
    function get_student_result_summary($student_id,$class_id,$session_id,$term_id = NULL)
    {
        $sql = "SELECT SUM(total_score) AS total_score, SUM(100) AS max_obtainable, "
                . " AVG(total_score) AS avg_score, MAX(total_score) AS max_score,"
                . " MIN(total_score) AS min_score FROM result_view "
                . " WHERE result_view.class_id = {$class_id} AND session_id = {$session_id}"
                . " AND result_view.student_id = {$student_id} GROUP BY subject_id";

        $sql .= ($term_id != NULL && $term_id != "all") ? " AND term_id = {$term_id} " : '';
        $rs = $this->db->query($sql);
        $record = $rs->row_array();
		    $record['principals_comment'] = $this->get_principals_comment($record['avg_score']);
        return $record;
    }
    
    
    
    function get_class_students_result($class_id,$session_id,$term_id = NULL)
    {
        $term_id = ($term_id != NULL && $term_id != 'all') ? $term_id : 0;
        $sql = "CALL getClassStudentsResult({$class_id},{$session_id},{$term_id})";
        $rs = $this->db->query($sql);
        $students = array();
        if($rs->num_rows() > 0){
            $records = $rs->result_array();
            foreach($records as $row){
                $row['total_score'] =  ($row['total_score'] == NULL) ? 0 : number_format($row['total_score'],2,".","");
                $row['position'] = $this->linear_search($records, $row['total_score']);
                $row['average'] = ($row['total_score'] > 0) ? number_format(($row['total_score'] / $row['record_count']),2) : 0;
                $students[] = $row;
            }
        }
        $rs->next_result();
        $rs->free_result();
        return $students;
    }
    
    /**
     * Gets summary of scores for a student in a class. Gets values
     * such as class highest score, students position in class, class max score
     * , class min score
     * @param int $class_id ID of class to be selected
     * @param int $session_id Session where result is to be fetched
     * @param float $student_total_score the students total score to be compared
     * @param int $term_id The term to be selected, if null, only session is used
     * @return Array
     */
    function get_class_result_summary($class_id, $session_id, $student_total_score, $term_id = NULL)
    {
        $data = array();
        $sql = "SELECT SUM(total_score) AS total_score, SUM(100) AS total_obtainable_score FROM result_view a, student_biodata b WHERE "
                . "a.class_id = $class_id AND a.student_id = b.id AND b.deleted = 0  AND session_id = $session_id";
        $sql .= ($term_id != NULL && $term_id != "all") ? " AND term_id = $term_id " : '';
        $sql .= " GROUP BY student_id ORDER BY total_score DESC";

        $rs = $this->db->query($sql);
        $record = $rs->result_array();
        $first_row = $rs->row_array();
        $min_total = $max_total = $first_row['total_score'];
        $sum_total = $total_obtainable_score = 0;
        foreach($record as $row)
        {
            $total_obtainable_score += $row['total_obtainable_score'];
            $min_total = ($row['total_score'] < $min_total) ? $row['total_score'] : $min_total;
            $sum_total += $row['total_score'];
        }
        $num_of_records = (sizeof($record) > 0 ) ? sizeof($record) : 1;
        $data['position'] = $this->linear_search($record, $student_total_score);
        $data['percentage'] = $this->get_percentage($student_total_score,$total_obtainable_score);
        $data['max_score'] = $max_total;
        $data['min_score'] = $min_total;
        $data['avg_score'] = ($sum_total / $num_of_records);
        $data['total_score'] = $sum_total;
        return $data;
    }
    
    
    
    /** Retrieves result for a student for a particular session **
     * 
     */
    function get_student_session_result($student_id,$session_id,$terms,$class_id)
    {
       $result = array();
       $student_subjects = $this->get_student_subjects($student_id);
       $subject_results = array();
       if(sizeof($student_subjects) > 0){
        foreach($student_subjects as $subject)
        {
           $record['subject_name'] = $subject['subject_name'];
           $record['staff_name'] = $subject['staff_name'];
           $subject_total = 0;
           $subject_max_score = 0;
           $subject_min_score = 100;
           foreach($terms as $term)
           {
               $subject_term_score = $this->get_student_subject_score($student_id,$session_id,$term['school_term_id'],$subject['subject_id']);
               $subject_max_score = ($subject_term_score > $subject_max_score) ? $subject_term_score : $subject_max_score;
               $subject_min_score = ($subject_term_score < $subject_min_score) ? $subject_term_score : $subject_min_score;
               $record[$term['school_term_name']] = $subject_term_score;
               $subject_total += $subject_term_score;
           }
           $record['total_score'] = $subject_total;
           $record['max_score'] = $subject_max_score;
           $record['min_score'] = $subject_min_score;
           $record['avg_score'] = $subject_total / sizeof($terms);
           $grade_and_comment = $this->get_grade_and_comment($record['avg_score']);
           $record['grade'] = $grade_and_comment['grade'];
           $record['comment'] = $grade_and_comment['comment'];
           $record['position'] = $this->get_student_subject_position($subject_total, $subject['subject_id'], $class_id, $session_id);
           $subject_results[] = $record;
       }
       }
        $result['subject_results'] = $subject_results;
        return $result;
    }
    
    
    /**
     * To get the subjects offered by a student. Get the class, then subject
     * teacher, then subjects
     * @param type $student_id
     */
    function get_student_subjects($student_id)
    {
        $sql = "SELECT subjects.subject_id, subject_name, " . STAFF_NAME . " FROM subjects, subject_teacher"
                . ", student_biodata, staff WHERE student_biodata.id = $student_id AND "
                . "student_biodata.class_id = subject_teacher.class_id AND "
                . "subjects.subject_id = subject_teacher.subject_id AND staff.staff_id"
                . "= subject_teacher.staff_id GROUP BY subject_id ORDER BY subject_name ASC";
        $rs = $this->db->query($sql);
        return $rs->num_rows() > 0 ? $rs->result_array() : NULL;
    }
    
    
    function get_student_subject_score($student_id,$session_id,$term_id,$subject_id)
    {
        $sql = "SELECT (total_score) FROM result_view WHERE student_id = $student_id "
                . "AND session_id = $session_id AND term_id = $term_id AND subject_id = $subject_id";
        $rs = $this->db->query($sql);
        if($rs->num_rows() > 0)
        {
            $row = $rs->row_array();
            return $row['total_score'];
        }
        else return 0;
    }
    
    /* Used to search and get students position.
     * Linear search was used due to situations where multiple positions
     * have the same values. In that case there can be like 4 students 
     * having 4th position and the next student will have to be in 9th position
     * Linear Search handles that appropriately */
    function linear_search($data,$key)
    {
        for($i = 0; $i < sizeof($data); $i++)
        {
            if(number_format($data[$i]['total_score'],2) == number_format($key,2))
            {
                return $i + 1;
            }
        }
        return NULL;
    }
    
    /**
     * Saves Cognitive Skill
     */
    function save_skill($data)
    {
        $skills = explode(",",$data);
        $records = array();
        foreach($skills as $skill){
            $row =  array('skill'=>$skill);
            if(!$this->record_exists('cognitive_skills',$row)){
                   $records[] = $row;
            }
        }
        if(sizeof($records) > 0) {
            $this->db->insert_batch('cognitive_skills',$records);
        }
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }
    
    
    /** 
     * Gets all cognitive skills 
     */
    function get_skills(){
        $this->db->order_by('skill','ASC');
        $rs = $this->db->get('cognitive_skills');
        return $rs->num_rows() > 0 ? $rs->result_array() : NULL;
    }
    
    function delete_skill($record_id){
        $this->db->delete('cognitive_skills',array('skill_id'=>$record_id));
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }
    
    
    function add_grading($data){
        if(!$this->record_exists('gradings', $data)) {
            $this->db->insert('gradings',$data);
        }
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }
    
    
    function get_gradings()
    {
        $rs = $this->db->get('gradings');
        return $rs->num_rows() > 0 ? $rs->result_array() : NULL;
    }
    
    function delete_grade($record_id){
        $this->db->delete('gradings',array('grading_id'=>$record_id));
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }
    
    
    function save_student_display($data){
        $values = $this->get_student_data_display();
        $keys = array_keys($data);
        if(sizeof($data) > 0)
        {
            $records = array();
            foreach($values as $k=>$v){
               $val = (in_array($k,$keys)) ? 1 : 0;
               $records[] = array('field'=>$k,'value'=>$val);
            }
		   $rs = $this->db->get("result_display_settings");
		   if($rs->num_rows() > 0){
			   $this->db->update_batch('result_display_settings',$records,"field");
		   }
		   else {
			   $this->db->insert_batch('result_display_settings',$records);
		   }
           
        }
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }
    
    
    function get_student_data_display() {
        $rs = $this->db->get('result_display_settings');
        $records = array();
        if($rs->num_rows() > 0 ) { 
            foreach($rs->result_array() as $row)
            {
                $records[$row['field']] = $row['value'];
            }
            return $records;
        }
  		else {
  			$data = array();
  			$fields = array("full_name","class","gender","picture","date_of_birth","teacher_name","cognitive_skills");
  			foreach($fields as $field){
  				$data[$field] = 0;
  			}
  			return $data;
  		}
    }
    
    /**
     * 
     * @param int $data Containing Cognitive Skills Result
     * @param int $session_id Current session when result is being saved
     * @param int $term_id Current term when result is being saved
     */
    function save_cog_skills_result($data,$session_id,$term_id){
        $skills = $data['skills'];
        $skills_result = $data['skill_result'];
        $updated = FALSE;
        $insert_data = array(); // Array to hold all rows of cognitive skills and corresponding rating
        if(sizeof($skills) > 0) {
            foreach($skills as $skill){
                $record = array();
                $record['student_id'] = $data['student_id'];
                $record['session_id'] = $session_id;
                $record['term_id'] = $term_id;
                $record['skill_id'] = $skill;
                if(!$this->record_exists('cog_skills_result', $record)) { //If result has not been entered in database
                    $record['rating'] = $skills_result[$skill]['rating'];
                    $insert_data[] = $record; //add to record data
                }
                else { //Record has been addded, update rating score
                    $rating = $skills_result[$skill]['rating'];
                    $this->db->where($record);
                    $this->db->update("cog_skills_result",array('rating'=>$rating));
                    $updated = TRUE;
                }
            }
            if(sizeof($insert_data) > 0) {
                $this->db->insert_batch("cog_skills_result",$insert_data);
            }
        }
    }
    
    
    function get_cog_skills_result($student_id,$session_id,$term_id = NULL)
    {
        $sql = "SELECT cognitive_skills.skill_id,skill,AVG(rating) AS rating FROM cognitive_skills LEFT JOIN cog_skills_result ON"
                . " cognitive_skills.skill_id = cog_skills_result.skill_id AND student_id = {$student_id} "
        . "AND session_id = {$session_id} ";
        $sql .= ($term_id != NULL && $term_id != "all") ? " AND term_id = {$term_id}" : '';
        $sql .= " GROUP BY skill_id ORDER BY skill ASC";
        $rs = $this->db->query($sql);
        return $rs->num_rows() > 0 ? $rs->result_array() : NULL;
    }
    
    
    /**
     * 
     * @param type $data Array containing start positioin, end position and comment
     * to be saved in DB
     */
    function save_principals_comment($data){
        if(!$this->record_exists('principal_comments', $data)){
            $this->db->insert("principal_comments",$data);
        }
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
    
    
    /**
     * 
     * @return Array An Array of all principals comments
     */
    function get_principal_comments(){
        $this->db->order_by("start_average ASC, end_average ASC");
        $rs = $this->db->get("principal_comments");
        return $rs->num_rows() > 0 ? $rs->result_array() : NULL;
    }
    
    
    /**
     * 
     * @param int $comment_id ID of comment to be deleted
     * @return bool TRUE or FALSE if comment is deleted
     */
    function delete_principals_comment($comment_id){
        $this->db->where("comment_id",$comment_id);
        $this->db->delete("principal_comments");
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
    
    
    /**
     * 
     * @param int $average Gets the appropriate comment for a particular average score
     */
    function get_principals_comment($average){
		$average = floor($average);
        $this->db->where("start_average <= ",$average,FALSE);
        $this->db->where("end_average >= ",$average,FALSE);
        $rs = $this->db->get("principal_comments");
        if($rs->num_rows() > 0){
            $row = $rs->row_array();
            return $row['comment'];
        }
        else { //Percentage not found.
            return "";
        }
    }
    
    /**
     * Computes percentage score
     * @param  float $score Students Score
     * @param  float $total Max Obtainable Score
     * @return float Percentage Score 
     */
    function get_percentage($score,$total){
        $percentage = ($total > 0) ? ($score/$total) * 100 : 0;
        $output = number_format($percentage,2);
        return $output;
    }



    function get_grade_and_comment($score){
      $return_values = array("grade"=>"","comment"=>"");
      $sql = "SELECT * FROM gradings g WHERE g.from <= $score AND g.to >= $score";
      $rs = $this->db->query($sql);
      if($rs->num_rows() > 0){
         $data = $rs->row_array();
         $return_values["grade"] = $data['grade'];
         $return_values["comment"] = $data['comment'];
      }
      return $return_values;
    }
    
}