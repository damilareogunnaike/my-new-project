<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#recv-messages','Received Messages',array('data-toggle'=>"tab"));?>
            </li>
            <li class="<?=(isset($active_tab) && $active_tab == "sent-messages") ? "active" : "";?>">
                <?=anchor('#sent-messages','Sent Messages',array('data-toggle'=>"tab"));?>
            </li>
             <li class="<?=(isset($active_tab) && $active_tab == "new-message") ? "active" : "";?>">
                <?=anchor('#new-message','New Message',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="recv-messages">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                        <h3 class="panel-title text-center text-uppercase">Inbox</h3>
                    </div>
                    <div class="panel-body">
                       <?= (isset($messages) && $messages === NULL ) ? info_msg("No recent messages") : "";?>
                        
                        <?php
                            if(isset($messages))
                            {
                                ?>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                        <table class="table table-bordered">
                            <thead>
                            <th>#</th>
                            <th>Sender</th>
                            <th>Message</th>
                            </thead>
                        <?php
                            $count = 0; 
                                foreach($messages as $message)
                                {
                                    ?>
                        <tr>
                            <td><?=++$count;?></td>
                            <td><?=$message['recipient'];?></td>
                            <td><?=$message['message'];?></td>
                            <td>
                                <button class="btn btn-xs btn-info" data-toggle="popover" data-placement="top"
                                        data-html="true"
                                        data-content='<?=$this->load->view('partials/message_popover',
                                                array('recipient_id'=>$message['sender_id'],
                                                    'target'=>'message','form_action'=>'parent/send_message'),true);?>'
                                                    >
                                    <i class="fa fa-reply"></i>
                                </button>
                            </td>
                        </tr>
                        <?php
                                }
                                ?>
                        </table> 
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div>
            </div>
            
             <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="sent-messages">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                        <h3 class="panel-title text-center text-uppercase">Outbox</h3>
                    </div>
                    <div class="panel-body">
                       <?= (isset($sent_messages) && $sent_messages === NULL ) ? info_msg("No recent messages") : "";?>
                        
                        <?php
                            if(isset($sent_messages))
                            {
                                ?>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                        <table class="table table-bordered">
                            <thead>
                            <th>#</th>
                            <th>Recipient</th>
                            <th>Message</th>
                            </thead>
                        <?php
                            $count = 0; 
                                foreach($sent_messages as $message)
                                {
                                    ?>
                        <tr>
                            <td><?=++$count;?></td>
                            <td><?=$message['recipient'];?></td>
                            <td><?=$message['message'];?></td>
                            <td>
                                <button class="btn btn-xs btn-info" data-toggle="popover" data-placement="top"
                                        data-html="true"
                                        data-content='<?=$this->load->view('partials/message_popover',
                                                array('recipient_id'=>$message['sender_id'],
                                                    'target'=>'message','form_action'=>'parent/send_message'),true);?>'
                                                    >
                                    <i class="fa fa-reply"></i>
                                </button>
                            </td>
                        </tr>
                        <?php
                                }
                                ?>
                        </table> 
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div>
            </div>
            
             <div class="tab-pane <?=(isset($active_tab) && $active_tab == "new-message") ? "active" : "";?>" id="new-message">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                        <h3 class="panel-title text-center text-uppercase">New Message</h3>
                    </div>
                   <div class="panel-body">
                       
                       <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                               
                                <?=form_open("staff/send_message");?>
                                <div class="form-group">
                                    <label class="control-label">
                                        Choose Recipient
                                    </label>
                                    <input type="hidden" name="recipients[]" id="input_recipient">
                                    <div class="row">
                                        <label class="col-md-4">
                                            Administrator
                                            <input type="checkbox" name="recipients" value="administrator">
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">
                                        Message
                                    </label>
                                    <textarea class="form-control" name="message"></textarea>
                                </div>
                                <?=form_close();?>
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