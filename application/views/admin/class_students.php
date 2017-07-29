<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#class_students','Students in Class',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="class_students">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                         <h3 class="panel-title text-center text-uppercase">Class Students</h3>
                    </div>
                    <div class="panel-body">
                        
                         <?php
                            if(isset($class_students))
                            {
                                ?>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div id="print-window">
                                <h3>Students in Class | <?=$class_name;?></h3>
                                    <table class="table table-bordered no-datatable">
                                        <thead>
                                        <th>#</th>
                                        <th>Student Name</th>
                                        <th>Student ID</th>
                                        <th>Parent Name</th>
                                        <th>Parent Username</th>										<th>Parent Phone No</th>
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
                                        <td><?=$student['student_id'];?></td>
                                        <td><?=$student['parent_name'];?></td>
                                        <td><?=$student['username'];?></td>										<td><?=$student['phone_no'];?></td>
                                        <th></th>
                                        <td>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                            ?>
                                    </table> 
                            </div>
                                <div class="text-center">
                                <button data-url="<?=site_url("report/print_")?>" type="button" class="btn btn-primary" id="print-btn">
                                    <i class="fa fa-print"> Print</i>
                                </button>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                            elseif(isset($class_id)) { echo info_msg("No students for this class..."); }
                        ?>
                    </div>
                    <div class="panel-footer">
                         <div class="row">
                            <div class="col-md-8 col-md-offset-2 text-right">
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Tab Content -->
    </div>
</div>