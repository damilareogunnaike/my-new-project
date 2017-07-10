<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once("ApiBase.php");

class Result_pins extends ApiBase {

	public function __construct() {
        parent::__construct();

        $this->load->model("results/Result_pins_model", "Result_pins");
        $this->load->model("Classes_model", "Classes");
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


    public function print_class_pins_get($class_id){
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


    public function print_all_pins_get(){

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

}