<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#class_studs','Class Students',array('data-toggle'=>"tab"));?>
            </li>
            <li class="pull-right"><?=anchor('staff/p/classes','<i class="fa fa-chevron-left"></i> Back',array('class'=>'btn btn-primary btn-xs'));?></li>
        </ul>
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="class_studs">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                        <h3 class="panel-title text-center text-uppercase">Class Students | <?=isset($class_name) ? $class_name : '';?></h3>
                    </div>
                     <div class="panel-body">
                        <?php
                            if(isset($class_students))
                            {
                                ?>
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
                                <?=anchor('staff/input_result/'.$class_id.'/'.$student['student_id'],'Input Result',array('class'=>'btn btn-primary btn-xs'));?>
                                <?=anchor('staff/view_stud_record/'.$student['student_id'],'View',array('class'=>'btn btn-primary btn-xs'));?>
                                <button class="btn btn-xs btn-primary" data-toggle="popover" data-placement="top"
                                        data-html="true" 
                                        data-content='<?=$this->load->view('partials/message_popover',
                                                array('recipient_id'=>$student['student_id'],
                                                    'target'=>'class_students','extras'=>array('class_id'=>$class_id),'form_action'=>'staff/send_message'),true);?>'
                                        data-title="Send Message">
                                    <i class="fa fa-envelope"></i> Send Message
                                </button>
                            </td>
                        </tr>
                        <?php
                                }
                                ?>
                        </table> 
                            </div>
                        </div>
                        <?php
                            }
                            else { echo info_msg("No students for this class..."); }
                        ?>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                        <div class="col-md-10 col-md-offset-1 text-right">
                            <?php 
                            if(isset($subject_students) && sizeof($subject_students) > 0) { ?>
                            <?=form_submit(array('class'=>'btn btn-primary','value'=>'Save'));?>
                            <?=form_submit(array('class'=>'btn btn-warning','value'=>'Reset'));?>
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