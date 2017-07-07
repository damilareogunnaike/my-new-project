<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#current','Current Session/Term',array('data-toggle'=>"tab"));?>
            </li>
            <li class="<?=(isset($active_tab) && $active_tab == "school_session") ? "active" : "";?>">
                <?=anchor('#school_session','Manage School Session/Term',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="current">
                <div class="panel panel-default ">
                     <div class="panel-heading ">
                        <h3 class="text-center text-uppercase panel-title">Current Session/Term</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Current Session/Term</h4>
                                <div class="form-group">
                                    <label class="control-label">Session</label>
                                    <span class="form-control">
                                        <strong><?=isset($current_session) ? $current_session : "Not Set";?></strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Term</label>
                                    <span class="form-control text-uppercase">
                                        <strong> <?=isset($current_term) ? $current_term : "Not Set";?></strong>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Set Current Session/Term</h4> 
                                <?=form_open('admin/set_current_session',array('style'=>'padding:0'));?>
                                 <div class="form-group">
                                    <label class="control-label">Session</label>
                                    <div class="input-group">
                                        <select name="school_session_id" class="form-control">
                                            <?php
                                            if(isset($school_sessions) && $school_sessions != NULL)
                                            {
                                                foreach($school_sessions as $record)
                                                {
                                                    echo "<option value='{$record['school_session_id']}' >";
                                                    echo $record['school_session_name'];
                                                    echo "</option>";
                                                }
                                            }
                                            else echo "<option value='-1'>No Sessions Added</option>";
                                            ?>
                                        </select>
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-primary">
                                                Set as Current
                                            </button>
                                        </span>
                                    </div>
                                </div>
                              <?=form_close()?>
                                <?=form_open('admin/set_current_term',array('style'=>'padding:0'));?>
                                 <div class="form-group">
                                    <label class="control-label">Term</label>
                                    <div class="input-group">
                                        <select name="school_term_id" class="form-control">
                                            <?php
                                            if(isset($school_terms) && $school_terms != NULL)
                                            {
                                                foreach($school_terms as $record)
                                                {
                                                    echo "<option value='{$record['school_term_id']}'> ";
                                                    echo $record['school_term_name'];
                                                    echo "</option>";
                                                    unset($record);
                                                }
                                            }
                                            else echo "<option value='-1'>No Terms Added</option>";
                                            ?>
                                        </select>
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-primary">
                                                Set as Current
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <?=form_close()?>
                                 <p class="text-warning">
                                    Please note that changes made here are crucial and affect overall functionality of system
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div>
            </div>
            <div class="tab-pane <?=(isset($active_tab) && $active_tab == "school_session") ? "active" : "";?>" id="school_session">
                <div class="panel panel-default ">
                    <div class="panel-heading">
                        <h3 class="text-center text-uppercase panel-title">School Session</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <?=form_open('admin/add_school_session')?>
                                <div class="form-group">
                                    <label class="control-label">Enter Session Name</label>
                                    <div class="input-group">
                                    <?=form_input(array('class'=>'form-control','name'=>'school_session_name','required'=>'required'))?>
                                        <span class="input-group-btn">
                                            <?=form_submit(array('class'=>'btn btn-primary','value'=>'Add'));?>
                                        </span>
                                    </div>
                                </div>
                             <?=form_close()?>
                                <table class="table table-bordered">
                                    <thead>
                                        <th>#</th>
                                        <th>Session Name</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(isset($school_sessions) && sizeof($school_sessions) > 0)
                                        {
                                            $count = 0;
                                            foreach($school_sessions as $row)
                                            {
                                                ?>
                                        <tr>
                                            <td><?=++$count;?></td>
                                            <td><?=$row['school_session_name']?></td>
                                            <td>
                                            <?=anchor('#',"<i class='fa fa-edit'></i>",array('class'=>'text-info'))?>
                                            <?=anchor('admin/del/session/'.$row['school_session_id'],"<i class='fa fa-trash-o'></i>",array('class'=>'text-danger delete_btn'))?>
                                        </td>
                                        </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                             <div class="col-md-6">
                                <?=form_open('admin/add_school_term')?>
                                <div class="form-group">
                                    <label class="control-label">Enter Term Name</label>
                                    <div class="input-group">
                                    <?=form_input(array('class'=>'form-control','name'=>'school_term_name','required'=>'required'))?>
                                        <span class="input-group-btn">
                                            <?=form_submit(array('class'=>'btn btn-primary','value'=>'Add'));?>
                                        </span>
                                    </div>
                                </div>
                             <?=form_close()?>
                                <table class="table table-bordered">
                                    <thead>
                                        <th>#</th>
                                        <th>Term Name</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(isset($school_terms) && sizeof($school_terms) > 0)
                                        {
                                            $count = 0;
                                            foreach($school_terms as $row)
                                            {
                                                ?>
                                        <tr>
                                            <td><?=++$count;?></td>
                                            <td><?=$row['school_term_name']?></td>
                                            <td>
                                            <?=anchor('#',"<i class='fa fa-edit'></i>",array('class'=>'text-info'))?>
                                            <?=anchor('admin/del/term/'.$row['school_term_id'],"<i class='fa fa-trash-o'></i>",array('class'=>'text-danger delete_btn'))?>
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
                    <div class="panel-footer">
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Tab Content -->
    </div>
</div>