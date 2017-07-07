<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#classes','Classes',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="classes">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                        <h3 class="panel-title text-center text-uppercase">My Classes</h3>
                    </div>
                    <div class="panel-body">
                       <?= (isset($my_classes) && $my_classes === NULL ) ? info_msg("No classes assigned to you...") : "";?>
                        
                        <?php
                            if(isset($my_classes))
                            {
                                ?>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                        <table class="table table-bordered">
                            <thead>
                            <th>#</th>
                            <th>Class</th>
                            <th></th>
                            </thead>
                        <?php
                            $count = 0; 
                                foreach($my_classes as $class)
                                {
                                    ?>
                        <tr>
                            <td><?=++$count;?></td>
                            <td><?=$class['class_name'];?></td>
                            <td>
							<?=anchor('staff/class_students/'.$class['class_id'],'<i class="fa fa-eye"></i> View Students',array('class'=>'btn btn-sm btn-info'));?>
							<?=anchor('staff/class_students_message/'.$class['class_id'],'<i class="fa fa-envelope"></i>  Send Message',array('class'=>'btn btn-sm btn-info'));?>
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