<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#class_report','Class Report',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="class_report">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                         <h3 class="panel-title text-center text-uppercase">Class Report</h3>
                    </div>
                    <div class="panel-body">
                        <?=form_open("admin/get_class_report");?>
							<div class="row">
								<div class="col-md-4 ">
									<div class="form-group">
										<label class="control-label">Select Session</label>
										<select class="form-control" name="school_session_id">
											<?=$this->myapp->session_dropdown();?>
										</select>
									</div>
									
									<div class="form-group">
										<label class="control-label">Select Term</label>
										<select class="form-control" name="school_term_id">
											<?=$this->myapp->term_dropdown();?>
										</select class="form-control">
									</div>
									
								</div>
								<div class="col-md-4 form-group">
									<label class="control-label">Select Class</label>
									<select class="form-control" multiple name="class_id">
									<?=$this->myapp->class_dropdown();?>
									</select>
								</div>
								<div class="col-md-4 form-group">
									<div class="form-group">
										<label class="control-label">Select Subject</label>
										<select class="form-control" name="term_id">
										<?=$this->myapp->subjects_dropdown();?>
										</select>
									</div>
									<div class="form-group">
										<?=form_submit(array("class"=>"btn btn-primary ","value"=>"Get Report"));?>
									</div>
								</div>
							</div>
						<?=form_close();?>
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