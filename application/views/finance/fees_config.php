<div class="row">    
<div class="col-md-12">     
   <ul class="nav nav-tabs">  
   
<li class="<?=(!isset($active_tab)) ? "active" : '';?>"><?=anchor('#payment_purpose','Payment Purpose',array('data-toggle'=>"tab"));?> </li>  
          <li class="<?=(isset($active_tab) && $active_tab == "set_fees") ? "active" : "";?>">             
	  <?=anchor('#set_fees','Set Fees',array('data-toggle'=>"tab"));?>            </li>          
	  <li class="<?=(isset($active_tab) && $active_tab == "view_fees") ? "active" : "";?>">        
	  <?=anchor('#view_fees','View Fees',array('data-toggle'=>"tab"));?>            </li>        </ul>    
	  <!-- Status Message -->        <?=isset($msg) ? $msg : '';?>                <!-- Tab Content -->        <div class="tab-content">   
	  <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="payment_purpose">                <div class="panel panel-default ">        
	  <div class="panel-heading ">                        <h3 class="panel-title text-center text-uppercase">Payment Purpose</h3>               
	  </div>                    <div class="panel-body">                        <div class="row">                            <div class="col-md-6">      
	  <h4>Add Payment Purpose</h4>                                <?=form_open('finance/add_payment_purpose')?>                               
	  <div class="form-group">                                    <label class="control-label">Purpose</label>                              
      <?=form_input(array('class'=>'form-control','required'=>'required','name'=>'purpose'))?>                             
	  </div>                                <div class="form-group text-center">                                    <hr>                                
	  <?=form_submit(array('class'=>'btn btn-primary','value'=>'Submit'))?>                                  
	  <?=form_reset(array('class'=>'btn btn-warning','value'=>'Reset'))?>                                </div>                        
	  <?=form_close();?>                            </div>                            <div class="col-md-6">                              
	  <h4>Payment Purposes</h4>                                <table class="table">                                   
	  <?php                                    if(isset($payment_purposes) && sizeof($payment_purposes) > 0)                  
		  {                                        $count = 0;                                       
	  foreach($payment_purposes as $purpose)                                        {                                            ?>                                    <tr>                                        <td><?=++$count?></td>                                        <td><?=$purpose['purpose'];?></td>
<td>                                            <?=anchor('finance/del_payment_purpose/'.$purpose['id'],"<i class='fa fa-trash-o'></i>",array('class'=>'text-danger delete_btn'))?>    
	  </td>                                    </tr>                                    <?php                                        }                                    }                                    ?>                                </table>                            </div>                     
	  </div>                    </div>                    <div class="panel-footer">                    </div>  
	  </div>            </div>            <div class="tab-pane <?=(isset($active_tab) && $active_tab == "set_fees") ? "active" : "";?>" id="set_fees">                <div class="panel panel-default "> 
                   <div class="panel-heading">                      
				   <h3 class="panel-title text-center text-uppercase">Fees Settings</h3>     
				   
				   </div>                    <div class="panel-body">                    
				   <?=form_open('finance/set_fees')?>                        <div class="form-group row">       
				   <div class="col-md-4">                                <label class="control-label">Select Class(es)</label>                             
				   <select class="form-control" name="class_id[]" multiple="multiple" required="required">        
				   
				   <?php                               
				   foreach($classes as $class)                                {                                  
				   echo "<option value='{$class['class_id']}'>{$class['class_name']}</option>";                                }                                ?>                              
				   </select>                            </div>                             <div class="col-md-4">                         
				   <label class="control-label">Select Payment Purpose</label>                            
				   <select class="form-control" name="payment_purpose_id" required="required">     
				   <?php                                foreach($payment_purposes as $purpose)                               
				   {                                    echo "<option value='{$purpose['id']}'>{$purpose['purpose']}</option>";                                }                                ?>                                </select>                            </div>                             <div class="col-md-4">                                <label class="control-label">Enter Amount</label>                                <?=form_input(array('class'=>'form-control','name'=>'amount','required'=>'required','type'=>'number'));?>                            </div>                        </div>                        <div class="form-group row text-center">                            <hr>                            <?=form_submit(array('class'=>'btn btn-primary','value'=>'Set Fees'))?>                            <?=form_reset(array('class'=>'btn btn-warning','value'=>'Reset'))?>                        </div>                        <?=form_close()?>                    </div>                    <div class="panel-footer">                                            </div>                </div>            </div>             <div class="tab-pane <?=(isset($active_tab) && $active_tab == "view_fees") ? "active" : "";?>" id="view_fees">                <div class="panel panel-default">                    <div class="panel-heading">                
				   <h3 class="panel-title text-center text-uppercase">View Fees Settings</h3>                    </div>          
				   <div class="panel-body">                        <div class="form-group row">                        
				   <?=form_open('finance/view_fees')?>                            <div class="col-md-4">           
				   <label class="control-label">Select Class</label>                                <select class="form-control" name="class_id" required="required">                                    <option value="-1">All Classes</option>                                <?php                                if(isset($classes) && $classes != NULL) {                                    foreach($classes as $class)                                    {                                        echo "<option value='{$class['class_id']}'>{$class['class_name']}</option>";                                    }                                }                                ?>                                </select>                            </div>                             <div class="col-md-4">                                <label class="control-label">Select Payment Purpose</label> 
				   <select class="form-control" name="payment_purpose_id" required="required" >                                  
				   <option value="-1">All Purposes</option>                                <?php                                
				   foreach($payment_purposes as $purpose)                                {                                    
				   echo "<option value='{$purpose['id']}'>{$purpose['purpose']}</option>";                                
				   }                                ?>                                </select>                           
				   </div>                            <div class="col-md-4">                               
				   <label class="control-label"><?=nbs(20);?></label>                               
				   <?=form_submit(array('class'=>'btn btn-primary form-control','value'=>'View Fees'))?>               
				   </div>                            <?=form_close()?>                        </div>                      
				   <div class="form-group row text-center">                            <hr>                            
				   <?php                            if(isset($fees_settings) && $fees_settings != NULL)                      
					   {                                ?>                            <table class="table table-bordered text-left">                                <thead>                                <th>#</th>                                <th>Class</th>                                <th>Fees Purpose</th>                                <th>Amount</th>                                <th></th>                                <tbody>                                    <?php                                    $count = 0;                                 
				   foreach($fees_settings as $settings)                                    {                                        ?>                                    <tr>                                        <td><?=++$count;?></td>                                        <td><?=$settings['class_name'];?></td>                                        <td><?=$settings['purpose'];?></td>                                        <td><?=$settings['amount'];?></td>                                        <td>                                            <?=anchor('#',"<i class='fa fa-edit'></i>",array('class'=>'text-info'))?>                                            <?=anchor('finance/del_fees_settings/'.$settings['fees_settings_id'],"<i class='fa fa-trash-o'></i>",array('class'=>'text-danger delete_btn'))?>                                        </td>                                    </tr>                                    <?php                                    }                                    ?>                                </tbody>                                </thead>                            </table>                            <?php                            }                            ?>                        </div>                    </div>                    <div class="panel-footer">                                            </div>                </div>          
				   </div>        </div>        <!-- End of Tab Content -->    </div></div>