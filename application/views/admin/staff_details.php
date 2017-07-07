<?php 
	if($this->session->flashdata("active_tab")){
		$active_tab  = $this->session->flashdata("active_tab");
	}
?>
<div class="row">
    <div class="col-md-12">
	
		<div class="row">
            <div class="col-md-8">
                <h3><?=arr_val($staff_info,"title");?> <?=arr_val($staff_info,'surname') . " " . arr_val($staff_info,'first_name') . " " . arr_val($staff_info,'middle_name');?></h3>
                <h4>Staff ID: <?=arr_val($staff_info,'username');?></h4>
            </div>
        </div>
		
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#biodata','Biodata',array('data-toggle'=>"tab"));?>
            </li>
			<li class="<?=(isset($active_tab) && ($active_tab == "academic_info")) ? "active" : '';?>">
                <?=anchor('#academic-info','Academic Info',array('data-toggle'=>"tab"));?>
            </li>
			<li class="pull-right">
                <?=anchor("#",'<i class="fa fa-chevron-left"></i> Back',array('class'=>'btn back-btn btn-primary btn-xs'));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
		<?=$this->session->flashdata("msg");?>
        
        <!-- Tab Content -->
        <div class="tab-content">
			<div class="tab-pane <?=(!isset($active_tab)) ? "active" : "";?>" id="biodata">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title text-center text-uppercase">Biodata</h3>
					</div>
					 <?=form_open_multipart('admin/update_staff_info');?>
                    <?=form_hidden('staff_id',$staff_info['staff_id']);?>
					<div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="control-label">Title</label>
                                <select name="title" class="form-control">
								<?=print_arr_dropdown(array("Mr.","Mrs","Miss"),arr_val($staff_info,"title"));?>
                                    </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Surname</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'surname','value'=>arr_val($staff_info,"surname")))?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">First Name</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'first_name','value'=>arr_val($staff_info,"first_name")));?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Last Name</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'last_name','value'=>arr_val($staff_info,"last_name")));?>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Gender</label>
                                   <select name="gender" class="form-control" >
                                        <?=print_arr_dropdown(array("Male","Female"),arr_val($staff_info,"gender"));?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Marital Status</label>
                                    <select name="marital_status" class="form-control">
									<?=print_arr_dropdown(array("Single","Married","Divorced","Widow"),arr_val($staff_info,"marital_status"));?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                               <div class="form-group">
                                    <label class="control-label">Religion</label>
                                    <select name="religion" class="form-control" >
									<?=print_arr_dropdown(array("Islam","Christianity","Hindu","Others"),arr_val($staff_info,"religion"));?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Date of Birth</label>
                                    <?=form_input(array('class'=>'form-control datepicker','id'=>'date_of_birth','name'=>'date_of_birth','value'=>arr_val($staff_info,'date_of_birth')));?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                               <div class="form-group">
                                    <label class="control-label">Country</label>
									<?=form_input(array("class"=>"form-control","name"=>"nationality",'value'=>arr_val($staff_info,'nationality')));?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">State of Origin</label>
                                    <select name="state_of_origin" class="form-control">
                                        <?=print_dropdown($states,"state_name","state_name",arr_val($staff_info,"state_name"));?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Local Govt. Area</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'loc_govt_area','value'=>arr_val($staff_info,'loc_govt_area')));?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Home Town</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'home_town','value'=>arr_val($staff_info,'home_town')));?>
                                </div>
                            </div>
                        </div>
                        <div  class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Contact Address</label>
                                    <?=form_textarea(array('class'=>'form-control','name'=>'contact_address','value'=>arr_val($staff_info,'contact_address')));?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Email</label>
                                            <?=form_input(array('class'=>'form-control','name'=>'email','value'=>arr_val($staff_info,'email')));?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Mobile Number</label>
                                            <?=form_input(array('class'=>'form-control','name'=>'mobile_no','value'=>arr_val($staff_info,'mobile_no')));?>
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
			
            <div class="tab-pane <?=(isset($active_tab) && ($active_tab == "academic_info")) ? "active" : '';?>" id="academic-info">
					<div class="panel panel-default">
						 <div class="panel-heading ">
							<h3 class="panel-title text-center text-uppercase">Academic Info</h3>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="panel-body">
								   <div class="panel panel-info">
										<div class="panel-heading text-white text-center">
											Subjects Taught
										</div>
										<div class="panel-body">
											<table class="table table-bordered">
												<thead>
													<th>Subject Name</th>
													<th>Class Taught</th>
													<th></th>
												</thead>
												<?php 
												if(isset($subjects) && sizeof($subjects) > 0) {
												foreach($subjects as $subject) { ?>	
												<tr>
												<td><?=$subject['subject_name']?></td>
												<td><?=$subject['class_name']?></td>
												<td>
													<?=anchor("admin/remove_teacher/subject/". $subject['class_id'] ."/".$staff_info['staff_id']. "/" . $subject['subject_id'] . "/?prev_page=" . urlencode(uri_string()),"<i class='fa fa-times'></i>",array("class"=>"text-danger"));?>
												</td>
												</tr>
												<?php }
												}
												else echo "<tr><td colspan='100%'>" . info_msg("No records!") . "</td></tr>";
												?>
											</table>
										</div>
								   </div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="panel-body">
								   <div class="panel panel-info">
										<div class="panel-heading text-white text-center">
											Classes Managed
										</div>
										<div class="panel-body">
											<table class="table table-bordered">
											<thead>
												<th>Class Name</th>
												<th></th>
											</thead>
												<?php 
												if(isset($classes) && sizeof($classes) > 0) {
												foreach($classes as $class) { ?>	
												<tr>
												<td><?=$class['class_name']?></td>
												<td>
													<?=anchor("admin/remove_teacher/class/".$class['class_id']."/".  $staff_info['staff_id'] . "/?prev_page=" . urlencode(uri_string()),"<i class='fa fa-times'></i>",array("class"=>"text-danger"));?>
												</td>
												</tr>
												<?php }
												}
												else echo "<tr><td colspan='100%'>" . info_msg("No records!") . "</td></tr>";
												?>
											</table>
										</div>
								   </div>
								</div>
							</div>
						</div>
						<div class="panel-footer">
						</div>
				</div>
            </div>
        </div>
        <!-- End of Tab Content -->
    </div>
</div>