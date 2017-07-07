<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Finance_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    

    function add_payment_purpose($data)
    {
        $rs = $this->db->get_where('payment_purpose',$data);
        if($rs->num_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            $this->db->insert('payment_purpose',$data);
            return $this->db->affected_rows() > 0 ? TRUE : FALSE;
        }
    }

    
    function del_payment_purpose($purpose_id){
        $this->db->delete("payment_purpose",array("id"=>$purpose_id));
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }
    

    function get_fees_purposes()
    {
        $rs = $this->db->get('payment_purpose');
        return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
    }

    
    function set_fees($data)
    {
        $row_data = array();
        foreach($data['class_id'] as $class_id)
        {
            $record = array('class_id'=>$class_id,
                'payment_purpose_id'=>$data['payment_purpose_id']);
            if(!$this->record_exists('fees_settings', $record))
            {
                $record['amount'] = $data['amount'];
                $row_data[] = $record;
            }
        }
        if(sizeof($row_data) > 0)
        {
            $this->db->insert_batch('fees_settings',$row_data);
        }
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
    

    function get_fees_settings($data)
    {
        extract($data);
        if($class_id != "-1") { $this->db->where('fees_settings.class_id',$class_id); }
        if($payment_purpose_id != "-1") { $this->db->where('payment_purpose_id',$payment_purpose_id); }
        $this->db->where(array(
            'classes.class_id'=>'fees_settings.class_id',
            'payment_purpose.id'=>'fees_settings.payment_purpose_id'
        ),NULL,FALSE);
        $this->db->order_by('class_name','ASC');
        $this->db->order_by('purpose','ASC');
        $this->db->from('classes,payment_purpose,fees_settings');
        $this->db->select('fees_settings.id AS fees_settings_id,class_name,purpose,amount');
        $rs = $this->db->get();
        return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
    }

    
    function del_fees_settings($settings_id)
    {
        $this->db->delete('fees_settings',array('id'=>$settings_id));
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }
    

    function get_pending_fees($student_id)
    {

    }
    

    function record_exists($table,$data)
    {
        $rs = $this->db->get_where($table,$data);
        return ($rs->num_rows() > 0) ? TRUE : FALSE;
    }
    

    //Retrieves the fees to be paid by students in a class
    /**
     * 
     * @param type $class_id
     * @param type $curr_session
     * @param type $curr_term
     * @return Array Array of fees required to be paid
     */
    function get_required_fees($class_id,$curr_session,$curr_term)
    {
        $this->db->where('fees_settings.class_id',$class_id);
        $this->db->where(array(
            'fees_settings.class_id'=>'classes.class_id',
            'fees_settings.payment_purpose_id'=>'payment_purpose.id'
                ),NULL,FALSE);
        $this->db->from('fees_settings,classes,payment_purpose');
        $this->db->select('class_name,purpose,amount');
        $rs = $this->db->get();
        return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
    }
    

    function get_paid_fees($student_id,$curr_session_id,$curr_term_id)
    {
        $this->db->select('SUM(amount) as total_amount_paid');
        $rs = $this->db->get_where('fees_payment',array(
            'student_id'=>$student_id,
            'session_id'=>$curr_session_id,
            'term_id'=>$curr_term_id
                ));
        if($rs->num_rows() > 0)
        {
            $row = $rs->row_array();
            return $row['total_amount_paid'] == NULL ? 0 : $row['total_amount_paid'];
        }
        else return 0;
    }

    /**
     * 
     * @param int $student_id ID of student for whom payment is being recorded
     * @param int $amount   Amount to be paid
     * @param String $bank_name Bank where payment was made
     * @param int $teller_no Teller Number of bank where payment was made
     * @param int $curr_session_id Session for which payment is being recorded
     * @param int $curr_term_id Term for which fees is being recorded
     * @return boolean TRUE if successful, FALSE if not successful
     */
    function pay_fees($payment_data,$curr_session_id,$curr_term_id)
    {
        $rs = $this->db->get_where("fees_payment",array('teller_no'=>$payment_data['teller_no']));
        if($rs->num_rows() > 0){
            $return_data['msg'] = warning_msg("Teller Number exists. Not successful..");
            $return_data['status'] = FALSE;
            return $return_data;
        }
		else{
            $payment_data['session_id']  = $curr_session_id;
            $payment_data['term_id'] = $curr_term_id;
            $payment_data['amount'] = $payment_data['amount_paid'];
            unset($payment_data['amount_paid']);
			$payment_data['admin_id'] = $this->session->userdata("id");
            $this->db->insert('fees_payment',$payment_data);
            $return_data = array();
            $return_data['msg'] = ($this->db->affected_rows() > 0) ? success_msg("Payment Successful...") : warning_msg("Not successful..");
            $return_data['status'] = ($this->db->affected_rows() > 0) ? TRUE : FALSE;
            return $return_data;
        }
    }

    
    function get_payments_made($student_id,$session_id,$term_id = NULL){
        $sql = "SELECT fees_payment.fees_payment_id, bank_name, teller_no,amount, purpose AS payment_purpose,"
                . "fees_payment.date_added FROM fees_payment LEFT JOIN payment_purpose"
                . " ON fees_payment.payment_purpose_id = payment_purpose.id WHERE fees_payment.student_id "
                . "= {$student_id} AND session_id = {$session_id}";
        $sql .= ($term_id != NULL) ? " AND term_id = {$term_id}" : '';
        $rs = $this->db->query($sql);
        return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
    }
    
    
    function delete_payment_record($record_id){
        $this->db->delete("fees_payment",array("fees_payment_id"=>$record_id));
        return $this->db->affected_rows() > 0 ? success_msg("Record deleted!") : error_msg("Error deleting record");
    }

	
    /**
     * Gets total of transactions made within a particular time frame for a 
     * particular session or term or student
     * @param Array $data Array of form filter elements to search with
     * @return Array Results from database
     */
    function get_payment_history($data){
        $clause = array();
        $sql = "SELECT CONCAT(surname, ' ',first_name, ' ', middle_name) AS student_name,"
                . "payment_purpose.purpose AS payment_purpose,amount,bank_name,teller_no, fees_payment.date_added"
                . " FROM fees_payment, payment_purpose,student_biodata WHERE "
                . "payment_purpose.id = fees_payment.payment_purpose_id "
                . " AND student_biodata.id = fees_payment.student_id ";
        foreach($data as $k=>$val) {
            if($val != "all" && $val != ""){
                $clause[] = " fees_payment.{$k} = '{$val}'";
            }
        }
        $clause_str = implode(" AND ",$clause);
        $sql .= (strlen($clause_str) > 0) ? " AND " . $clause_str : '';
        //Join other fields from other tables
        $rs = $this->db->query($sql);
        return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;
    }

    
    function add_cash_payment($data){
        $this->db->insert("cash_payments",$data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
    
    
    function get_last_cash_payment($payment_id){
        $this->db->where("cash_payment_id",$payment_id);
        $rs = $this->db->get("cash_payments");
        $row = $rs->row_array();

        return $row;

    }

    

    

    function get_cash_payments(){

        $this->db->order_by("date_added","desc");

        $rs = $this->db->get("cash_payments");

        return ($rs->num_rows() > 0) ? $rs->result_array() : NULL;

    }

    

    

    function delete_cash_payment($cash_payment_id){
        $this->db->delete("cash_payments",array("cash_payment_id"=>$cash_payment_id));
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }


    /* Function to get list of students and amount they are to pay */
    /* Firstly, get a list of all students, get their class, get the amount each class is yet to
     * pay, get the amount they are to pay
     */

    function get_students_payment_data($session_id,$term_id){
        $sql = "SELECT CONCAT(surname, ' ',first_name, ' ', middle_name) AS student_name,"
                . "student_biodata.id, student_biodata.class_id ,"
                . " class_name FROM student_biodata LEFT JOIN classes ON student_biodata.class_id = classes.class_id"
                . " WHERE student_biodata.deleted = 0 ORDER BY class_name ASC, student_name ASC";
        $rs = $this->db->query($sql);
        $records = array();
        if($rs->num_rows() > 0 ) {
            $students = $rs->result_array();
            $records = array();
            foreach($students as $student){
                $required_fees = $this->get_required_fees($student['class_id'], $session_id, $term_id);
                $student['required_fees'] = $this->sum_up_required_fees($required_fees);
                $student['paid_fees'] = $this->get_paid_fees($student['id'], $session_id, $term_id);
                $student['pending_fees'] = $student['required_fees'] - $student['paid_fees'];
                $student = array_merge($student,$this->get_parent_data($student['id']));
                $records[] = $student;
            }
            return $records;
        }
    } 

    

    

    function get_parent_data($student_id){

        $sql = "SELECT * FROM student_parent, parents WHERE student_parent.student_id = {$student_id}"

        . " AND student_parent.parent_id = parents.parent_id";

        $rs = $this->db->query($sql);

        if($rs->num_rows() > 0){

            $record = $rs->row_array();

            return array('parent_name'=>$record['parent_name'],'parent_no'=>$record['phone_no']);

        }

        else return array('parent_name'=>'','parent_no'=>'');

    }

    

    function sum_up_required_fees($required_fees){

        $total_amount = 0;

        if(is_array($required_fees)) {

            foreach($required_fees as $fee){

                $total_amount += $fee['amount'];

            }

        }

        

        return $total_amount;

    }

    

    

    function get_total_fees_paid($session_id,$term_id){

       $sql = "SELECT SUM(fees_payment.amount) AS fees_total FROM fees_payment";

       $rs = $this->db->query($sql);

       $row = $rs->row_array();

       $sql = "SELECT SUM(amount) AS cash_total FROM cash_payments";

       $rs = $this->db->query($sql);

       $row2 = $rs->num_rows() > 0 ? $rs->row_array() : array('cash_total'=>0);

       return $row['fees_total'] + $row2['cash_total'];

    }

    

    

    function get_finance_overview($session_id,$term_id){

        $data = array();

        $data['total_payments'] = $this->get_total_fees_paid($session_id, $term_id);

        $data['total_payment_types'] = sizeof($this->get_fees_purposes());

        return $data;

    }

    

 

}