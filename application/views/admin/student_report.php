<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="active">
                <?=anchor('#view_student','View Student',array('data-toggle'=>'tab'));?>
            </li>
        </ul>
        <?=isset($msg) ? $msg : '';?>
        <div class="tab-content">

              <!-- View Student -->
            <div class="tab-pane active" id="view_student">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center text-uppercase">Class Reports</h3>
                    </div>
                    <div class="panel-body">

                        <?=form_open('admin/class_students',array('class'=>''))?>
                        <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">
                                            Select Class
                                        </label>
                                        <select class="form-control" name="class_id">
                                            <?=print_dropdown($classes,"class_id","class_code",$class_id);?>
                                        </select>
                                    </div>
                                </div>
                                  <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">
                                            Select Session
                                        </label>
                                        <select class="form-control input" name="session_id" id="session_id">
                                            <?=print_dropdown($school_sessions,'school_session_id','school_session_name',$curr_session_id);?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">
                                            Select Term
                                        </label>
                                        <select class="form-control" name="term_id" id="term_id">
                                        <?=print_dropdown($school_terms,'school_term_id','school_term_name',$curr_term_id);?>
                                        <option value="all" <?=($curr_term_id == "all") ? "selected" : ''?>>All Terms</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"><?=nbs()?></label>
                                    <?=form_submit(array('class'=>'btn btn-primary form-control','value'=>'View Students'));?>
                                    </div>
                                </div>
                        </div>
                        <?=form_close();?>
                        <hr>
                         <div class="row">
                            <div class="col-md-12">
                                <p class="text-center">
                                    <strong>
                                        <?= isset($class_name) ? $class_name : '';?> -
                                        <?= isset($session_name) ? $session_name : '';?> -
                                        <?= isset($term_name) ? $term_name : '';?>
                                    </strong>
                                </p>
                            </div>
                        </div>
                        <hr>
                                <?php
                                $is_cumulative = false;

                                if(isset($term_id) && $term_id == "all"){
                                    $is_cumulative = true;
                                    $terms = $students_results['terms'];
                                    $term_keys = $students_results['term_keys'];
                                    $students_results = $students_results['student_scores'];
                                }

                                if(isset($students_results) && sizeof($students_results) > 0)
                                { ?>
                        <table class="table table-striped ">
                            <thead>
                                <th>#</th>
                                <th>Name</th>
                                <?php
                                if($is_cumulative){
                                    foreach($term_keys as $key){
                                        ?>
                                        <th><?=$terms[$key];?></th>
                                <?php
                                    }
                                }
                                ?>
                                <th>Total Score</th>
                                <th>Average Score </th>
                                <th>Position</th>
                                <th></th>
                            </thead>
                            <tbody>
                                <?php
                                    $count = 0;
                                    foreach($students_results as $student)
                                    {
                                    ?>
                                <tr>
                                    <td class="text-center"><?=++$count?></td>
                                    <td><?= anchor('admin/view_stud_record/'.$student['student_id'],$student['student_name']);?> </td>
                                    <?php
                                    if($is_cumulative){
                                        $scores = $student['scores'];
                                        foreach($term_keys as $key){
                                            ?>
                                            <td><?=isset($scores[$key]) ? $scores[$key] : '0';?></td>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <td><?= $student['total_score'];?></td>
                                    <td><?= $student['average'];?></td>
                                    <td><?= print_position($student['position']);?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm print_report_btn" data-print-url="<?=site_url("report/print_report/");?>"  data-student-id="<?=$student['student_id']?>">
                                            <i class="fa fa-print"></i> Print Report
                                        </button>
                                    </td>
                                </tr>
                                <?php
                                    }
                                    ?>
                                  </tbody>
                                </table>
                                <?php
                                }
                                ?>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>