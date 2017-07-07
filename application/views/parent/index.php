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
             <li>
                <?=anchor('parent/p/wards','<i class="fa fa-child"></i> My Wards')?>
            </li>
             <li>
                <?=anchor('parent/p/result_pin_validation','<i class="fa fa-key"></i> Input Result Pin')?>
            </li>
            <li>
                <?=anchor('messages','<i class="fa fa-envelope"></i> Messaging')?>
            </li>
            <li>
                <?=anchor('events','<i class="fa fa-calendar"></i> Events Calendar')?>
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