<?php 
	if($this->session->flashdata("active_tab")){
		$active_tab  = $this->session->flashdata("active_tab");
	}
?>
<div class="row">
    <div class="col-md-12">
	
		<div class="row">
            <div class="col-md-8">
                <h3><?=arr_val($parent_info,"parent_name");?> </h3>
                <h4>Parent ID: <?=arr_val($parent_info,'username');?></h4>
            </div>
        </div>
		
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#biodata','Biodata',array('data-toggle'=>"tab"));?>
            </li>
			<li class="<?=(isset($active_tab) && ($active_tab == "academic_info")) ? "active" : '';?>">
                <?=anchor('#wards','Wards',array('data-toggle'=>"tab"));?>
            </li>
			<li class="pull-right">
                <?=anchor("#",'<i class="fa fa-chevron-left"></i> Back',array('class'=>'btn back-btn btn-primary btn-xs'));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
		<?=$this->session->flashdata("msg");?>
        
        <!-- Tab Content -->
        <div class="tab-content">
			<div class="tab-pane <?=(!isset($active_tab)) ? "active" : "";?>" id="biodata">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title text-center text-uppercase">Biodata</h3>
					</div>
					 <?=form_open_multipart('admin/update_parent_info');?>
                    <?=form_hidden('parent_id',$parent_info['parent_id']);?>
					<div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="control-label">Name</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'parent_name','required'=>'required',"value"=>arr_val($parent_info,"parent_name")))?>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Relationship to Student</label>
                                    <select class="form-control" name="relationship">
									<?php $options = array("Father","Mother","Guardian","Grandfather","Grandmother"); ?>
									<?=print_arr_dropdown($options,arr_val($parent_info,"relationship"));?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Phone Number</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'phone_no','required'=>'required',"value"=>arr_val($parent_info,"phone_no")))?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Email Address</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'email_address',"value"=>arr_val($parent_info,"email_address")))?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Occupation</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'occupation',"value"=>arr_val($parent_info,"occupation")))?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Address</label>
                                    <textarea name="address" class="form-control"><?=arr_val($parent_info,"address");?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>New Parent Default username/password</label>
                                    <p class="form-control-static"><?=arr_val($parent_info,"username")?></p>
                                 </div> 
                            </div>
                        </div>
                        <hr>
                    </div>
					 <div class="panel-footer">
                        <?php
                            if($this->session->userdata('logged_in_role') === "admin") { 
                        ?>
                        <div class="row">
                            <div class="col-md-3">
                                <?=  form_button(array('class'=>'btn btn-primary','content'=>'Edit Record','id'=>'edit_form_btn'))?>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <div class="text-right">
                                        <?=form_submit(array('class'=>'btn btn-primary','value'=>'Update Data'))?>
                                        <?=form_reset(array('class'=>'btn btn-warning','value'=>'Reset'))?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                                }
                                ?>
                    </div>
                    <?=form_close();?>
				</div>
			</div>
			
            <div class="tab-pane <?=(isset($active_tab) && ($active_tab == "wards")) ? "active" : '';?>" id="wards">
					<div class="panel panel-default">
						 <div class="panel-heading ">
							<h3 class="panel-title text-center text-uppercase">Wards</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-6 col-md-offset-3">
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
                                            if(isset($wards) && (sizeof($wards) > 0))
                                            {
                                                $count = 0;
                                                foreach($wards as $ward)
                                                {
                                                    ?>
                                        <tr>
                                            <td><?=++$count;?></td>
                                            <td><?=$ward['full_name'];?></td>
                                            <td>
                                            <?=anchor('admin/del_parent_ward/' .$parent_id ."/".$ward['student_id'] . "/?redirect_url=".urlencode(uri_string()),"Remove Ward",array('class'=>'text-danger'));?>
                                        </tr>
                                        <?php
                                                }
                                            }
                                            else echo "<tr><td colspan='100'>" . info_msg("No wards assigned!!") . "</td></tr>";
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
        </div>
        <!-- End of Tab Content -->
    </div>
</div>