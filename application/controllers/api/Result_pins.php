<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once("ApiBase.php");

class Result_pins extends ApiBase {

	public function __construct() {
        parent::__construct();

        $this->load->model("results/Result_pins_model", "Result_pins");
        $this->load->model("Classes_model", "Classes");
        $this->load->model('School_setup_model','School_setup');
    }

    /**
    	Returns a list of all classes for which pin has been generated.
    */
    public function generated_get($class_id = null){
    	if(is_numeric($class_id)) {
    		$response = $this->Result_pins->get_pins_for_students_in_class($class_id);
    	}
    	else {
    		$response = $this->Result_pins->get_generated();	
    	}
    	$this->response($response);
    }

    public function generated_delete($class_id = null){
    	if(is_numeric($class_id)) {
    		$response = $this->Result_pins->delete_pins_for_students_in_class($class_id);
    	}
    	else {
    		$response = $this->Result_pins->get_generated();	
    	}
    	$this->response($response);
    }

    /**
    	Generates new pins for a class for a session and term. Expected input is an 
    	array of this form array("class_id"=>$val)
    */
    public function generate_get($entity = "class"){
		$req_obj = $this->input->get();
		switch($entity) {
			case "class":
				$response = $this->Result_pins->generate_for_class($req_obj['class_id']);
				break;
		}
		$this->response($response);
    }

    public function download_class_pins_get($class_id){
    	$response = $this->Result_pins->get_pins_for_students_in_class($class_id);
    	$class_name = $this->Classes->get_class_name($class_id);
    	$html = $this->get_pins_as_html_for_class($class_id, $class_name);
    	if($html != null){

 			$this->load->library("DompdfLib", null, "PDFLibrary");

	        $this->PDFLibrary->load_html($html);
	        if($this->PDFLibrary->convert()){
	        	$content = $this->PDFLibrary->get_output();
	        	$base64 = chunk_split(base64_encode($content));

	        	$file_name = "RESULT PINS - " . $class_name . ".pdf";
	        	$file_data = "data:application/pdf;base64,".$base64;
	        	$response = array("name"=>$file_name, "data"=>$file_data);
	        	$this->response(rest_success($response));
	        }
	        else {
	        	$this->response(rest_error("Unable to generate file.."));
	        }

    	}
    	else {
    		$this->response(rest_error("No pins have been generated for selection."));
    	}
    }

    public function download_all_pins_get(){

    	$generated = $this->Result_pins->get_generated();	
    	if(is_array($generated) && sizeof($generated['data'] > 0)){
            $this->load->library("DompdfLib", null, "PDFLibrary");

    		foreach($generated['data'] as $record){
    			$class_name = $this->Classes->get_class_name($record['class_id']);
    			$html = $this->get_pins_as_html_for_class($record['class_id'], $class_name);
    			$this->PDFLibrary->load_html($html);
    		}

    		if($this->PDFLibrary->convert()) {
    			$file_name = "RESULT_PINS_FOR_ALL_GENERATED_CLASSES";
    			$full_file_name = $this->config->item('pdf_upload_base') . $file_name . ".pdf";
    			$file_path = $this->PDFLibrary->get_output_file($file_name);
	        	$file = array("name"=>$file_name, "data"=>base_url($full_file_name));
	        	$this->response(rest_success($file));
    		}
    		else {
    			$this->response(rest_error("Unable to generate file at this time."));
    		}
    	}
    	else {
    		$this->response(rest_error("No pins have been generated!"));
    	}
    }

    private function get_pins_as_html_for_class($class_id, $class_name){
    	$response = $this->Result_pins->get_pins_for_students_in_class($class_id);
    	if(sizeof($response['data']) > 0){
    		$data = array('class_name'=>$class_name, 'pins'=>$response['data']);
 			$html = $this->load->view("print_template/pins",$data, TRUE);
 			return $html;
 		}
 		else {
 			return null;
 		}
    }

    public function student_pin_printout_get(){
        $req = $this->input->get();
        if(!isset($req['student_name']) || !isset($req['pin']) || !isset($req['serial'])){
            $this->response(rest_error("Invalid request"));
        }
        else {
            $this->load->library("DompdfLib", null, "PDFLibrary");
            $data = $req;
            $school_settings = $this->School_setup->get_school_settings();
            $data = array_merge($data, $school_settings);
            $data['session_name'] = $this->School_setup->get_session_name($data['session_id']);
            $data['term_name'] = $this->School_setup->get_term_name($data['term_id']);
            $data['class_name'] = $this->School_setup->get_class_name($data['class_id']);

            $logo_path = base_url($school_settings['school_logo']);
            $logo_data = file_get_contents($logo_path);
            $type = pathinfo($logo_path, PATHINFO_EXTENSION);
            $data['school_logo'] = 'data:image/' . $type . ';base64,' . base64_encode($logo_data);
            $html = $this->load->view("print_template/student_pin_printout", $data, TRUE);

            $file_path = "";
            try {
                $file_name = $req['student_name'];
                $file_path = $this->PDFLibrary->get_html_as_pdf_file($html, $file_name);
            }
            catch (Exception $e){
                //Something happened here..but currently I don't care...
            }
            $this->response(rest_success($file_path));
        }
    }

    public function class_pin_printout_get(){
        $req = $this->input->get();
        if(!isset($req['class_id'])){
            $this->response(rest_error("Invalid request. Please retry."));
        }
        else {
            $this->load->library("DompdfLib", null, "PDFLibrary");
            $curr_session_id = $this->myapp->get_current_session_id();
            $curr_term_id = $this->myapp->get_current_term_id();

            $student_pins = $this->Result_pins->get_by_session_term_class($curr_session_id, $curr_term_id, $req['class_id']);

            $html = "";
            $data['session_name'] = $this->School_setup->get_session_name($curr_session_id);
            $data['term_name'] = $this->School_setup->get_term_name($curr_term_id);
            $school_settings = $this->School_setup->get_school_settings();
            $data = array_merge($data, $school_settings);
            $data['school_logo'] = $this->get_image_data($school_settings['school_logo']);

            foreach($student_pins as $pin){
                $data['pin'] = $pin['pin'];
                $data['serial'] = $pin['serial'];
                $data['student_name'] = $pin['student_name'];
                $student_html = $this->load->view("print_template/student_pin_printout", $data, TRUE);
                $html  .= $student_html;
            }

            $file_name = $req['class_name'];
            $file_path = $this->PDFLibrary->get_html_as_pdf_file($html, $file_name);
            $this->response(rest_success($file_path));
        }
    }

    public function student_pin_get(){
        $req = $this->input->get();
        $student_id = $req['student_id'];
        $curr_session_id = $this->myapp->get_current_session_id();
        $curr_term_id = $this->myapp->get_current_term_id();

        $response = $this->Result_pins->get_by_session_term_student($curr_session_id, $curr_term_id, $student_id);
        $this->response($response);
    }

    public function student_pin_generate_get(){
        $req = $this->input->get();
        $student_id = $req['student_id'];
        $curr_session_id = $this->myapp->get_current_session_id();
        $curr_term_id = $this->myapp->get_current_term_id();

        $response = $this->Result_pins->generate_for_student($curr_session_id, $curr_term_id, $student_id);
        $this->response($response);
    }

    private function get_image_data($image){
        $logo_path = base_url($image);
        $logo_data = file_get_contents($logo_path);
        $type = pathinfo($logo_path, PATHINFO_EXTENSION);
        $data = 'data:image/' . $type . ';base64,' . base64_encode($logo_data);
        return $data;
    }

}