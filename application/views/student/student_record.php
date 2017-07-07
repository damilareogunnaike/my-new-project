<?php
if(isset($biodata))
{
    extract($biodata);
}

$states = $this->School_setup->get_all_states();
?>
<div class="row">
    <div class="col-md-12">
        <?=(!isset($biodata) || (isset($biodata) && $biodata == NULL)) ? info_msg("No student found..") : "";?>
        <div class="row">
            <div class="col-md-8">
                <h3><?=arr_val($biodata,'surname') . " " . arr_val($biodata,'first_name') . " " . arr_val($biodata,'middle_name');?></h3>
                <h4>Registration Number: <?=arr_val($biodata,'id');?></h4>
                <h4>Class : <?=arr_val($biodata,'class');?></h4>
            </div>
            <div class="col-md-4" style="">
                <?=img(array('src'=>arr_val($biodata,'image'),'class'=>'img img-passport img-thumbnail img-responsive pull-right'));?>
            </div>
        </div>
		
		<?php 
			//If valid student record retrieved
			if(isset($biodata) && $biodata != NULL) { 
		?>
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#biodata','Biodata',array('data-toggle'=>'tab'));?>
            </li>
            <li class="<?=(isset($active_tab) && $active_tab == "reports") ? "active" : '';?>">
                <?=anchor('#reports','Academic Reports',array('data-toggle'=>'tab'));?>
            </li>
            <li class="">
                <?=anchor('#finance','Finance',array('data-toggle'=>'tab'));?>
            </li>
            <li class="pull-right">
                <?=anchor("#",'<i class="fa fa-chevron-left"></i> Back',array('class'=>'btn back-btn btn-primary btn-xs'));?>
            </li>
        </ul>
        <?=isset($msg) ? $msg : '';?>
        <div class="tab-content">
            
              <!-- View Student -->
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="biodata">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center text-uppercase">Biodata</h3>
                    </div>
                    <?=form_open_multipart('admin/update_student_record');?>
                    <?=form_hidden('student_id',$student_id);?>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Surname</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'surname','value'=>isset($surname) ? $surname : ''))?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">First Name</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'first_name','value'=>isset($first_name) ? $first_name : ''));?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Middle Name</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'middle_name','value'=>isset($middle_name) ? $middle_name : ''));?>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Gender</label>
                                   <select name="gender" class="form-control" >
                                        <option <?php echo (isset($gender) && $gender == "Male") ? "selected" : ''?>>Male</option>
                                        <option <?php echo (isset($gender) && $gender == "Female") ? "selected" : ''?>>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                               <div class="form-group">
                                    <label class="control-label">Religion</label>
                                    <select name="religion" class="form-control" >
                                        <option <?php echo (isset($religion) && $religion == "Christianity") ? "selected" : ''?>>Christianity</option>
                                        <option <?php echo (isset($religion) && $religion == "Islam") ? "selected" : ''?>>Islam</option>
                                        <option <?php echo (isset($religion) && $religion == "Hindu") ? "selected" : ''?>>Hindu</option>
                                        <option <?php echo (isset($religion) && $religion == "Others") ? "selected" : ''?>>Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Date of Birth</label>
                                    <?=form_input(array('class'=>'form-control datepicker','id'=>'date_of_birth','name'=>'date_of_birth','value'=>isset($date_of_birth) ? $date_of_birth : ''));?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                               <div class="form-group">
                                    <label class="control-label">Select Country</label>
									<?=form_input(array("class"=>"form-control","name"=>"nationality","value"=>isset($nationality) ? $nationality : ""));?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">State of Origin</label>
                                    <select name="state_of_origin" class="form-control">
										<?=print_dropdown($states,"state_name","state_name",$state_of_origin);?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Local Govt. Area</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'loc_govt_area','value'=>isset($loc_govt_area) ? $loc_govt_area : ''));?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Home Town</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'home_town','value'=>isset($home_town) ? $home_town : ''));?>
                                </div>
                            </div>
                        </div>
                        <div  class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="control-label">Contact Address</label>
                                    <?=form_textarea(array('class'=>'form-control','name'=>'contact_address','value'=>isset($contact_address) ? $contact_address : ''));?>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Email</label>
                                            <?=form_input(array('class'=>'form-control','name'=>'email','value'=>isset($email) ? $email : ''));?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Mobile Number</label>
                                            <?=form_input(array('class'=>'form-control','name'=>'mobile_no','value'=>isset($mobile_no) ? $mobile_no : ''));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Upload New Passport</label>
                                            <?=form_upload(array('class'=>'','name'=>'file'));?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Set Class</label>
                                            <select class="form-control" name="class_id">
                                                <?php
                                                if(isset($classes) && $classes != NULL) {
                                                    $class_found = FALSE;
                                                    foreach($classes as $class)
                                                    {
                                                        echo "<option value='{$class['class_id']}' ";
                                                        echo (isset($class_id) && ($class['class_id'] == $class_id)) ? " selected" : '';
                                                        echo ">{$class['class_name']}</option>";
                                                        $class_found = (isset($class_id) && ($class['class_id'] == $class_id)) ? TRUE : $class_id;
                                                    }
                                                     if($class_found === FALSE){ // Student's class not set
                                                        echo "<option selected>Class Not Set</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="panel-footer">
                        <?php
                            if($this->session->userdata('logged_in_role') === "admin") { 
                        ?>
                        <div class="row">
                            <div class="col-md-3">
                                <?=  form_button(array('class'=>'btn btn-primary','content'=>'Edit Record','id'=>'edit_form_btn'))?>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <div class="text-right">
                                        <?=form_submit(array('class'=>'btn btn-primary','value'=>'Update Data'))?>
                                        <?=form_reset(array('class'=>'btn btn-warning','value'=>'Reset'))?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                                }
                                ?>
                    </div>
                    <?=form_close();?>
                </div>
            </div>
              
                <div class="tab-pane <?=(isset($active_tab) && $active_tab == "reports") ? "active" : '';?>" id="reports">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center text-uppercase">Academic Reports</h3>
                    </div>
                    <div class="panel-body">
                        <?=form_open($form_controller .'/view_stud_record');?>
                        <?=form_hidden('student_id',$student_id);?>
                        <?php if(!(isset($parent_view) && $pin_validated === FALSE)) { ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Select Session</label>
                                    <select class="form-control" name="session_id">
                                        <?=print_dropdown($school_sessions,'school_session_id','school_session_name',$curr_session_id);?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Select Term</label>
                                    <select class="form-control" name="term_id">
                                        <?=print_dropdown($school_terms,'school_term_id','school_term_name',$curr_term_id);?>
                                        <option value="all">All Terms</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label"><?=nbs(10);?></label>
                                    <?=form_submit(array('class'=>'btn btn-primary','value'=>'View Result'));?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label"><?=nbs(10);?></label>
                                </div>
                            </div>
                        </div>
                        <?=form_close();?>
                        <div class="row">
                            <div class="col-md-12">
                                
                                <?php if(isset($result_table) && $result_table == "all_terms") { 
                                    $this->load->view('results/all_terms');
                                    ;?>
                                <?php } else {  ?>
                                <table class="table table-bordered">
                                    <thead>
                                    <th colspan="100%" class="text-center">
                                        <?=isset($result_session) ? $result_session : '';?> | <?=isset($result_term) ? $result_term : '';?>
                                    </th>
                                    </thead>
                                    <thead>
                                        <th>#</th>
                                        <th>Subject</th>
                                        <th>CA-1</th>
                                        <th>CA-2</th>
                                        <th>Exam</th>
                                        <th>Total</th>
                                        <th>Class Max</th>
                                        <th>Class Avg.</th>
                                        <th>Class Min.</th>
                                        <th>Position</th>
                                        <th>Grade</th>
                                        <th>Comment</th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $count = 0;
                                    if(isset($student_result) && sizeof($student_result) > 0) {
                                        foreach($student_result as $record)
                                        {
                                            ?>
                                        <tr>
                                            <td><?=++$count;?></td>
                                            <td><?=$record['subject_name'];?></td>
                                            <td><?=$record['ca1'];?></td>
                                            <td><?=$record['ca2'];?></td>
                                            <td><?=$record['exam'];?></td>
                                            <td><?=number_format($record['total_score'],2);?></td>
                                            <td><?=  number_format($record['max_score'],2);?></td>
                                            <td><?=  number_format($record['avg_score'],2);?></td>
                                            <td><?=  number_format($record['min_score'],2);?></td>
                                            <td><?=print_position($record['position']);?></td>
                                            <td><?=($record['grade']);?></td>
                                            <td><?=($record['comment']);?></td>
                                        </tr>
                                        
                                        <?php
                                        }
                                        ?>
                                </tbody>
                                </table>
                                <table class="table table-bordered">
                                         <thead>
                                         <th colspan="100%" class="text-center">Result Summary</th>
                                        </thead>
                                        <tr>
                                            <td>Max. Obtainable: <?=$student_result_summary['max_obtainable'];?></td>
                                            <td>Total Score: <?=  number_format($student_result_summary['total_score'],2);?></td>
                                            <td>Max Score: <?= number_format($student_result_summary['max_score'],2);?></td>
                                            <td>Min Score: <?=  number_format($student_result_summary['min_score'],2);?></td>
                                            <td>Avg Score: <?=  number_format($student_result_summary['avg_score'],2);?></td>
                                        </tr>
                                        <tr>
                                            <td>Position in Class: <?=  print_position($class_result_summary['position']);?></td>
                                            <td>Class Total Score: <?=  number_format($class_result_summary['total_score'],2);?></td>
                                            <td>Class Max Score: <?=  number_format($class_result_summary['max_score'],2);?></td>
                                            <td>Class Min Score: <?=  number_format($class_result_summary['min_score'],2);?></td>
                                            <td>Class Avg Score: <?=  number_format($class_result_summary['avg_score'],2);?></td>
                                        </tr>
                                        <?php
                                    }
                                    else echo info_msg("No result found for selection..");
                                    ?>
                                </table>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } else { echo info_msg("Result Checking Pin not entered.."); } ?>
                    </div>
                    <div class="panel-footer text-center">
                        <?php if(!(isset($parent_view) && $pin_validated === FALSE)) { ?>
                         <?=anchor('report/print_report/'.$print_url,
                           '<i class="fa fa-print"></i> Print Report',array('class'=>'btn btn-info','target'=>'_blank'));?>
                        <?php } ?>
                    </div>
                </div>
            </div>
              
              <div class="tab-pane <?=(isset($active_tab) && $active_tab == "finance") ? "active" : '';?>" id="finance">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center text-uppercase">Finance</h3>
                    </div>
                      <div class="panel-body">
                          <div class="row">
                            <div class="col-md-12">
                                
                                <h3>Payment History</h3>
                                <table class="table table-bordered">
                                    <thead>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Bank Name</th>
                                    <th>Teller No</th>
                                    <th>Payment Purpose</th>
                                    </thead>
                                <?php
                                if(isset($payment_history) && $payment_history != NULL){
                                    $count = 0;
                                    foreach($payment_history as $row){
                                        ?>
                                <tr>
                                    <td><?=++$count;?></td>
                                    <td><?=$row['student_name'];?></td>
                                    <td><?=$row['amount'];?></td>
                                    <td><?=$row['bank_name'];?></td>
                                    <td><?=$row['teller_no'];?></td>
                                    <td><?=$row['payment_purpose'];?></td>
                                    <td></td>
                                </tr>
                                <?php
                                    }
                                }
                                else { echo info_msg("No payments made.."); }
                                ?>
                                </table>
                            </div>
                        </div>
                      </div>
					  <?php 
						//End of valid student returned
						} 
						else {
							echo anchor("#",'<i class="fa fa-chevron-left"></i> Back',array('class'=>'btn pull-right back-btn btn-primary btn-sm'));
						}
					?>
                      <div class="panel-footer">
                      </div>
              </div>
        </div>
    </div>
    </div>
</div>