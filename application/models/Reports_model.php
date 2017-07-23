<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("Crud_model.php");


class Reports_model extends Crud_model{
    
    private $CI;

    public function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->CI->load->model("Classes_model", "Classes");
        $this->CI->load->model("School_setup_model", "School_setup");
    }
    
  
    public function get_report($which,$input_data){
        switch ($which) {
            case "subject-performance":
                return $this->get_subject_performance_report($input_data);
        }
    }

    function get_subject_performance_report($selections){
        $classes = implode($selections["classes"],",");
        $session_id = $selections['session_id'];
        $term_id = ($selections['term_id'] == "all") ? 0 : $selections["term_id"];
        $subjects = $this->remove_duplicates($selections['subjects']);
        $subject_ids = implode(array_column($subjects,"subject_id"),",");
        $num_of_subjects = sizeof($subjects);
        $this->db->insert_batch("search_subjects",$subjects);
        $data = $this->_query("CALL getStudentSubjectPerformance({$session_id},{$term_id},'{$classes}')");
        $this->db->close();
        $divisor = ($term_id == "all") ? 3 : 1;
        if(sizeof($data) > 0){
            $req_subjects = array();
            foreach ($data  as $key => $value) {
                $sql = "SELECT (SUM(ca1+ca2+exam) / {$divisor}) AS total_score FROM results a , subjects b WHERE a.session_id = {$session_id} AND a.student_id = {$value['student_id']} ";
                $sql .= ($term_id == "all") ? "" : " AND term_id = {$term_id}";
                $sql .= " AND a.subject_id IN ({$subject_ids}) AND a.subject_id = b.subject_id GROUP BY a.subject_id ORDER BY b.subject_name ASC";
                $data[$key]['scores'] = $this->_query($sql);
            }
        }
        $req_subjects = $this->_query("SELECT * FROM subjects a WHERE a.subject_id IN({$subject_ids}) ORDER BY a.subject_name ASC");
        return array("report"=>$data,"subjects"=>$req_subjects);
    }


    /*
    function get_subject_performance_report($selections){
        $classes = implode($selections["classes"],",");
        $session_id = $selections['session_id'];
        $term_id = $selections['term_id'];
        $subjects = $this->remove_duplicates($selections['subjects']);
        $subjects_clause = "";
        $subject_ids = "";
        foreach($subjects as $i=>$subject){
            $subject_id = $subject['subject_id'];
            $subject_ids .= $subject_id .",";
            $from = $subject['from'];
            $to = $subject['to'];

            $subjects_clause .= " WHEN $subject_id THEN total_score >= {$from} AND total_score <= {$to} ";
        }
        $num_of_subjects = sizeof($subjects) * ($term_id == "all") ? 3 : 1;
        $sql = "SELECT SUM(total_score) AS total_score, (SUM(total_score) / {$num_of_subjects}) AS avg_score, CONCAT(b.surname,' ',b.first_name) AS student_name, a.student_id, COUNT(a.subject_id) AS subject_count ";
        $sql .= "FROM result_view a, student_biodata b WHERE a.class_id IN({$classes}) AND a.session_id = {$session_id}";
        $sql .= ($term_id == "all") ? "" : " AND a.term_id = {$term_id} ";
        $sql .= " AND CASE subject_id ";
        $sql .= $subjects_clause;
        $sql .= " END AND b.id = a.student_id GROUP BY a.student_id ORDER BY avg_score DESC";
        $sql = "SELECT * FROM ({$sql}) AS my_table WHERE my_table.subject_count = {$num_of_subjects}";
        $data = $this->_query($sql);
        $subject_ids = substr($subject_ids,0,strlen($subject_ids) - 1);
        echo $sql;
        if(sizeof($data) > 0){
            $req_subjects = array();
            foreach ($data  as $key => $value) {
                $sql = "SELECT AVG(ca1+ca2+exam) AS total_score FROM results a, subjects b WHERE a.session_id = {$session_id} AND a.student_id = {$value['student_id']} ";
                $sql .= ($term_id == "all") ? "" : " AND term_id = {$term_id}";
                $sql .= " AND a.subject_id IN ({$subject_ids}) AND a.subject_id = b.subject_id ORDER BY subject_name ASC";
                $data[$key]['scores'] = $this->_query($sql);
            }
        }
        $req_subjects = $this->_query("SELECT * FROM subjects a WHERE a.subject_id IN({$subject_ids}) ORDER BY a.subject_name ASC");
        return array("report"=>$data,"subjects"=>$req_subjects);
    }
    */

    function remove_duplicates($object){
        $new_object = array();
        foreach($object as $k=>$v){
            if(!in_array($new_object,$v)){
                $new_object[] = array("subject_id"=>$v["subject_id"],"start"=>$v['from'],"end"=>$v["to"]);
            }
        }
        return $new_object;
    }




    /* NEW BEGINNING */

    public function get_students_subject_report($session_id, $term_id, $class_id, $student_id, $student_subjects){

        if($term_id == "all"){
            return $this->get_students_subject_report_cummulative($session_id, $class_id, $student_id, $student_subjects);
        }

        $subject_ids = get_array_values($student_subjects, "subject_id");

        $where_clause = " WHERE session_id = {$session_id} AND term_id = {$term_id} AND class_id = {$class_id} AND t.subject_id = a.subject_id";

        $max_query = "SELECT MAX(total_score) FROM subject_result_overview t" . $where_clause;
        $min_query = "SELECT MIN(total_score) FROM subject_result_overview t" . $where_clause;
        $avg_query = "SELECT AVG(total_score) FROM subject_result_overview t" . $where_clause;

        $this->db->select("IFNULL(b.ca1,0) AS ca1, IFNULL(b.ca2,0) AS ca2, IFNULL(b.exam,0) AS exam, IFNULL(c.total_score,0) AS total_score, IFNULL(c.position,0) AS position, a.subject_name");
        $this->db->select("({$max_query}) AS class_max_score");
        $this->db->select("({$min_query}) AS class_min_score");
        $this->db->select("({$avg_query}) AS class_avg_score");

        $on_clause = " a.subject_id = b.subject_id ";
        $on_clause .= "AND b.session_id = {$session_id} ";
        $on_clause .= "AND b.term_id = {$term_id} ";
        $on_clause .= "AND b.class_id = {$class_id} ";
        $on_clause .= "AND b.student_id = {$student_id} ";

        $on_clause_2 = " b.student_id = c.student_id ";
        $on_clause_2 .= "AND b.subject_id = c.subject_id ";
        $on_clause_2 .= "AND c.session_id = {$session_id} ";
        $on_clause_2 .= "AND c.term_id = {$term_id} ";
        $on_clause_2 .= "AND c.class_id = {$class_id} ";

        $this->db->from("subjects a");
        $this->db->where_in("a.subject_id", $subject_ids);
        $this->db->join("results b", $on_clause, "left");

        $this->db->join("subject_result_overview c", $on_clause_2,"left");

        $rs = $this->db->get();

        return $rs->num_rows() > 0 ? $rs->result_array() : NULL;
    }

    public function get_students_subject_report_cummulative($session_id, $class_id, $student_id, $student_subjects){
        /*
        Expected response = array("
        subject_name", 1st term, second term, third term, avg, position)
        subject_name", 1st term, second term, third term, avg, position)
        subject_name", 1st term, second term, third term, avg, position)
        */
    }

    public function get_students_report_overview($session_id, $term_id, $class_id, $student_id){

        $this->db->from("student_result_overview a, student_class_result_overview b");

        $this->db->where("a.session_id",$session_id);
        $this->db->where("a.term_id",$term_id);
        $this->db->where("a.class_id",$class_id);
        $this->db->where("a.student_id",$student_id);

        $this->db->where("b.session_id",$session_id);
        $this->db->where("b.term_id",$term_id);
        $this->db->where("b.class_id",$class_id);
        $this->db->where("b.student_id",$student_id);

        $this->db->where("a.student_id = b.student_id");

        $rs = $this->db->get();
        if($rs->num_rows() > 0){
            $overview = $rs->row_array();
            $overview['comment'] = $this->get_comment_for_score($overview['avg_score']);
            return $overview;
        }
        else {
            return NULL;
        }
    }


    public function get_class_report_overview($session_id, $term_id, $class_id){

        $class_size = $this->CI->Classes->get_class_size($class_id, $session_id);

        $this->db->from("student_class_result_overview a");

        $this->db->select("{$class_size} AS class_size");
        $this->db->select("MAX(avg_score) AS max_score");
        $this->db->select("MIN(avg_score) AS min_score");
        $this->db->select("AVG(avg_score) AS avg_score");

        $this->db->where("a.session_id",$session_id);
        $this->db->where("a.term_id",$term_id);
        $this->db->where("a.class_id",$class_id);

        $rs = $this->db->get();
        return $rs->num_rows() > 0 ? $rs->row_array() : NULL;
       
    }


    function get_comment_for_score($average){
        $average = floor($average);

        $this->db->where("start_average <= ",$average,FALSE);
        $this->db->where("end_average >= ",$average,FALSE);

        $rs = $this->db->get("principal_comments");
        if($rs->num_rows() > 0){
            $row = $rs->row_array();
            return $row['comment'];
        }
        else { 
            return "";
        }
    }

    public function get_class_students_report($session_id, $term_id, $class_id){

        if($term_id == "all"){
            return $this->get_class_students_report_for_session($session_id, $class_id);
        }

        $this->db->select("CONCAT(b.surname, ' ' , b.middle_name, ' ', b.first_name) AS student_name, a.*, a.avg_score AS average");
        $this->db->from("student_class_result_overview a");

        $this->db->where("a.session_id", $session_id);
        $this->db->where("a.class_id", $class_id);
        $this->db->where("a.term_id", $term_id);
        $this->db->where("b.deleted", 0);

        $this->db->join("student_biodata b", "a.student_id = b.id");

        $this->db->order_by("a.avg_score", "DESC");

        $rs = $this->db->get();
        return $rs->num_rows() > 0 ? $rs->result_array() : array();
    }

    public function get_class_students_report_for_session($session_id, $class_id){
        $this->db->select("CONCAT(b.surname, ' ' , b.middle_name, ' ', b.first_name) AS student_name, a.student_id, GROUP_CONCAT(a.total_score) as total_scores, GROUP_CONCAT(a.avg_score) as averages,
                        GROUP_CONCAT(a.max_obtainable) as max_obtainables, GROUP_CONCAT(a.percentage) as percentages,
                        GROUP_CONCAT(a.term_id) as terms, SUM(a.total_score) as total_score, ROUND(AVG(a.avg_score),2) AS avg_score,
                        AVG(a.percentage) as percentage, SUM(a.max_obtainable) AS max_obtainable");
        $this->db->from("student_class_result_overview a");
        $this->db->where(array("a.session_id"=>$session_id, "a.class_id"=>$class_id));
        $this->db->group_by("a.student_id");
        $this->db->join("student_biodata b", "a.student_id = b.id");
        $this->db->order_by("avg_score", "DESC");

        $rs = $this->db->get();

        if($rs->num_rows() > 0){

            $all_terms = $this->CI->School_setup->get_school_terms();

            $last_position = 0;
            $last_lowest_score = 2147483647;

            $result_terms = [];
            $terms = [];
            foreach($rs->result_array() as $row){
                $student = array("student_name"=>$row['student_name']);

                $term_arrangement = $row['terms'];
                $terms = explode(",", $term_arrangement);
                $total_scores = explode(",", $row['total_scores']);
                $scores = [];
                $index = 0;
                foreach($terms as $term){
                    $scores[$term] = $total_scores[$index++];
                }

                ksort($scores);

                $student['student_id'] = $row['student_id'];
                $student['scores'] = $scores;
                $avg_score = $row['avg_score'];
                $student['total_score'] = $row['total_score'];
                $student['average'] = $row['avg_score'];
                $student['percentage'] = $row['percentage'];

                if($avg_score < $last_lowest_score){
                    $last_position += 1;
                    $last_lowest_score = $avg_score;
                }

                $student['position'] = $last_position;

                $student_scores[] = $student;
            }


            if(is_array($terms)){
                foreach($all_terms as $term){
                    if(in_array($term['school_term_id'], $terms)){
                        $result_terms[$term['school_term_id']] = $this->CI->School_setup->get_term_name($term['school_term_id']);
                    }
                }
            }
            $response['terms'] = $result_terms;
            $response['term_keys'] = array_keys($result_terms);
            $response['student_scores'] = $student_scores;
            return $response;
        }
        return [];

    }

    function get_cog_skills_report($student_id,$session_id,$term_id = NULL){

        $this->db->select("a.skill_id, a.skill, AVG(b.rating) as rating");

        $this->db->from("cognitive_skills a");

        $join_clause = "a.skill_id = b.skill_id AND b.session_id = {$session_id} AND b.student_id = {$student_id}";
        $join_clause .= ($term_id != "all") ? " AND b.term_id = {$term_id}" : "";
        $this->db->join("cog_skills_result b ", $join_clause, "left");
        $this->db->group_by("a.skill_id");
        $this->db->order_by("a.skill", "desc");

        $rs = $this->db->get();

        return $rs->num_rows() > 0 ? $rs->result_array() : array();
    }


}

