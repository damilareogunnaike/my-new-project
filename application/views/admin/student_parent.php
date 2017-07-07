<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#parent_ward','Parent\'s Ward',array('data-toggle'=>"tab"));?>
            </li>
            <li class="<?=(isset($active_tab) && $active_tab == "ward_parent") ? "active" : "";?>">
                <?=anchor('#ward_parent','Ward\'s Parent',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="parent_ward">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                         <h3 class="panel-title text-center text-uppercase">Check Parents Ward</h3>
                    </div>
                    <div class="panel-body">
                        
                         <?php
                            if(isset($parents))
                            {
                                ?>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <?=form_open('admin/view_parent_wards');?>
                                <div class="form-group">
                                    <label class="control-label">Select Parent</label>
                                    <div class="input-group">
                                        <select name="parent_id" class="form-control input">
                                            <?php $parent_id = (isset($parent_id)) ? $parent_id : NULL; ?>
                                            <?=print_dropdown($parents,"parent_id","parent_name",$parent_id);?>
                                        </select>
                                        <span class="input-group-btn">
                                            <?=form_submit(array('class'=>'btn btn-primary','value'=>'View'));?>
                                        </span>
                                    </div>
                                </div>
                                <?=form_close();?>
                                <hr>
                                <?php if(isset($parent_name))  { ;?>
                                
                                <h4 class=""><strong><?=$parent_name;?></strong><span class='pull-right'>Parent Wards</span></h4>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student Name</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(isset($parent_wards) && (sizeof($parent_wards) > 0))
                                            {
                                                $count = 0;
                                                foreach($parent_wards as $ward)
                                                {
                                                    ?>
                                        <tr>
                                            <td><?=++$count;?></td>
                                            <td><?=$ward['full_name'];?></td>
                                            <td>
                                            <?=anchor('admin/del_parent_ward/' .$parent_id ."/".$ward['student_id'],"Remove Ward",array('class'=>'text-danger'));?>
                                                
                                        </tr>
                                        <?php
                                                }
                                            }
                                            else echo "<tr><td colspan='100'>" . info_msg("No wards assigned!!") . "</td></tr>";
                                        ?>
                                    </tbody>
                                </table>
                                <?=form_open("admin/add_parent_wards");?>
                                <?=form_fieldset("Add Wards");?>
                                <?=form_hidden("parent_id",$parent_id);?>
                                    <div class="form-group">
                                        <label class="control-label">Select Wards</label>
                                        <select class="form-control" name="students[]" multiple="">
                                            <?=print_dropdown($students,"id","fullname");?>
                                        </select>
                                    </div>
                                    <div class="form-group text-right">
                                        <?=form_button(array("class"=>'btn btn-warning ',"content"=>"Reset"));?> 
                                        <?=form_submit(array("class"=>'btn btn-primary ',"value"=>"Add Wards"));?>
                                    </div>
                                <?=form_fieldset_close();?>
                                <?=form_close();?>
                                <?php } ?>
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
            
            <div class="tab-pane <?=(isset($active_tab) && $active_tab == "ward_parent") ? "active" : "";?>" id="ward_parent">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                         <h3 class="panel-title text-center text-uppercase">Check Students Parent</h3>
                    </div>
                    <div class="panel-body">
                        
                         <?php
                            if(isset($students))
                            {
                                ?>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <?=form_open('admin/view_wards_parent');?>
                                <div class="form-group">
                                    <label class="control-label">Select Student</label>
                                    <div class="input-group">
                                        <select name="student_id" class="form-control input">
                                            <?php $student_id = isset($student_id) ? $student_id : NULL; ?>
                                            <?=print_dropdown($students,"student_id","fullname",$student_id);?>
                                        </select>
                                        <span class="input-group-btn">
                                            <?=form_submit(array('class'=>'btn btn-primary','value'=>'View'));?>
                                        </span>
                                    </div>
                                </div>
                                <?=form_close();?>
                                <hr>
                                <?php if(isset($student_name))  { ;?>
                                
                                <h4 class=""><strong><?=$student_name;?></strong><span class='pull-right'>Ward's Parent</span></h4>
                                <?php 
                                    if(isset($ward_parent) && ($ward_parent != NULL))
                                    {
                                        $count = 0;
                                        ?>
                                <div>
                                    <table class="table table-bordered">
                                        <thead>
                                        <th>#</th>
                                        <th>Parent Name</th>
                                        <th>Username</th>
                                        <th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?=++$count;?></td>
                                                <td><?=$ward_parent['parent_name'];?></td>
                                                <td><?=$ward_parent['username'];?></td>
                                                <td>
                                                <?=anchor("admin/del_ward_parent/{$student_id}/".$ward_parent['parent_id'],"Remove",array("class"=>'text-danger'));?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                                    }
                                    else {
                                   ?>
                                <?=info_msg("No parent assigned to ward!");?>
                                <?=form_open("admin/add_ward_parent");?>
                                <?=form_fieldset("Assign Parent");?>
                                <?=form_hidden("student_id",$student_id);?>
                                    <div class="form-group">
                                        <label class="control-label">Select Parent</label>
                                        <select class="form-control" name="parent_id" >
                                            <?=print_dropdown($parents,"parent_id","parent_name");?>
                                        </select>
                                    </div>
                                    <div class="form-group text-right">
                                        <?=form_button(array("class"=>'btn btn-warning ',"content"=>"Reset"));?> 
                                        <?=form_submit(array("class"=>'btn btn-primary ',"value"=>"Assign Parent"));?>
                                    </div>
                                <?=form_fieldset_close();?>
                                <?=form_close();?>
                                <?php }
                                }
                                ?>
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