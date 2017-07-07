  <div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#input_pin','Input Pin',array('data-toggle'=>"tab"));?>
            </li>
             <li class="<?=(isset($active_tab) && ($active_tab === "check_pin")) ? "active" : '';?>">
                <?=anchor('#check_pin','Check Pin',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="input_pin">
                <div class="panel panel-default  ">
                     <div class="panel-heading ">
                         <h3 class="text-center text-uppercase panel-title">Pay Fees</h3>
                    </div>
                    <div class="panel-body">
                         <p class="text-info text-center">
                            Each student requires a pin to be used to check his or her results for a particular term.
                        </p>
                        <div class="row">
                             <div class="col-md-6 col-md-offset-3">
                                    <?=form_open("ms_parent/input_pin");?>
                                       <div class="form-group">
                                           <label>Select Student</label>
                                           <select class="form-control" name="student_id">
                                              <?=print_dropdown($my_wards,"student_id","full_name",$student_id);?>
                                           </select>
                                       </div>

                                       <div class="form-group">
                                           <label>Enter Pin</label>
                                           <?=form_input(array('class'=>'form-control','name'=>'pin'));?>
                                       </div>
                                       <div class="form-group">
                                           <?=form_submit(array('class'=>'btn btn-primary pull-right','value'=>'Submit'));?>
                                       </div>
                                   <?=form_close();?>
                               </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div>
            </div>
              <div class="tab-pane <?=(isset($active_tab) && ($active_tab === "check_pin")) ? "active" : '';?>" id="check_pin">
                <div class="panel panel-default  ">
                     <div class="panel-heading ">
                         <h3 class="text-center text-uppercase panel-title">Check Pin</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                             <div class="col-md-6 col-md-offset-3">
                                    <?=form_open("ms_parent/check_pin");?>
                                       <div class="form-group">
                                           <label>Select Student</label>
                                           <select class="form-control" name="student_id">
                                              <?=print_dropdown($my_wards,"student_id","full_name",$student_id);?>
                                           </select>
                                       </div>
                                       <div class="form-group text-right">
                                           <?=form_submit(array('class'=>'btn btn-primary','value'=>'Check Pin'));?>
                                       </div>
                                   <?= form_close();?>
                                 <hr>
                                 <?php if(isset($pin)) { ?>
                                 <h3>Result Pin: <strong><?=isset($pin) ? $pin : "";?></strong></h3>
                                 <?php } ?>
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