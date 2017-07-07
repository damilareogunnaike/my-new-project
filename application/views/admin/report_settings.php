<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="active">
                <?=anchor('#view_student','Report View Setting',array('data-toggle'=>'tab'));?>
            </li>
            <li class="active">
                <?=anchor('#class_report_sheet','Class Report Sheet',array('data-toggle'=>'tab'));?>
            </li>
        </ul>
        <?=isset($msg) ? $msg : '';?>
        <div class="tab-content">
            
              <!-- View Student -->
            <div class="tab-pane active" id="view_student">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center text-uppercase">Report Settings</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Student Data Displayed
                                    </div>
                                    <div class="panel-body">
                                        <?=form_open('admin/save_student_display_settings')?>
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>
                                                    <label >
                                                        
                                                        <?=form_checkbox("student_display[full_name]",$student_display['full_name'],($student_display['full_name'] == 1) ? TRUE : '');?>
                                                        Full Name
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label >
                                                        <?=form_checkbox("student_display[class]",$student_display['class'],($student_display['class'] == 1) ? TRUE : '');?>
                                                       Class
                                                    </label>
                                                </td>
                                                 </tr>
                                            <tr>
                                                <td>
                                                    <label >
                                                        <?=form_checkbox("student_display[picture]",$student_display['picture'],($student_display['picture'] == 1) ? TRUE : '');?>
                                                        Picture
                                                    </label>
                                                </td>
                                                 </tr>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <?=form_checkbox("student_display[date_of_birth]",$student_display['date_of_birth'],($student_display['date_of_birth'] == 1) ? TRUE : '');?>
                                                        Date of Birth
                                                    </label>
                                                </td>
                                            </tr>
                                             <tr>
                                                <td>
                                                    <label>
                                                        <?=form_checkbox("student_display[gender]",$student_display['gender'],($student_display['gender'] == 1) ? TRUE : '');?>
                                                        Gender
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <?=form_checkbox("student_display[teacher_name]",$student_display['teacher_name'],($student_display['teacher_name'] == 1) ? TRUE : '');?>
                                                        Teacher's Name
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <?=form_checkbox("student_display[cognitive_skills]",$student_display['cognitive_skills'],($student_display['cognitive_skills'] == 1) ? TRUE : '');?>
                                                        Cognitive Skills
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">
                                                        <?=form_submit(array('class'=>'btn btn-primary','value'=>"Save"))?>
                                                </td>
                                            </tr>
                                        </table>
                                        <?=form_close();?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">Cognitive Skills</h4>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table-bordered table">
                                            <thead>
                                            <th>#</th>
                                            <th>Skill</th>
                                            <th></th>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($cognitive_skills) && sizeof($cognitive_skills) > 0)
                                                {
                                                    $count = 0;
                                                    foreach($cognitive_skills as $skill) {
                                                    ?>
                                                <tr>
                                                    <td><?=++$count;?></td>
                                                    <td><?=$skill['skill'];?></td>
                                                    <td>
                                                        <?=anchor('admin/del_skill/'.$skill['skill_id'],'<i class="fa fa-trash-o"></i>',array('class'=>'text-danger text-sm'));?>
                                                    </td>
                                                </tr>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <hr>
                                        <?=form_open('admin/add_skill');?>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <?=form_input(array('class'=>'form-control input','name'=>'skill','required'=>'required','placeholder'=>'Skill 1, Skill 2'))?>
                                                <span class="input-group-btn">
                                                    <?=form_submit(array('class'=>'btn btn-primary','value'=>'Add Skill'));?>
                                                </span>
                                            </div>
                                        </div>
                                        <?=form_close();?>
                                    </div>
                                </div>
                            </div>
                            
                             <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">Grading</h4>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table-bordered table">
                                            <thead>
                                            <th>#</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Grade</th>
                                            <th>Comment</th>
											<th></th>
                                            </thead>
                                            <tbody>
                                               <?php
                                               if(isset($gradings) && sizeof($gradings) > 0)
                                               {
                                                   $count = 0;
                                                   foreach($gradings as $grade){
                                                       ?>
                                                <tr>
                                                    <td><?=++$count;?></td>
                                                    <td><?=$grade['from'];?></td>
                                                    <td><?=$grade['to'];?></td>
                                                    <td><?=$grade['grade'];?></td>
                                                    <td><?=$grade['comment'];?></td>
                                                    <td>
                                                        <?=anchor('admin/del_grade/'.$grade['grading_id'],'<i class="fa fa-trash-o"></i>',array('class'=>'text-danger text-sm'));?>
                                                    </td>
                                                </tr>
                                                <?php
                                                   }
                                               }
                                               ?>
                                            </tbody>
                                        </table>
                                        <hr>
                                        <?=form_open('admin/add_grading',array('rel'=>'async','action'=>site_url('admin/add_c_skill')));?>
                                        <div class="row form-group">
                                            <div class="col-md-4">
                                                <?=form_input(array('class'=>'form-control input','name'=>'from','required'=>'required','placeholder'=>'From'))?>
                                            </div>
                                            <div class="col-md-4">
                                                <?=form_input(array('class'=>'form-control input','name'=>'to','required'=>'required','placeholder'=>'To'))?>
                                            </div>
                                            <div class="col-md-4">
                                                <?=form_input(array('class'=>'form-control input','name'=>'grade','required'=>'required','placeholder'=>'Grade'))?>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-md-8">
                                                 <?=form_input(array('class'=>'form-control input','name'=>'comment','required'=>'required','placeholder'=>'Comment'))?>
                                            </div>
                                            <div class="col-md-4">
                                                <?=form_submit(array('class'=>'form-control btn btn-primary','value'=>'Add'));?>
                                            </div>
                                        </div>
                                        <?=form_close();?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>