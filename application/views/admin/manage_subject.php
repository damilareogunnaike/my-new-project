<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#add_subject','Add Subject',array('data-toggle'=>"tab"));?>
            </li>
            <li class="<?=(isset($active_tab) && $active_tab == "view_subject") ? "active" : "";?>">
                <?=anchor('#view_subject','View Subject',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="add_subject">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                         <h3 class="text-center text-uppercase panel-title">Add New Subject</h3>
                    </div>
                <?=form_open('school_setup/add_subject')?>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                        <div class="form-group">
                            <label class="control-label">
                                Subject Name
                            </label>
                            <?=form_input(array('class'=>'form-control','name'=>'subject_name','required'=>'required'))?>
                            <span class="text-muted pull-right">e.g. Mathematics</span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">
                                Subject Short Name
                            </label>
                            <?=form_input(['class'=>'form-control','name'=>'subject_short_name','required'=>'required'])?>
                            <span class="text-muted pull-right">e.g. Maths</span>
                        </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2 text-right">
                        <?=form_submit(['class'=>'btn btn-primary','value'=>'Add Subject'])?>
                        <?=form_reset(['class'=>'btn btn-warning','value'=>'Reset'])?>
                            </div>
                        </div>
                    </div>
                <?=form_close()?>
                </div>
            </div>
            <div class="tab-pane <?=(isset($active_tab) && $active_tab == "view_subject") ? "active" : "";?>" id="view_subject">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="text-center text-uppercase panel-title">All Subjects</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <table class="table table-bordered">
                                    <thead>
                                    <th>#</th>
                                    <th>Subject Name</th>
                                    <th>Subject Short Name</th>
                                    <th></th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(isset($subjects) && sizeof($subjects) > 0)
                                        {
                                            $count = 0;
                                            foreach($subjects as $subject)
                                            {
                                                ?>
                                        <tr>
                                            <td><?=++$count?></td>
                                            <td><?=$subject['subject_name'];?></td>
                                            <td><?=$subject['subject_short_name'];?></td>
                                            <td>
                                                <?=anchor('school_setup/del/subject/'.$subject['subject_id'],'<i class="fa fa-remove"></i>',array('class'=>'text-danger'))?>
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
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Tab Content -->
    </div>
</div>