<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#','Change Password',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="password_change">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                         <h3 class="panel-title text-center text-uppercase">Change Password</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row form-group">
                            <?=form_open($this->session->userdata("role"). "/change_password");?>
                            <div class="col-md-6 col-md-offset-3">
								<div class="form-group">
									<label class="control-label">Old Password</label>
									<?=form_password(array("class"=>"form-control","required"=>"required","name"=>"old_password"));?>
								</div>
								<div class="form-group">
									<label class="control-label">New Password</label>
									<?=form_password(array("class"=>"form-control","required"=>"required","name"=>"new_password"));?>
								</div>
								<div class="form-group">
									<label class="control-label">Confirm Password</label>
									<?=form_password(array("class"=>"form-control","required"=>"required","name"=>"confirm_password"));?>
								</div>
								<div class="text-center">
									<?=form_submit(array("class"=>"btn btn-primary","value"=>"Change Password"));?>
									<?=form_reset(array("class"=>"btn btn-warning","value"=>"Reset"));?>
								</div>
                            </div>
                            <?=form_close();?>
                        </div>
                        
                        <hr>
                         <?php
                            if(isset($class_students))
                            {
                                ?>
                        <?=form_open("admin/change_class");?>
                        <?=form_hidden("class_id",$class_id);?>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                        <table class="table table-bordered">
                            <thead>
                            <th>#</th>
                            <th>Student Name</th>
                            <th></th>
                            </thead>
                        <?php
                            $count = 0; 
                                foreach($class_students as $student)
                                {
                                    ?>
                        <tr>
                            <td><?=++$count;?></td>
                            <td><?=$student['student_name'];?></td>
                            <td>
                                <?=form_checkbox("student_ids[]",$student['student_id'],TRUE);?>
                            </td>
                        </tr>
                        <?php
                                }
                                ?>
                        </table> 
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                             <label>Select New Class</label>
                                            <select name="new_class_id" class="form-control">
                                                <?=print_dropdown($classes,"class_id","class_name",$class_id);?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?=nbs(10);?></label>
                                            <?=form_submit(array('class'=>'btn btn-primary','value'=>'Change Class'));?>
                                        </div>
                                    </div>
                                </div>
                                <?=form_close();?>
                            </div>
                        </div>
                        <?php
                            }
                            elseif(isset($class_id)) { echo info_msg("No students for this class..."); }
                        ?>
                    </div>
                    <div class="panel-footer">
                         <div class="row">
                            <div class="col-md-8 col-md-offset-2 text-right">
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Tab Content -->
    </div>
</div>