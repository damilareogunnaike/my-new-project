<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab))? "active" : '';?>">
                <?=anchor('#assign_teacher','Assign Class Teacher',['data-toggle'=>'tab']);?>
            </li>
            <li class="<?=(isset($active_tab) && ($active_tab == "view_teacher"))? "active" : '';?>">
                <?=anchor('#view_teacher','View Class Teacher',['data-toggle'=>'tab']);?>
            </li>
        </ul>
        <?=isset($msg) ? $msg : '';?>
        <div class="tab-content">
            
            <!-- View Staff -->
            <div class="tab-pane <?=(!isset($active_tab))? "active" : '';?>" id="assign_teacher">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center text-uppercase">Assign Class Teacher</h3>
                    </div>
                    <div class="panel-body">
                       <?=form_open('admin/assign_class_teacher')?>
                        <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Select Staff</label>
                            <select class="form-control" name="staff_id">
                                <?php
                                if(isset($staffs) && sizeof($staffs) > 0)
                                {
                                    foreach($staffs as $staff)
                                    {
                                        echo "<option value='{$staff['staff_id']}'>";
                                        echo $staff['surname'] . " " . $staff['first_name'] . " " . $staff['last_name'];
                                        echo "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        </div>
                         <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Select Class</label>
                            <select class="form-control" name="class_id">
                                <?php
                                if(isset($classes) && sizeof($classes) > 0)
                                {
                                    foreach($classes as $class)
                                    {
                                        echo "<option value='{$class['class_id']}'>";
                                        echo $class['class_name'];
                                        echo "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        </div>
                        </div>
                        <hr>
                        <div class="form-group text-center">
                            <?=form_submit(['class'=>'btn btn-primary','value'=>'Submit']);?>
                            <?=form_reset(['class'=>'btn btn-warning','value'=>'Cancel']);?>
                        </div>
                        <?=form_close();?>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div>
            </div>
            
            <!-- View Class Teacher -->
            <div class="tab-pane <?=(isset($active_tab) && ($active_tab == "view_teacher"))? "active" : '';?>" id="view_teacher">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center text-uppercase">View Class Teacher</h3>
                    </div>
                   <div class="panel-body">
                       <?=form_open('admin/view_class_teacher')?>
                        <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Select Staff</label>
                            <select class="form-control" name="staff_id">
                                <?php
                                if(isset($staffs) && sizeof($staffs) > 0)
                                {
                                    foreach($staffs as $staff)
                                    {
                                        echo "<option value='{$staff['staff_id']}'>";
                                        echo $staff['surname'] . " " . $staff['first_name'] . " " . $staff['last_name'];
                                        echo "</option>";
                                    }
                                }
                                else echo "<option>No Staff Added !!</option>";
                                ?>
                            </select>
                        </div>
                            <div class="text-center">
                             <?=form_submit(['class'=>'btn btn-primary','value'=>'View Class','name'=>'view_class']);?>
                            </div>
                        </div>
                         <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Select Class</label>
                            <select class="form-control" name="class_id">
                                <?php
                                if(isset($classes) && sizeof($classes) > 0)
                                {
                                    foreach($classes as $class)
                                    {
                                        echo "<option value='{$class['class_id']}'>";
                                        echo $class['class_name'];
                                        echo "</option>";
                                    }
                                }
                                else echo "<option>No Class Added !!</option>";
                                ?>
                            </select>
                        </div>
                             <div class="text-center">
                             <?=form_submit(['class'=>'btn btn-primary','value'=>'View Teacher','name'=>'view_teacher']);?>
                             </div>
                        </div>
                        </div>
                        <hr>
                        <?php
                        if(isset($class_teacher))
                        {
                            ?>
                        <table class="table table-bordered">
                            <thead>
                            <th>#</th>
                            <th>Staff Name</th>
                            <th>Class</th>
                            </thead>
                            <tbody>
                                <?php
                                $count = 0;
                                foreach($class_teacher as $record)
                                {
                                    ?>
                                <tr>
                                    <td><?=++$count?></td>
                                    <td><?=$record['surname'] . " " . $record['first_name'] . " " . $record['last_name'];?></td>
                                    <td><?=$record['class_name']?></td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php
                        }
                        if(isset($query_sent) && !isset($class_teacher))
                        {
                            echo warning_msg("No results found for the search..");
                        }
                        ?>
                        <div class="form-group text-center">
                        </div>
                        <?=form_close();?>
                    </div>
                    <div class="panel-footer text-right">
                        <?=form_close();?>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
</div>