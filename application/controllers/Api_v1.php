<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "controllers/Rest_Ctrl.php");

class Api_v1 extends Rest_Ctrl {
    
    public $User;
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model("User_model","Users");
        $this->load->model("Staff_model","Staff");
        $this->load->model("Parent_model","Parents");
        
        $this->User = $this->Users->get($this->session->userdata("username"));
    }
    
    
    /* USER DETAILS */
    public function user_get(){
        $user_info = $this->User->toArray();
        $this->response(array("user"=>$user_info));
    }


    /* SCHOOL INFORMATION */

    public function school_info_get(){
        $this->load_model("School");
        $info = $this->School->get_info();
        $this->response(array("info"=>$info));
    }
    

    /* SUBJECTS */
    public function school_setup_get($which){
        $data = array();
        $this->load->model("School_setup_model","School_setup");
        switch($which){
            case "subjects":
                $data = $this->School_setup->get_all_subjects();
                break;
            case "classes":
                $data = $this->School_setup->get_all_classes();
                break;
            case "sessions":
                $data = $this->School_setup->get_school_sessions();
                break;
            case "terms":
                $data = $this->School_setup->get_school_terms();
                break;
            case "class_subjects":
                $this->load->model("Classes_model","Classes");
                $req_data = $this->input->get();
                $data = $this->Classes->get_class_subjects($req_data);
                break;
        }
        $this->response(array("data"=>$data));
    }

    

    /* MESSAGING */
    public function messages_delete($message_id){
        $this->load->model("Messages_model","Messages");
        $status = $this->Messages->delete_message($this->User->getUserId(),$message_id);
        $this->response(array("status"=>$status));
    }
    

    public function messages_get() {
        $this->load->model("Messages_model","Messages");
        $get_data = $this->input->get();
        $method = "get_" . $get_data['category'];
        $messages = $this->Messages->$method($this->User->getUserId());
        $data = sizeof($messages) > 0 ? $messages : array();
        $this->response(array("messages"=>$data));
    }
    
    
    public function  messages_post(){
        $this->load->model("Messages_model","Messages");
        $form_data = $this->input->post();
        $form_data['sender_id'] = $this->User->getUserId();
        $message = $this->Messages->save($form_data);
        $status = ($message != NULL) ? TRUE : FALSE;
        $this->response(array("status"=>$status,"message"=>$message));
    }
    
    
    /* EVENTS */    
    public function events_get($id = null){
        $this->load->model("Events_model","Events");
        if($id != NULL){
            $event = $this->Events->get($id);
            $this->response(array("event"=>$event));
            return ;
        }
        $get_data = $this->input->get();
        if(sizeof($get_data) > 0){
            //Then special request sent. Probably one with month and date so as to get event
            //for that month and date
            $event = $this->Events->get_event_for_day($get_data);
            $this->response(array("events"=>$event));
        }
        else {
            $events = $this->Events->get_all();
            $this->response(array("events"=>$events));
        }
    }
    
    public function events_post(){
        $this->load->model("Events_model","Events");
        $form_data = $this->input->post();
        $event = $this->Events->save($form_data);
        $status = ($event != NULL) ? TRUE : FALSE;
        $this->response(array("status"=>$status,"event"=>$event));
    }
    
    
    public function events_delete($event_id){
        $this->load_model("Events");
        $status = $this->Events->delete($event_id);
        $this->response(array("status"=>$status));
    }
    
    
    /* BULK SMS */

    //Does message sending via selected API.
    //It is assumed that numbers have been filtered and validated prior to the
    //call of this function
    function sms_sender_post(){
        $msg = $this->input->post();
        $this->load_model("Sms");
        $response = $this->Sms->send_SMS($msg);
        $this->response($response);
    }


    function sms_contact_post(){
        $this->load_model("Sms");
        $data = $this->input->post();
        $contact = $this->Sms->save_contact($data);
        $this->response(array("contact"=>$contact));
    }
    
    
    function sms_contact_get(){
        $this->load_model("Sms");
        $contacts = $this->Sms->get_contacts();
        $this->response(array("contacts"=>$contacts == null ? array() : $contacts));
    }
    

    function sms_contact_delete($contact_id){
        $this->load_model("Sms");
        $status = $this->Sms->delete_contact($contact_id);
        $this->response(array("status"=>$status));
    }
    
    function sms_group_post(){
        $data = $this->input->post();
        $this->load_model("Sms");
        $group = $this->Sms->save_group($data);
        $this->response(array("group"=>$group));
    }
    
    function sms_group_put(){
       $data = $this->_put_args;
       $this->load_model("Sms");
       $group = $this->Sms->update_group($data['group']);
       $this->response(array("group"=>$group));
    }
    
    
    function sms_group_get(){
        $this->load_model("Sms");
        $groups = $this->Sms->get_groups();
        $staff_numbers = $this->Staff->get_mobile_numbers(",");
        $parent_numbers = $this->Parents->get_mobile_numbers(",");
        $groups[] = array("name"=>"SYS-STAFF","numbers" => $staff_numbers,"editable"=>FALSE);
        $groups[] = array("name"=>"SYS-PARENTS","numbers" => $parent_numbers,"editable"=>FALSE);
        $this->response(array("groups"=>$groups == null ? array() : $groups));
    }
    
    
    function sms_group_delete($group_id){
        $this->load_model("Sms");
        $status = $this->Sms->delete_group($group_id);
        $this->response(array("status"=>$status));
    }



    /* REPORTS */
    function reports_get($which){
        $this->load->model("Reports_model","Reports");
        $input_data = $this->input->get();
        $data = $this->Reports->get_report($which,$input_data);
        $this->response(array("data"=>$data,"input"=>$input_data));
    }



    private function load_model($model_name){
        $this->load->model($model_name . "_model",$model_name);
    }
    
    
}
