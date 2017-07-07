<div class="row content-nav">
    <div class="col-md-12">
        
    </div>
</div>

<!-- Main Content -->
<div class="row main-view">
    <!-- Side Bar -->
    <div class="col-md-2 sidebar">
        <ul class="nav nav">
            <li><?=anchor($this->session->userdata("role"),'<i class="fa fa-home"></i> Dashboard');?></li>
            <li><?=anchor('finance/p/fees_config','<i class="fa fa-calculator"></i> Fees Configuration')?></li>
            <li><?=anchor('finance/p/pay_fees','<i class="fa fa-credit-card"></i> Fees Payment')?></li>
            <li><?=anchor('finance/p/cash_payment','<i class="fa fa-credit-card"></i> Cash Payment')?></li>
            </li>
             <li>
                <?=anchor('finance/p/payment_history','<i class="fa fa-list"></i> Payment Report')?>
            </li>
            <li>
                <?=anchor('finance/p/unpaid_fees','<i class="fa fa-list"></i> Pending Fees Student')?>
            </li>
            <li>
                <?=anchor('finance/p/calendar','<i class="fa fa-calendar"></i> School Calendar')?>
            </li>
            <li>
                <?=anchor('messages','<i class="fa fa-envelope"></i> Messaging')?>
            </li>
        </ul>
    </div>
    
    <!-- Main Display -->
    <div class="col-md-10 display">
        <!-- Notifications Tab -->
        <div class="row">
            <div class="col-md-12">
                <h3 class="display-title">
            <?=isset($page) ? $page : '';?>
                <span class="pull-right">
                        <h4><strong> <?=$this->session->userdata('user_real_name');?></strong></h4>
                    </span>
                </h3>
            </div>
        </div>
        <?=$page_content?>
    </div>
</div>