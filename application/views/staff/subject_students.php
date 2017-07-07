<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#subjects','Subjects',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="subjects">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                        <h3 class="panel-title text-center text-uppercase">Subject Students | <?=$subject_name;?> | <?=$class_name;?></h3>
                    </div>
                    <?=form_open('staff/save_subject_result')?>
                     <div class="panel-body">
                        <?php
                            if(isset($subject_students))
                            {
                                echo form_hidden('subject_id',$subject_id);
                                echo form_hidden('class_id',$class_id);
                                ?>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                        <table class="table table-bordered">
                            <thead>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>C.A. 1</th>
                            <th>C.A. 2</th>
                            <th>Exam</th>
                            <th>Total</th>
                            </thead>
                        <?php
                            $count = 0; 
                                foreach($subject_students as $student)
                                {
                                    echo form_hidden('students[]', $student['student_id']);
                                    ?>
                        <tr>
                            <td><?=++$count;?></td>
                            <td><?=$student['student_name'];?></td>
                            <td><?=form_input(array('class'=>'form-control input-sm score-input','name'=>"student[{$student['student_id']}][ca1]",'value'=>$student['ca1'],'placeholder'=>'0','max'=>'20','min'=>'0','type'=>'number','data-animation'=>'true','data-html'=>'true','data-placement'=>'right'));?></td>
                            <td><?=form_input(array('class'=>'form-control input-sm score-input','name'=>"student[{$student['student_id']}][ca2]",'value'=>$student['ca2'],'placeholder'=>'0','max'=>'10','min'=>'0','type'=>'number','data-animation'=>'true','data-html'=>'true','data-placement'=>'right'));?></td>
                            <td><?=form_input(array('class'=>'form-control input-sm score-input','name'=>"student[{$student['student_id']}][exam]",'value'=>$student['exam'],'placeholder'=>'0','max'=>'70','min'=>'0','type'=>'number','data-animation'=>'true','data-html'=>'true','data-placement'=>'right'));?></td>
                                    <td><span class="form-control input-sm">
                                            <?= $student['ca1'] + $student['ca2'] + $student['exam'];?>
                                </span></td>
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
                        <div class="col-md-10 col-md-offset-1 text-right">
                            <?php 
                            if(isset($subject_students) && sizeof($subject_students) > 0) { ?>
                            <?=form_submit(array('class'=>'btn btn-primary','value'=>'Save'));?>
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