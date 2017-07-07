<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#messages','Messages',array('data-toggle'=>"tab"));?>
            </li>
             <li class="<?=(isset($active_tab) && $active_tab == "new-message") ? "active" : "";?>">
                <?=anchor('#new-message','New Message',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="messages">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                        <h3 class="panel-title text-center text-uppercase">Messages</h3>
                    </div>
                    <div class="panel-body">
                       <?= (isset($my_messages) && $my_messages === NULL ) ? info_msg("No recent messages") : "";?>
                        
                        <?php
                            if(isset($my_classes))
                            {
                                ?>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                        <table class="table table-bordered">
                            <thead>
                            <th>#</th>
                            <th>Class</th>
                            <th></th>
                            </thead>
                        <?php
                            $count = 0; 
                                foreach($my_classes as $class)
                                {
                                    ?>
                        <tr>
                            <td><?=++$count;?></td>
                            <td><?=$class['class_name'];?></td>
                            <td><?=anchor('staff/class_students/'.$class['class_id'],'View Students',array('class'=>'btn btn-sm btn-info'));?></td>
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