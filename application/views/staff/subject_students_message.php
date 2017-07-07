<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#students','Send Message',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="subjects">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                        <h3 class="panel-title text-center text-uppercase">Send Message | <?=$subject_name;?> | <?=$class_name;?></h3>
                    </div>
                    <?=form_open('staff/send_message')?>
                    <input type="hidden" value="subject_students" name="target">
                     <div class="panel-body">
                         <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                         <div class="form-group">
                             <label>Enter Message</label>
                             <textarea required name="message" class="form-control"></textarea>
                         </div>
                        <?php
                            if(isset($subject_students))
                            {
                                echo form_hidden('subject_id',$subject_id);
                                echo form_hidden('class_id',$class_id);
                                ?>
                        <label>Select Recipients</label>
                        <table class="table table-bordered">
                            <thead>
                            <th>#</th>
                            <th>Student Name</th>
                            <th><input type="checkbox" id="check-all-btn" data-toggle=".check-me"></th>
                            </thead>
                        <?php
                            $count = 0; 
                                foreach($subject_students as $student)
                                {
                                    echo form_hidden('students[]', $student['student_id']);
                                    ?>
                        <tr>
                            <td><?=++$count;?></td>
                            <td><?=$student['surname'] . " " . arr_val($student,"first_name") . " " . arr_val($student,'middle_name');?></td>
                            <td><input name="recipients[]" value="<?=arr_val($student,"student_id");?>" type="checkbox" class="check-me"></td>
                        </tr>
                        <?php
                                }
                                ?>
                        </table> 
                            </div>
                        </div>
                        <?php
                            }
                            else echo info_msg("No students for this subject...");
                        ?>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                        <div class="col-md-8 col-md-offset-2 text-right">
                            <?php 
                            if(isset($subject_students) && sizeof($subject_students) > 0) { ?>
                            <?=form_submit(array('class'=>'btn btn-primary validate-checked-boxes','value'=>'Send'));?>
                            <?=form_reset(array('class'=>'btn btn-warning','value'=>'Reset'));?>
                            <?php } ?>
                        </div>
                        </div>
                    </div>
                    <?=form_close();?>
                </div>
            </div>
        </div>
        <!-- End of Tab Content -->
    </div>
</div>