<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#subjects','Subjects',array('data-toggle'=>"tab"));?>
            </li>
            <li class="pull-right"><?=anchor('staff/class_students/'.$class_id,'<i class="fa fa-chevron-left"></i> Back',array('class'=>'btn btn-primary btn-xs'));?></li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="subjects">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                        <h3 class="panel-title text-center text-uppercase">Student Result Form | <?=isset($student_name) ? $student_name : '';?></h3>
                    </div>
                    <?=form_open('staff/save_student_result')?>
                     <div class="panel-body">
                        <?php
                            if(isset($student_subjects))
                            {
                                ?>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                        <table class="table table-bordered">
                            <thead>
                            <th>#</th>
                            <th>Subject</th>
                            <th>C.A. 1</th>
                            <th>C.A. 2</th>
                            <th>Exam</th>
                            <th>Total</th>
                            </thead>
                        <?php
                            $count = 0; 
                                foreach($student_subjects as $subject)
                                {
                                    echo form_hidden('subjects[]', $subject['subject_id']);
                                    echo form_hidden("student_id",$student_id);
                                    echo form_hidden("class_id",$class_id);
                                    ?>
                        <tr>
                            <td><?=++$count;?></td>
                            <td><?=$subject['subject_name'];?></td>
                            <td><?=form_input(array('class'=>'form-control input-sm score-input','name'=>"subject_scores[{$subject['subject_id']}][ca1]",'value'=>$subject['ca1'],'placeholder'=>'0','max'=>'20','min'=>'0','type'=>'number','data-animation'=>'true','data-html'=>'true','data-placement'=>'right'));?></td>
                            <td><?=form_input(array('class'=>'form-control input-sm score-input','name'=>"subject_scores[{$subject['subject_id']}][ca2]",'value'=>$subject['ca2'],'placeholder'=>'0','max'=>'10','min'=>'0','type'=>'number','data-animation'=>'true','data-html'=>'true','data-placement'=>'right',));?></td>
                            <td><?=form_input(array('class'=>'form-control input-sm score-input','name'=>"subject_scores[{$subject['subject_id']}][exam]",'value'=>$subject['exam'],'placeholder'=>'0','max'=>'70','min'=>'0','type'=>'number','data-animation'=>'true','data-html'=>'true','data-placement'=>'right'));?></td>
                                    <td><span class="form-control input-sm">
                                            <?= $subject['ca1'] + $subject['ca2'] + $subject['exam'];?>
                                </span></td>
                        </tr>
                        <?php
                                }
                                ?>
                        </table> 
                                
                                <!-- Cognitive Skills  Entering Platform -->
                                
                                <h4>Cognitive Skills</h4>
                                <table class="table table-bordered">
                                    <thead>
                                    <th>#</th>
                                    <th>Skill</th>
                                    <th>Rating</th>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if(isset($cog_skills_result) && sizeof($cog_skills_result) > 0){
                                            $count = 0;
                                            $rating = array(1,2,3,4,5);
                                            foreach($cog_skills_result as $result) {
                                                echo form_hidden("skills[]",$result['skill_id']);
                                                ?>
                                        <tr>
                                            <td><?=++$count;?></td>
                                            <td><?=$result['skill'];?></td>
                                            <td>
                                                <select class="form-control" name="skill_result[<?=$result['skill_id']?>][rating]]">
                                                    <?php
                                                    foreach($rating as $val){
                                                        echo "<option ";
                                                        echo ($val == $result['rating']) ? " selected " : '';
                                                        echo ">" .$val . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
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
                            if(isset($student_subjects) && sizeof($student_subjects) > 0) { ?>
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