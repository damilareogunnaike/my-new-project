<?php

class Result_pins_model extends Crud_model {

	private $CI;
	public function __construct(){
		$this->load->model("Student_model", "Students");
		$this->CI = & get_instance();
		$this->load->library("Myapp");
	}


	public function get_generated(){
		$session_id = $this->myapp->get_current_session_id();
		$term_id = $this->myapp->get_current_term_id();

		$this->db->where("a.session_id", $session_id);
		$this->db->where("a.term_id", $term_id);

		$this->db->distinct();
		$this->db->select("b.class_id, b.class_name");

		$this->db->from("result_pins a");
		$this->db->join("classes b", "a.class_id = b.class_id");
		$this->db->order_by("b.class_name", "asc");
		$rs = $this->db->get();
		$data = $rs->result_array();
		return rest_success($data);
	}


	public function get_pins_for_students_in_class($class_id){
		$session_id = $this->myapp->get_current_session_id();
		$term_id = $this->myapp->get_current_term_id();

		$this->db->where("a.session_id", $session_id);
		$this->db->where("a.term_id", $term_id);
		$this->db->where("a.class_id",$class_id);

		$this->db->select("a.*, CONCAT(b.surname, ' ', b.first_name, ' ', b.middle_name) AS student_name");
		$this->db->from("result_pins a");
		$this->db->join("student_biodata b", "a.student_id = b.id");
		$this->db->order_by("student_name", "ASC");
		$rs = $this->db->get();
		return rest_success($rs->result_array());
	}


	public function delete_pins_for_students_in_class($class_id){
		$req_obj['session_id'] = $this->myapp->get_current_session_id();
		$req_obj['term_id'] = $this->myapp->get_current_term_id();
		$req_obj['class_id'] = $class_id;

		$this->db->where($req_obj);
		$this->db->delete("result_pins");
		return rest_success("Pins deleted.");
	}


	public function generate_for_class($class_id){
		$req_obj = array();
		$req_obj['session_id'] = $this->myapp->get_current_session_id();
		$req_obj['term_id'] = $this->myapp->get_current_term_id();
		$req_obj['class_id'] = $class_id;

		$pins_exist = $this->_get_where("result_pins", $req_obj);
		if($pins_exist) {
			return rest_error("Pins already generated for selection.");
		}

		$students = $this->Students->get_students_by_class_and_session($req_obj['class_id'], $req_obj['session_id']);

		if(sizeof($students) < 1){
			return rest_error("No students found for selection");
		}

		$data = array();
		$record = array("session_id"=>$req_obj['session_id'], "term_id"=>$req_obj['term_id'], "class_id"=>$req_obj['class_id']);

		foreach($students as $student){
			$student_id = $student['id'];
			$student_name = $student['student_name'];
			$key = $this->generate_pin($student_id, $student_name);
			$record['serial'] = $key['serial'];
			$record['pin'] = $key['pin'];
			$record['student_id'] = $student['id'];
			$data[] = $record;
		}

		$this->db->insert_batch("result_pins",$data);
		return rest_success("Pins generated successfully..");
	}


	private function generate_pin($student_id, $student_name){
        $student_id = str_pad($student_id, 5, "0", STR_PAD_LEFT);
        $names = explode(" ", $student_name);
        $prefix = substr($names[0], 0,1) . substr($names[1],0,1);
		$pin = strtoupper(substr(bin2hex(openssl_random_pseudo_bytes(10)), 0, 10));
		$pin = $prefix . $pin;
		$serial = $prefix . $student_id;
		return array("serial"=>$serial,"pin"=>$pin);
	}


	public function check_pin($req_data){

	    $this->db->select("a.student_id, CONCAT(b.surname, ' ', b.first_name) AS student_name");
        $this->db->where($req_data);

        $this->db->join("student_biodata b", "a.student_id = b.id");
        $rs = $this->db->get("result_pins a");

        if($rs->num_rows() > 0){
            $data = array();
            $data[0] = $rs->row_array();
            $data = array_merge($data, $this->get_related_students($req_data));
            return rest_success($data);
        }
        else {
            return rest_error("Invalid pin or serial");
        }
    }


    private function get_related_students($req_data) {
	    return array();
    }
}
