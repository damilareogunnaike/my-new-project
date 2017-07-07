<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="active">
                <?=anchor('#payment_history','Payment History',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
             <div class="tab-pane active" id="payment_history">
                <div class="panel panel-default ">
                    <div class="panel-heading">
                        <h3 class="text-center text-uppercase panel-title">View Fees Settings</h3>
                    </div>
                    <div class="panel-body">
                        <?=form_open('finance/view_payment_history')?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Select Session</label>
                                    <select class="form-control" name="session_id">
                                        <?=print_dropdown($school_sessions,"school_session_id","school_session_name",$curr_session_id);?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Select Term</label>
                                    <select class="form-control" name="term_id">
                                        <option value="all">All</option>
                                        <?=print_dropdown($school_terms,"school_term_id","school_term_name",$term_id);?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Select Payment Purpose</label>
                                    <select class="form-control" name="payment_purpose_id">
                                        <option value="all">All</option>
                                        <?=print_dropdown($payment_purposes,"id","purpose",$payment_purpose_id);?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Student ID</label>
                                   <?=form_input(array('class'=>'form-control','placeholder'=>'Not compulsory','name'=>'student_id'))?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-md-offset-4">
                                <?=form_submit(array('class'=>'btn btn-primary form-control','value'=>'Get Report'));?>
                            </div>
                        </div>
                        <?=form_close()?>
                        
                        <!-- Payment History has been obtained -->
                        <div class="row">
                            <div class="col-md-12" id="print-window">
                                <h4 class="text-center">Payment History</h4>
                                <table class="table table-bordered">
                                    <thead>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Bank Name</th>
                                    <th>Teller No</th>
                                    <th>Payment Purpose</th>
									<th>Payment Date</th>
                                    </thead>
                                <?php
                                if(isset($history) && $history != NULL){
                                    $count = 0;
                                    foreach($history as $row){
                                        ?>
                                <tr>
                                    <td><?=++$count;?></td>
                                    <td><?=$row['student_name'];?></td>
                                    <td><?=$row['amount'];?></td>
                                    <td><?=$row['bank_name'];?></td>
                                    <td><?=$row['teller_no'];?></td>
                                    <td><?=$row['payment_purpose'];?></td>
                                    <td><?=$row['date_added'];?></td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="text-center">
                                <button data-url="<?=site_url("report/print_")?>" type="button" class="btn btn-primary" id="print-btn">
                                    <i class="fa fa-print"> Print</i>
                                </button>
                                </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Tab Content -->
    </div>
</div>