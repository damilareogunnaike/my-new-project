<?php

/**
 * Created by PhpStorm.
 * User: Lahray
 * Date: 6/27/2017
 * Time: 12:54 AM
 */
require_once "ApiBase.php";
class Parents extends ApiBase {

    public function __construct(){
        parent::__construct();

        $this->load->model("results/Result_pins_model", "Result_pins");
    }

    public function access_token_get(){
        $req_data = $this->input->get();
        $data = $this->Result_pins->check_pin($req_data);
        if($data['success']) {
            $students = $data['data'];
            $response = array("success"=>"true");
            $response['data']['primary'] = $students[0];
            $response['data']['primary']['token'] = $this->myjwt->encode($students[0]);
            $response['data']['students'] = [];
            foreach($students as $student){
                $response['data']['students'][] = [
                    "student_name" => $student['student_name'],
                    "token" => $this->myjwt->encode($student)
                ];
            }
            $this->response($response);
        }
        else {
            $this->response($data);
        }
    }


    public function student_result_get(){
        $req_data = $this->input->get();
    }
}