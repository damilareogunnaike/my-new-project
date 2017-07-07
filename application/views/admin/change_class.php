<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#bulk_change','Class Change',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="bulk_change">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                         <h3 class="panel-title text-center text-uppercase">Change Students Class</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row form-group">
                            <?=form_open("admin/get_class_students");?>

                             <div class="col-md-2 col-md-offset-2">
                                <label class='control-label'>Select Session </label>
                                
                                <select class="form-control" name="school_session_id">
                                    <?php $school_session_id = isset($school_session_id) ? $school_session_id : NULL;?>
                                    <?=$this->myapp->session_dropdown($school_session_id);?>
                                </select>
                                <span class="text-info text-sm">Session to display</span>
                            </div>
                            <div class="col-md-4">
                            <label class="control-label">
                                Select Class
                            </label>
                                <select class="form-control" name="class_id">
                                    <?php $class_id = isset($class_id) ? $class_id : NULL;?>
                                    <?=print_dropdown($classes,"class_id","class_name",$class_id);?>
                                </select>
                            </div>
                           
                             <div class="col-md-2">
                                 <?=form_submit(array('class'=>'btn btn-primary','value'=>'Get Students','style'=>"margin-top:23px"));?>
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
                                    <div class="col-md-3 col-md-offset-1">
                                        <label class='control-label'>Select New Session </label>
                                        <select class="form-control" name="school_session_id">
                                            <?=$this->myapp->session_dropdown();?>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
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