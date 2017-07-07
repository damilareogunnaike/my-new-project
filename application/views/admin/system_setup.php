<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#system_setup','System Setup',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="class_students">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                         <h3 class="panel-title text-center text-uppercase">System Setup</h3>
                    </div>
					<div class="row">
						<div class="col-md-6">
							<div class="panel panel-default">
								<div class="panel-heading text-center">
									System Reset
								</div>
								<div class="panel-body">
									<?=anchor("admin/system_setup/reset_db","Reset System",array("class"=>"btn btn-primary btn-block"));?>
								</div>
								<div class="panel-footer">
								</div>
							</div>
						</div>
					</div>
                   
                    <div class="panel-footer">
                         <div class="row">
                         </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Tab Content -->
    </div>
</div>