<?php 
	
	include_once("Crud_model.php");

	class Results_model extends Crud_model {

		private $CI;

		public function __construct(){
			parent::__construct();

			$this->CI = & get_instance();
			$this->CI->load->model("Student_model", "Student");
			$this->CI->load->model("SubjectSettings_model","SubjectSettings");
		}


		public function get_student_subject_scores($student_id, $session_id, $term_id, $class_id, $student_subjects) {
			
			$subject_scores = array();
			if(sizeof($student_subjects) > 0){
				foreach($student_subjects as $subject){
					$record = array("subject_id"=>$subject['subject_id'], "subject_name"=>$subject['subject_name']);
					$scores = $this->get_subject_score($student_id, $session_id, $term_id, $class_id, $subject['subject_id']);
					$record = array_merge($record, $scores);
					$subject_scores[] = $record;
				}
			}
			return $subject_scores;
		}


		public function get_subject_students_scores($subject_id, $class_id, $term_id, $session_id, $subject_students) {
			
			$student_scores = array();
			if(sizeof($subject_students) > 0){
				foreach($subject_students as $student){
					$record = array("student_id"=>$student['id'], "student_name"=>$this->CI->Student->get_name($student));
					$scores = $this->get_subject_score($student['id'], $session_id, $term_id, $class_id, $subject_id);
					$record = array_merge($record, $scores);
					$record['subject_id'] = $subject_id;
					$student_scores[] = $record;
				}
			}
			return $student_scores;
		}


		public function get_subject_score($student_id, $session_id, $term_id, $class_id, $subject_id){
			$where_clause = array("student_id"=>$student_id, "session_id"=>$session_id, 
				"term_id"=>$term_id, "subject_id"=>$subject_id, "class_id"=>$class_id);
			$rs = $this->_get_where("results", $where_clause);

			$scores = array("ca1"=>0, "ca2"=>0, "exam"=>0, "total_score"=>0);
			if(is_array($rs) && sizeof($rs) > 0){
				$record = $rs[0];
				$scores['ca1'] = floatval($record['ca1']);
				$scores['ca2'] = floatval($record['ca2']);
				$scores['exam'] = floatval($record['exam']);
				$scores['total_score'] = ($scores['ca1'] + $scores['ca2'] + $scores['exam']);
			}

			return $scores;
		}


		public function save_student_subject_scores($session_id, $term_id, $class_id, $student_id, $scores){

			$record = array("session_id"=>$session_id, "term_id"=>$term_id, "class_id"=>$class_id, 
				"student_id"=>$student_id);

			$insert_data = array();
			$updated = false;

			foreach($scores as $score){
				$result_record = $record;
				$result_record['subject_id'] = $score['subject_id'];
				$result_record['ca1'] = $score['ca1'];
				$result_record['ca2'] = $score['ca2'];
				$result_record['exam'] = $score['exam'];

				$record['subject_id'] = $score['subject_id'];

				if($this->_record_exists("results", $record)){
					$record['subject_id'] = $score['subject_id'];
					$this->_update("results", $record, $result_record);
					$updated = true;
				}
				else {
					$insert_data[] = $result_record;
				}
			}

			if(sizeof($insert_data) > 0){
				$this->db->insert_batch("results", $insert_data);
			}
			return ($this->db->affected_rows() > 0 || $updated) ? TRUE : FALSE; 
		}


		public function save_class_subject_scores($session_id, $term_id, $class_id, $subject_id, $scores){

			$record = array("session_id"=>$session_id, "term_id"=>$term_id, "class_id"=>$class_id, 
				"subject_id"=>$subject_id);

			$insert_data = array();
			$updated = false;

			foreach($scores as $score){
				$result_record = $record;
				$result_record['student_id'] = $score['student_id'];
				$result_record['ca1'] = $score['ca1'];
				$result_record['ca2'] = $score['ca2'];
				$result_record['exam'] = $score['exam'];

				$record['student_id'] = $score['student_id'];

				if($this->_record_exists("results", $record)){
					$this->_update("results", $record, $result_record);
					$updated = true;
				}
				else {
					$insert_data[] = $result_record;
				}
			}

			if(sizeof($insert_data) > 0){
				$this->db->insert_batch("results", $insert_data);
			}
			return ($this->db->affected_rows() > 0 || $updated) ? TRUE : FALSE; 
		}



		/**
		Computes result overview for all students in a class....
		Metrics calculated are: Total score of student, Avg_score, Min_score, Max_score.
		This is done to increase report generation speed!
		*/
		public function compute_student_result($session_id, $term_id, $class_id, $class_students){

			$response = array("status"=>false);
			$updated_records = 0;
			if(is_array($class_students) && sizeof($class_students) > 0){
				$insert_data = array();
				$update_data = array();

				$record = array("session_id"=>$session_id, "term_id"=>$term_id, "class_id"=>$class_id);

				foreach($class_students as $student){
					$student_id = $student['student_id'];
					$record['student_id'] = $student_id;
					$student_result_overview = $this->calculate_student_result_overview($session_id, $term_id, $class_id, $student_id);

					$existing_record = $this->_get_if_exists("student_result_overview", $record);
					if($existing_record != null){

						$update_record = $student_result_overview;
						$update_record['record_id'] = $existing_record['record_id'];
						$update_data[] = $update_record;
						$updated_records += 1;

					}
					else {
						$insert_data[] = array_merge($record, $student_result_overview);
					}
				}

				if(sizeof($update_data) > 0){	
					$this->db->update_batch("student_result_overview", $update_data, "record_id");
				}

				if(sizeof($insert_data) > 0){
					$this->db->insert_batch("student_result_overview", $insert_data);
				}

				$records_affected = $this->db->affected_rows() + $updated_records;

				if($records_affected > 0){
					$response['status'] = true;
					$response['records'] = $records_affected;
				}
			}
			return $response;

		}


		public function calculate_student_result_overview($session_id, $term_id, $class_id, $student_id){

			$overview = array(
				"subjects_count"=>0,
				"max_score"=>0,
				"min_score"=>0,
				"avg_score"=>0,
				"total_score"=>0,
				);

			$student_subjects = $this->SubjectSettings->get_student_subjects($student_id, $session_id, $class_id);

			if(is_array($student_subjects) && sizeof($student_subjects) > 0) {

				$overview['subjects_count'] = sizeof($student_subjects);

				$subject_ids = get_array_values($student_subjects, "subject_id");
				$subject_ids_str = implode(",", $subject_ids);

				$total_score_str = "(ca1 + ca2 + exam)";

				$this->db->select("MAX({$total_score_str}) AS max_score");
				$this->db->select("MIN({$total_score_str}) AS min_score");
				$this->db->select("AVG({$total_score_str}) AS avg_score");
				$this->db->select("SUM({$total_score_str}) AS total_score");

				$this->db->where_in("subject_id", $subject_ids_str, FALSE);

				$this->db->where("session_id",$session_id);
				$this->db->where("term_id",$term_id);
				$this->db->where("class_id",$class_id);
				$this->db->where("student_id",$student_id);

				$rs = $this->db->get("results");

				if($rs->num_rows() > 0){
					$record = $rs->row_array();
					$overview['total_score'] = doubleval($record['total_score']);
					$overview['avg_score'] = doubleval($record['avg_score']);
					$overview['max_score'] = doubleval($record['max_score']);
					$overview['min_score'] = doubleval($record['min_score']);
				}
			}
			return $overview;

		}



		/**
		This computes each student's position per subject in a class.
		*/
		public function compute_subject_result($session_id, $term_id, $class_id){
			//Get class subjects..
			$response = array("status"=>true);
			$class_subjects = $this->SubjectSettings->get_class_subjects($class_id, $session_id, TRUE); //i.e. TRUE means return both compulsory and optional subjects..
			
			if(is_array($class_subjects) && sizeof($class_subjects) > 0){
				foreach ($class_subjects as $subject) {
					$subject_id = $subject['subject_id'];
					$this->compute_class_subject_position($session_id, $term_id, $class_id, $subject_id);
				}
			}
			return $response;

		}


		public function compute_class_subject_position($session_id, $term_id, $class_id, $subject_id){

			$insert_data = array();
			$update_data = array();

			$record = array(
				"session_id"=>$session_id,
				"term_id"=>$term_id,
				"class_id"=>$class_id,
				"subject_id"=>$subject_id
				);


			$subject_students = $this->Students->get_by_session_and_class_and_subject($session_id, $class_id, $subject_id);
			
			$subject_student_scores = $this->get_subject_students_scores($subject_id, $class_id, $term_id, $session_id, $subject_students);


			$student_scores = get_array_values($subject_student_scores, "total_score");

			$subject_student_scores = sort_multi_array($subject_student_scores, "scores", "desc");

		

			$position = 1;
			$index = 1;

			if(is_array($subject_student_scores) && sizeof($subject_student_scores) > 0){

				$min_score = $subject_student_scores[0]['total_score'];

				foreach($subject_student_scores as $score_record){
					$subject_record = array();
					$total_score = 0;
					$student_id = $score_record['student_id'];
					$total_score = floatval($score_record['total_score']);
					
					if($total_score < $min_score) {
						$position = $index;
						$min_score = $total_score;
					}

					$index++;

					$subject_record['total_score'] = $total_score;
					$subject_record['position'] = $position;

					$record['student_id'] = $subject_record['student_id'] = $student_id;


					$existing_record = $this->_get_if_exists("subject_result_overview", $record);

					if($existing_record != null) {
						$update_record = array("record_id"=>$existing_record['record_id'], 
							"total_score"=>$total_score, "position"=>$position);
						$update_data[] = $update_record;
					}
					else {
						$insert_data[] = array_merge($record, $subject_record);
					}
					
				}

				if(sizeof($update_data) > 0){
					$this->db->update_batch("subject_result_overview", $update_data, "record_id");
				}

				if(sizeof($insert_data) > 0){
					$this->db->insert_batch("subject_result_overview", $insert_data);
				}
			}
			return true;
		}


		public function compute_class_result_overview($session_id, $term_id, $class_id, $class_students){
			
			$class_students_overview = $this->get_student_result_overview($session_id, $term_id, $class_id, $class_students);

			sort_multi_array($class_students_overview, "avg_score", "desc");

			$class_students_overview = array_reverse($class_students_overview);

			$update_data = array();
			$insert_data = array();

			$record = array("session_id"=>$session_id, "term_id"=>$term_id, "class_id"=>$class_id);
			$updated_records = 0;

			$min_score = $class_students_overview[0]['avg_score'] + 1;
			$position = 1;
			$index = 1;
			$stud_record = array();
			foreach($class_students_overview as $overview){
				$avg_score = $overview['avg_score'];
				$max_obtainable = $overview['subjects_count'] * 100;
				$total_score = floatval($overview['total_score']);

				if($avg_score < $min_score){
					$position = $index;
					$min_score = $avg_score;
				}

				$stud_record['max_obtainable'] = $max_obtainable;
				$stud_record['total_score'] = $total_score;
				$stud_record['position'] = $position;
				$stud_record['percentage'] = ($total_score / $max_obtainable) * 100;
				$stud_record['avg_score'] = $avg_score;

				$index++;


				$record['student_id'] = $overview['student_id'];
				$existing_record = $this->_get_if_exists("student_class_result_overview", $record);

				if($existing_record != null){
					$update_record = $stud_record;
					$update_record['record_id'] = $existing_record['record_id'];
					$update_data[] = $update_record;
					$updated_records ++;
				}
				else {
					$insert_data[] = array_merge($record, $stud_record);
				}
			}
			if(sizeof($update_data) > 0){
				$this->db->update_batch("student_class_result_overview", $update_data, "record_id");
			}

			if(sizeof($insert_data) > 0){
				$this->db->insert_batch("student_class_result_overview", $insert_data);
			}

			$records_affected = $updated_records + $this->db->affected_rows();

			return $records_affected > 0 ? array("status"=>true, "records"=>$records_affected) : array("status"=>false);
		}


		public function get_student_result_overview($session_id, $term_id, $class_id, $class_students){

			$student_ids = get_array_values($class_students, "student_id");
			$student_ids_str = implode(",", $student_ids);

			$this->db->where("session_id", $session_id);
			$this->db->where("term_id", $term_id);
			$this->db->where("class_id", $class_id);

			$this->db->where_in("student_id", $student_ids_str, false);

			$rs = $this->db->get("student_result_overview");

			return $rs->num_rows() > 0 ? $rs->result_array() : array();


		}
	}
?>