<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="active">
                <?=anchor('#add_student','Add Student',array('data-toggle'=>'tab'));?>
            </li>
        </ul>
        <?=isset($msg) ? $msg : '';?>
        <div class="tab-content">
            
            <!-- Add New Student -->
            <div class="tab-pane active" id="add_student">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center text-uppercase">Add New Student</h3>
                    </div>
                    <div class="panel-body">date
                         <?=  form_open_multipart('admin/add_student');?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Surname</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'surname'))?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">First Name</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'first_name'));?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Middle Name</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'middle_name'));?>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Gender</label>
                                   <select name="gender" class="form-control" >
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                            <div class="col-md-4">
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Upload Passport</label>
                                            <?=form_upload(array('class'=>'','name'=>'file'));?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Set Class</label>
                                            <select class="form-control" name="class_id">
                                                <?php
                                                if(isset($classes) && $classes != NULL) {
                                                    foreach($classes as $class)
                                                    {
                                                        echo "<option value='{$class['class_id']}'>{$class['class_name']}</option>";
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
                    <div class="panel-footer text-right">
                        <?=form_submit(array('class'=>'btn btn-primary','value'=>'Add Student'));?>
                        <?=form_reset(array('class'=>'btn btn-warning','value'=>'Cancel'));?>
                        <?=form_close();?>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
</div>