<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#admins','Administrators',['data-toggle'=>"tab"]);?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="admins">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                         <h3 class="panel-title text-center text-uppercase">Administrators</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-8 col-md-offset-2">
                          <table class="table table-bordered">
                            <thead>
                            <th>#</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th></th>
                            </thead>
                         <?php
                            if(isset($admins))
                            {
                                ?>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                        <?php
                            $count = 0; 
                                foreach($admins as $admin)
                                {
                                    ?>
                        <tr>
                            <td><?=++$count;?></td>
                            <td><?=$admin['username'];?></td>
                            <td><?=$admin['role'];?></td>
                            <td>
                                <?=anchor('admin/del_admin/'.$admin['id'],'<i class="fa fa-trash-o"></i> ',array('class'=>'text-sm text-danger del_btn'));?>
                            </td>
                        </tr>
                        <?php
                                }
                            }
                            elseif(isset($class_id)) { echo info_msg("No students for this class..."); }
                        ?>
                         </table> 
                        </div>
                            <hr>
                        <div class="row form-group">
                            <?=form_open("admin/add_admin");?>
                            <div class="col-md-3 col-md-offset-0">
                                <label class="control-label">
                                    Username
                                </label>
                                <?=form_input(array('class'=>'form-control','name'=>'username','required'=>'required','placeholder'=>'Username'));?>
                            </div>
                             <div class="col-md-3">
                                <label class="control-label">
                                   Password
                                </label>
                                <?=form_password(array('class'=>'form-control','name'=>'password','required'=>'required','placeholder'=>'Password'));?>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">
                                   Category
                                </label>
                                <select class="form-control" name="role" >
                                    <option value="admin">System Admin</option>
                                    <option value="finance">Finance Officer</option>
                                    <option value="health">Health Officer</option>
                                    
                                </select>
                            </div>
                             <div class="col-md-3">
                                 <label class="control-label"></label>
                                 <?=form_submit(array('class'=>'btn btn-primary form-control','value'=>'Add Admin'));?>
                            </div>
                            <?=form_close();?>
                        </div>
                        
                       
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