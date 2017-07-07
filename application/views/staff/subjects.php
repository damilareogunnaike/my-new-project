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
                        <h3 class="panel-title text-center text-uppercase">My Subjects</h3>
                    </div>
                    <div class="panel-body">
                       <?= (isset($my_subjects) && $my_subjects === NULL ) ? info_msg("No subjects assigned to you...") : "";?>
                        
                        <?php
                            if(isset($my_subjects))
                            {
                                ?>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                        <table class="table table-bordered">
                            <thead>
                            <th>#</th>
                            <th>Subject</th>
                            <th>Class</th>
                            <th></th>
                            </thead>
                        <?php
                            $count = 0; 
                                foreach($my_subjects as $subject)
                                {
                                    ?>
                        <tr>
                            <td><?=++$count;?></td>
                            <td><?=$subject['subject_name'];?></td>
                            <td><?=$subject['class_name'];?></td>
                            <td>
                            <?=anchor('staff/subject_class/'.$subject['subject_id'] .'/'.$subject['class_id'],'<i class="fa fa-list"></i> Input Result',array('class'=>'btn btn-sm btn-info'));?>
                                <?=anchor('staff/subject_student_message/'.$subject['subject_id'] .'/'.$subject['class_id'],'<i class="fa fa-envelope"></i> Send Message',array('class'=>'btn btn-sm btn-info'));?>
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
                        ?>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Tab Content -->
    </div>
</div>

