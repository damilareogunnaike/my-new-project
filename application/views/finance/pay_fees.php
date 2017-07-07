<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#pay_fees','Pay Fees',array('data-toggle'=>"tab"));?>
            </li>
             <li class="<?=(isset($active_tab) && ($active_tab === "payment_receipt")) ? "active" : '';?>">
                <?=anchor('#payment_receipt','Payment Receipt',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="pay_fees">
                <div class="panel panel-default  ">
                     <div class="panel-heading ">
                         <h3 class="text-center text-uppercase panel-title">Pay Fees</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Check Pending Fees</h4>
                                <?=form_open('finance/get_pending_fees')?>
                                <div class="form-group">
                                    <label class="control-label">Select Student</label>
                                    <div class="input-group">									<?php $student_id = isset($student_id) ? $student_id : NULL; ?>										<select class="form-control" name="student_id">											<?=print_dropdown($students,"student_id","fullname",$student_id);?>										</select>
                                        <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">Get Fees</button>
                                        </span>
                                    </div>
                                </div>
                                <?=form_close();?>
                                <table class="table table-bordered">
                                    <caption>Required Fees</caption>
                                    <thead>
                                        <th>#</th>
                                        <th>Purpose</th>
                                        <th>Amount</th>
                                    </thead>
                                    <?php
                                    $total = 0;
                                    if(isset($required_fees) && sizeof($required_fees) > 0)
                                    {
                                        $count = 0;
                                        foreach($required_fees as $fee)
                                        {
                                            $total += $fee['amount'];
                                            ?>
                                    <tr>
                                        <td><?=++$count?></td>
                                        <td><?=$fee['purpose'];?></td>
                                        <td><?=$fee['amount'];?></td>
                                    </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <tr class="text-left">
                                        <td></td>
                                        <td><strong>Total Required</strong></td>
                                        <td><strong><?=isset($total) ? $total : '';?></strong></td>
                                    </tr>
                                    <tr class="text-left">
                                        <td></td>
                                        <td><strong>Total Paid</strong></td>
                                        <td><strong><?=isset($paid_fees) ? $paid_fees : '';?></strong></td>
                                    </tr>
                                    <tr class="text-left">
                                        <td></td>
                                        <td><strong>Balance  </strong></td>
                                        <td><strong><?=(isset($total) && isset($paid_fees)) ? ($total - $paid_fees) : '';?></strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h4>Receive Payment</h4>
                                <?=form_open('finance/receive_payment')?>
                                <div class="form-group">
                                    <label class="control-label">Student ID</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'student_id','value'=>isset($student_id) ? $student_id : '','required'=>'required'));?>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Bank Name</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'bank_name','value'=>'','required'=>'required'));?>
                                </div>
                                 <div class="form-group">
                                    <label class="control-label">Teller Number</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'teller_no','value'=>'','required'=>'required'));?>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Amount to Pay</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'amount_paid','value'=>''));?>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Payment Purpose</label>
                                    <select name="payment_purpose_id" class="form-control">
                                        <?=print_dropdown($payment_purposes,"id","purpose");?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <?=form_submit(array('class'=>'btn btn-primary pay_btn','value'=>'Pay Fees'));?>
                                    <?=form_reset(array('class'=>'btn btn-warning ','value'=>'Reset'));?>
                                </div>
                                <?=form_close()?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div>
            </div>
              <div class="tab-pane <?=(isset($active_tab) && ($active_tab === "payment_receipt")) ? "active" : '';?>" id="payment_receipt">
                <div class="panel panel-default  ">
                     <div class="panel-heading ">
                         <h3 class="text-center text-uppercase panel-title">Payment Receipt</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <?=form_open("finance/get_payments_made");?>
                            <div class="col-md-4 col-md-offset-3">
                                <div class="form-group">
                                    <label class="control-label">Student ID</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'student_id','required'=>'required','value'=>isset($student_id) ? $student_id : ''));?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label"><?=nbs(20);?></label>
                                    <?=form_submit(array('class'=>' btn btn-primary','required'=>'required','value'=>'Get Payments'));?>
                                </div>
                            </div>
                            <?=form_close();?>
                        </div>
                        <hr>
                        <div class="row">
                            <div class=" col-md-8 col-md-offset-2">
                                <?php if(isset($payments_made) && $payments_made != NULL) { ?>
                                <!-- Get the payment receipt view -->
                                <?=$this->load->view('finance/payment_receipt');?>
                                <div class="text-center">
                                <?=form_button(array('class'=>'btn btn-info','content'=>'<i class="fa fa-print"></i> Print',
                                    'id'=>'print_receipt_btn','data-student-id'=>$student_id,'data-url'=>site_url("finance/print_receipt/".$student_id)));?>
                                </div>
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