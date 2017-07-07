<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
             <li class="<?=(!isset($active_tab) ) ? "active" : '';?>">
                <?=anchor('#cash_payment','Cash Payment',array('data-toggle'=>"tab"));?>
            </li>
              <li class="<?=(isset($active_tab) && ($active_tab === "payments_made")) ? "active" : '';?>">
                <?=anchor('#payments_made','Payments Made',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="cash_payment">
                <div class="panel panel-default  ">
                     <div class="panel-heading ">
                         <h3 class="text-center text-uppercase panel-title">Cash Payment</h3>
                    </div>
                    <div class="panel-body">
                        <?=form_open('finance/add_cash_payment');?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Name</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'depositor_name','required'=>'required'))?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                  <div class="form-group">
                                    <label>Amount Paid</label>
                                    <?=form_input(array('class'=>'form-control','name'=>'amount','required'=>'required'))?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                 <div class="form-group">
                                    <label>Payment Purpose</label>
                                     <?=form_input(array('class'=>'form-control','name'=>'payment_description','required'=>'required'))?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-md-offset-4">
                                <div class="form-group">
                                    <label><?=nbs(10)?></label>
                                    <?=form_submit(array('class'=>'form-control btn btn-primary','value'=>'Submit'))?>
                                </div>
                            </div>
                        </div>
                        <?=form_close();?>
                        <hr>
                        <?php if(isset($print_receipt)){ ?>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <?php $this->load->view('finance/cash_payment_receipt');?>
                            </div>
                        </div>
                         <div class="text-center">
                                <?=form_button(array('class'=>'btn btn-info','content'=>'<i class="fa fa-print"></i> Print',
                                    'id'=>'print_cash_receipt_btn','data-insert-id'=>$insert_id,'data-url'=>site_url("finance/print_cash_receipt/".$insert_id)));?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="panel-footer">
                        
                    </div>
                </div>
            </div>
            
            <div class="tab-pane <?=(isset($active_tab) && $active_tab === "payments_made") ? "active" : '';?>" id="payments_made">
                <div class="panel panel-default" >
                    <div class="panel-heading">
                        <h3 class="text-center text-uppercase panel-title">Cash Payment</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1" id="print-window">
                                <h4 class="text-center">Cash Payments</h4>
                                <table class="table table-bordered">
                                    <thead>
                                    <th>#</th>
                                    <th>Depositor Name</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    </thead>
                                    <?php
                                    if(isset($payments_made)) {
                                        $count = 0;
                                        foreach($payments_made as $payment){
                                            ?>
                                    <tr>
                                        <td><?=++$count;?></td>
                                        <td><?=$payment['depositor_name'];?></td>
                                        <td><?=$payment['payment_description'];?></td>
                                        <td><?=$payment['amount'];?></td>
                                        <td><?=date("Y/m/d H:i:s",time())?></td>
                                        <td>
                                            <?=anchor("finance/del_cash_payment/".$payment['cash_payment_id'],' <i class="fa fa-trash-o"></i></a>',
                                                    array('class'=>'btn btn-xs btn-danger'));?>
                                               
                                            <?=form_button(array('class'=>'btn btn-info btn-xs','content'=>'<i class="fa fa-print"></i> Print',
                                    'id'=>'print_cash_receipt_btn','data-insert-id'=>$payment['cash_payment_id'],'data-url'=>site_url("finance/print_cash_receipt/".$payment['cash_payment_id'])));?>
                                        </td>
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