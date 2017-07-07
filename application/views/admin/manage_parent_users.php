<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
			<li class="<?=(!isset($active_tab) || (isset($active_tab) && $active_tab == "view_parent_user")) ? "active" : '';?>">
					<?=anchor('#view_parent_user','View Parent User',array('data-toggle'=>"tab"));?>
			</li>
			<li class="<?=(isset($active_tab) && $active_tab == "add_parent_user") ? "active" : "";?>">
				<?=anchor('#add_parent_user','Add Parent User',array('data-toggle'=>"tab"));?>
			</li>
            
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
		 <div class="tab-pane <?=(!isset($active_tab) || (isset($active_tab) && $active_tab == "view_parent_user")) ? "active" : '';?>" id="view_parent_user">
                <div class="panel panel-default">
                    <div class="panel-heading" >
                        <h3 class="text-center text-uppercase panel-title">All Parents</h3>
                    </div>
                    <div class="panel-body" id="print-window">
                        <table class="table table-bordered datatable">
                            <thead>
                            <th>#</th>
                            <th>Parent Name</th>
                            <th>Phone no</th>
							<th>Username</th>
							<th></th>
                            </thead>
                            <tbody>
                                <?php
                                if(isset($parent_users) && sizeof($parent_users) > 0)
                                {
                                    $count = 0;
                                    foreach($parent_users as $user)
                                    {
                                        ?>
                                <tr>
                                    <td><?=++$count?></td>
                                    <td><?=$user['parent_name'];?></td>
                                    <td><?=$user['phone_no'];?></td>
									<td><?=$user['username'];?></td>
                                    <td>
									<?=anchor('admin/view_parent/'.$user['parent_id'],'<i class="fa fa-eye"></i>',array('class'=>' btn btn-info btn-xs'))?>
                                         <?=anchor('admin/delete_parent/'.$user['parent_id'],'<i class="fa fa-trash"></i>',array('class'=>' btn del_btn btn-danger btn-xs'))?>
                                    </td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                                
                        </table>
                       
                    </div>
					<div class="panel-footer text-center">
						 <button data-url="<?=site_url("report/print_")?>" type="button" class="btn btn-primary" id="print-btn">
							<i class="fa fa-print"> Print</i>
						</button>
					</div>
                </div>
            </div>
			
            <div class="tab-pane   <?=(isset($active_tab) && $active_tab == "add_parent_user") ? "active" : "";?>" id="add_parent_user">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                        <h3 class="text-center text-uppercase panel-title">Add Parent User</h3>
                    </div>
                <?=form_open('admin/add_parent_user')?>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="control-label">Name</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'parent_name','required'=>'required'))?>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Relationship to Student</label>
                                    <select class="form-control" name="relationship">
                                        <option>Father</option>
                                        <option>Mother</option>
                                        <option>Guardian</option>
                                        <option>Grandfather</option>
                                        <option>Grandmother</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Phone Number</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'phone_no','required'=>'required'))?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Email Address</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'email_address'))?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Occupation</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'occupation'))?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Address</label>
                                    <textarea name="address" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>New Parent Default username/password</label>
                                    <?=form_hidden('parent_user_pass',$next_parent_id)?>
                                    <span class="form-control"><?=$next_parent_id?></span>
                                 </div> 
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <?=form_submit(array('class'=>'btn btn-primary','value'=>'Add Parent User'))?>
                        <?=form_reset(array('class'=>'btn btn-warning','value'=>'Reset'))?>
                    </div>
                <?=form_close()?>
                </div>
            </div>
        </div>
        <!-- End of Tab Content -->
    </div>
</div>