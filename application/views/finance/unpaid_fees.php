<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="active">
                <?=anchor('#fees_data','Fees Data',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
             <div class="tab-pane active" id="payment_history">
                <div class="panel panel-default ">
                    <div class="panel-heading">
                        <h3 class="text-center text-uppercase panel-title">View Fees Data</h3>
                    </div>
                    <div class="panel-body">
                        <!-- Payment History has been obtained -->
                        <div class="row">
                            <div class="col-md-12" id="print-window">
                                <h4 class="text-center">Fees Payment Stats</h4>
                                <table class="table table-bordered">
                                    <thead>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Student ID</th>
                                    <th>Class</th>
                                    <th>Required Fess</th>
                                    <th>Paid Fees</th>
                                    <th>Pending Fees</th>
                                    <th>Parent Name</th>
                                    <th>Parent Number</th>
                                    </thead>
                                <?php
                                if(isset($fees_payment_data) && $fees_payment_data != NULL){
                                    $count = 0;
                                    foreach($fees_payment_data as $row){
                                        ?>
                                <tr>
                                    <td><?=++$count;?></td>
                                    <td><?=$row['student_name'];?></td>
                                    <td><?=$row['id'];?>
                                    <td><?=$row['class_name'];?></td>
                                    <td><?=$row['required_fees'];?></td>
                                    <td><?=$row['paid_fees'];?></td>
                                    <td><?=$row['pending_fees'];?></td>
                                    <td><?=$row['parent_name'];?></td>
                                    <td><?=$row['parent_no'];?></td>
                                    <td></td>
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