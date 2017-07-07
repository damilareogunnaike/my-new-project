<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#school_setting','School Settings',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="payment_purpose">
                <div class="panel panel-default ">
                     <div class="panel-heading ">
                         <h3 class="panel-title text-center text-uppercase">School Setup</h3>
                    </div>
                    <div class="panel-body">
                        <?php
                        if(isset($school_settings))
                        {
                            extract($school_settings);
                        ?>
                        <div class="row">
                            <div class="col-md-8">
                                <h3><?=isset($school_name) ? $school_name : '';?></h3>
                                
                                <h4><?=isset($school_motto) ? $school_motto : '';?></h4>
                            </div>
                            <div class="col-md-4 text-right">
                                <?=isset($school_logo) ? img(array('src'=>$school_logo,'class'=>'img-passport img-thumbnail')) : '';?>
                            </div>
                        </div>
                        <?php } ?>
                        <hr>
                        <?=form_open_multipart('admin/school_setting');?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-8">
                                         <div class="form-group">
                                            <label class="control-label">
                                                School Name
                                            </label>
                                    <?=form_input(array('class'=>'form-control','name'=>'school_name'
                                        ,'required'=>'required','placeholder'=>'School Name','value'=>isset($school_name) ? $school_name : ''));?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">School Logo</label>
                                            <?=form_upload(array('class'=>'','value'=>'Upload Logo','name'=>'school_logo'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">
                                                School Motto
                                            </label>
                                    <?=form_input(array('class'=>'form-control','name'=>'school_motto'
                                        ,'required'=>'required','placeholder'=>'School Motto','value'=>isset($school_motto) ? $school_motto : ''));?>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">
                                                School Address
                                            </label>
                                    <?=form_input(array('class'=>'form-control','name'=>'school_address'
                                        ,'required'=>'required','placeholder'=>'School Address','value'=>isset($school_address) ? $school_address : ''));?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                </div>
                            <div class="panel-footer text-right">
                               <?=form_submit(array('class'=>'btn btn-primary','value'=>'Save'));?>
                               <?=form_submit(array('class'=>'btn btn-warning','value'=>'Reset'));?>
                           </div>
                    <?=form_close();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Tab Content -->
    </div>
</div>