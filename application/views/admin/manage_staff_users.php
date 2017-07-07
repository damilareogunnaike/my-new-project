<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#add_staff_user','Add Class',array('data-toggle'=>"tab"));?>
            </li>
            <li class="<?=(isset($active_tab) && $active_tab == "view_class") ? "active" : "";?>">
                <?=anchor('#view_class','View Class',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="add_staff_user">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                        <h3 class="text-center text-uppercase panel-title">Add New Class</h3>
                    </div>
                <?=form_open('school_setup/add_class')?>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="form-group">
                                    <label class="control-label">
                                        Class Name
                                    </label>
                                    <?=form_input(array('class'=>'form-control','name'=>'class_name','required'=>'required'))?>
                                    <span class="text-muted pull-right">e.g. Junior Secondary School 1</span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        Class Code
                                    </label>
                                    <?=form_input(array('class'=>'form-control','name'=>'class_code','required'=>'required'))?>
                                    <span class="text-muted pull-right">e.g. J.S.S.1</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2 text-right">
                                    <?=form_submit(array('class'=>'btn btn-primary','value'=>'Add Class'))?>
                                    <?=form_reset(array('class'=>'btn btn-warning','value'=>'Reset'))?>
                                </div>
                        </div>
                    </div>
                <?=form_close()?>
                </div>
            </div>
            <div class="tab-pane <?=(isset($active_tab) && $active_tab == "view_class") ? "active" : "";?>" id="view_class">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="text-center text-uppercase panel-title">All Classes</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-8 col-md-offset-2">
                        <table class="table table-bordered">
                            <thead>
                            <th>#</th>
                            <th>Class Name</th>
                            <th>Class Code</th>
                            </thead>
                            <tbody>
                                <?php
                                if(isset($classes) && sizeof($classes) > 0)
                                {
                                    $count = 0;
                                    foreach($classes as $class)
                                    {
                                        ?>
                                <tr>
                                    <td><?=++$count?></td>
                                    <td><?=$class['class_name'];?></td>
                                    <td><?=$class['class_code'];?></td>
                                    <td>
                                        <?=anchor('school_setup/del/class/'.$class['class_id'],'<i class="fa fa-remove"></i>',array('class'=>'text-danger'))?>
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
</div>