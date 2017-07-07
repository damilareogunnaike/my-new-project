<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="active">
                <?=anchor('#view_staff','View Staff',array('data-toggle'=>'tab'));?>
            </li>
            <li class="">
                <?=anchor('#add_staff','Add Staff',array('data-toggle'=>'tab'));?>
            </li>
        </ul>
        <?=isset($msg) ? $msg : '';?>
        <div class="tab-content">
            
            <!-- View Staff -->
            <div class="tab-pane active" id="view_staff">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center text-uppercase">View Staff</h3>
                    </div>
                    <div class="panel-body">						<div id="print-window">						
							<table class="table table-bordered">
								<thead>
									<th>#</th>
									<th>Title</th>
									<th>Name</th>
									<th>Email Address</th>
									<th>Mobile Number</th>
									<th>Username</th>
									<th></th>
								</thead>
								<tbody>
									<?php 
									if(isset($staffs) && sizeof($staffs) > 0)
									{
										$count = 0;
										foreach($staffs as $staff)
										{
										?>
									<tr>
										<td class="text-center"><?=++$count?></td>
										<td><?=$staff['title']?></td>
										<td><?=$staff['surname']?> <?=$staff['first_name']?> <?=$staff['last_name'];?> </td> 
										<td><?=$staff['email']?></td>
										<td><?=$staff['mobile_no']?></td>
										<td><?=$staff['username']?></td>
										<td>
										<?=anchor($this->session->userdata("role") . "/view_staff/".$staff['staff_id'],"<i class='fa fa-eye'></i>",array("class"=>"btn btn-xs  btn-info"));?>
										<?=anchor($this->session->userdata("role") . "/del_staff/".$staff['staff_id'],"<i class='fa fa-times'></i>",array("class"=>"btn btn-xs del_btn btn-danger"));?>
										</td>
									</tr>
									<?php
										}
									}
									?>
								</tbody>
							</table>						</div>
                    </div>
                    <div class="panel-footer text-center">								<button data-url="<?=site_url("report/print_")?>" type="button" class="btn btn-primary" id="print-btn">                                    <i class="fa fa-print"> Print</i>                                </button>
                    </div>
                </div>
            </div>
            
            <!-- Add New Staff -->
            <div class="tab-pane" id="add_staff">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center text-uppercase">Add New Staff</h3>
                    </div>
                    <div class="panel-body">
                         <?=form_open('admin/add_staff');?>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="control-label">Title</label>
                                <select name="title" class="form-control">
                                        <option>Mr.</option>
                                        <option>Mrs</option>
                                        <option>Miss</option>
                                    </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Surname</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'surname'))?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">First Name</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'first_name'));?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Last Name</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'last_name'));?>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Gender</label>
                                   <select name="gender" class="form-control" >
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Marital Status</label>
                                    <select name="marital_status" class="form-control">
                                        <option>Single</option>
                                        <option>Married</option>
                                        <option>Divorced</option>
                                        <option>Widow</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                               <div class="form-group">
                                    <label class="control-label">Religion</label>
                                    <select name="religion" class="form-control" >
                                        <option>Christianity</option>
                                        <option>Islam</option>
                                        <option>Hindu</option>
                                        <option>Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Date of Birth</label>
                                    <?=form_input(array('class'=>'form-control datepicker','id'=>'date_of_birth','name'=>'date_of_birth'));?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                               <div class="form-group">
                                    <label class="control-label">Country</label>
									<?=form_input(array("class"=>"form-control","name"=>"nationality"));?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">State of Origin</label>
                                    <select name="state_of_origin" class="form-control">
                                        <?=print_dropdown($states,"state_name","state_name");?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Local Govt. Area</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'loc_govt_area'));?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Home Town</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'home_town'));?>
                                </div>
                            </div>
                        </div>
                        <div  class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Contact Address</label>
                                    <?=form_textarea(array('class'=>'form-control','name'=>'contact_address'));?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Email</label>
                                            <?=form_input(array('class'=>'form-control','name'=>'email'));?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Mobile Number</label>
                                            <?=form_input(array('class'=>'form-control','name'=>'mobile_no'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                         <div class="form-group">
                                            <label class="control-label">Default username and password</label>
                                            <?=form_hidden('username',$next_staff_id);?>
                                            <span class="form-control">
                                                <strong><?=$next_staff_id;?></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>`
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="panel-footer text-right">
                        <?=form_submit(array('class'=>'btn btn-primary','value'=>'Add Staff'));?>
                        <?=form_reset(array('class'=>'btn btn-warning','value'=>'Cancel'));?>
                        <?=form_close();?>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
</div>