<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
             <li class="<?=(!isset($active_tab) ) ? "active" : '';?>">
                <?=anchor('#enter_health_records','Enter Health Records',array('data-toggle'=>"tab"));?>
            </li>
              <li class="<?=(isset($active_tab) && ($active_tab === "check_health_records")) ? "active" : '';?>">
                <?=anchor('#check_health_records','Check Health Records',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="enter_health_records">
                <div class="panel panel-default  ">
                     <div class="panel-heading ">
                         <h3 class="text-center text-uppercase panel-title">Check Health Records</h3>
                    </div>
                    <div class="panel-body">
                       <div class="row">
                           <div class="col-md-8 col-md-offset-2">
                               <?=form_open("health/search_student");?>
                               <div class="form-group">
                                   <div class="input-group">
                                       <span class="input-group-btn">
                                           <?=form_button(array('class'=>'btn btn-primary','content'=>'Enter Student ID/Name'));?>
                                       </span>
                                       <?=form_input(array('class'=>'form-control input','name'=>'student_id','required'=>'required'));?>
                                        <span class="input-group-btn">
                                           <?=form_submit(array('class'=>'btn btn-primary','value'=>'Search Student'));?>
                                       </span>
                                   </div>
                               </div>
                               <?=form_close();?>
                               
                                <?php if(isset($students) && sizeof($students) > 0){
                                echo "<h3>Search Result</h3>";
                                echo "<ul class='list-group'>";
                               foreach($students as $student){
                             ?>
                             <li class="list-group-item">
                                 <?=anchor('health/enter_health_record/'.$student['id'],$student['surname'] . " " . $student['first_name'] . " " . $student['middle_name']);?> 
                             </li>
                           <?php
                               }
                               echo "</ul>";
                           }
                           ?>
                             <hr>
                              <?php if(isset($student_details)) { ?>
                             <div id="health_details_record">
                                 <?=form_open("health/save_health_records");?>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <?=img(array('src'=>$student_details['image'],'class'=>'img-passport img-sm img-responsive'));?>
                                        </div>
                                        <div class="col-md-10">
                                            <h4><?=$student_details['surname'] . " " . $student_details['first_name'] . " " . $student_details['middle_name'];?></h4>
                                            <h5><?=$student_details['class_name']; ?></h5>
                                        </div>
                                    </div>
                                 <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Health Description</label>
                                                <?=  form_textarea(array('class'=>'form-control','name'=>'description','required'=>'required'));?>
                                            </div>
                                            <div class="form-group text-right">
                                                <?=form_hidden("student_id",$student_details['id']);?>
                                                 <?=form_reset(array('class'=>'btn btn-warning ','value'=>'Reset'));?>
                                                <?=form_submit(array('class'=>'btn btn-primary ','value'=>'Submit'));?> 
                                            </div>
                                            
                                        </div>
                                    </div>
                                 <?=form_close();?>
                             </div>
                             <?php } ?>
                           </div>
                          
                       </div>
                    </div>
                    <div class="panel-footer">
                        
                    </div>
                </div>
            </div>
            
            <div class="tab-pane <?=(isset($active_tab) && $active_tab === "check_health_records") ? "active" : '';?>" id="check_health_records">
                <div class="panel panel-default" >
                    <div class="panel-heading">
                        <h3 class="text-center text-uppercase panel-title">Check Health Records</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                               <?=form_open("health/search_student/tab2");?>
                               <div class="form-group">
                                   <div class="input-group">
                                       <span class="input-group-btn">
                                           <?=form_button(array('class'=>'btn btn-primary','content'=>'Enter Student ID/Name'));?>
                                       </span>
                                       <?=form_input(array('class'=>'form-control input','name'=>'student_id','required'=>'required'));?>
                                        <span class="input-group-btn">
                                           <?=form_submit(array('class'=>'btn btn-primary','value'=>'Search Student'));?>
                                       </span>
                                   </div>
                               </div>
                               <?=form_close();?>
                               
                                <?php if(isset($students) && sizeof($students) > 0){
                                echo "<h3>Search Result</h3>";
                                echo "<ul class='list-group'>";
                               foreach($students as $student){
                             ?>
                             <li class="list-group-item">
                                 <?=anchor('health/view_health_records/'.$student['id'],$student['surname'] . " " . $student['first_name'] . " " . $student['middle_name']);?> 
                             </li>
                           <?php
                               }
                               echo "</ul>";
                           }
                           ?>
                             <hr>
                              <?php if(isset($health_records)) { 
                                  ?>
                             <table class="table table-bordered">
                                 <thead>
                                     <tr>
                                         <th>#</th>
                                         <th>Date</th>
                                         <th>Description</th>
                                         <th></th>
                                     </tr>
                                     
                                 <tbody>
                                     <?php
                                     $count = 0;
                                     foreach($health_records as $record) {
                                  ?>
                                     <tr>
                                         <td><?=++$count;?></td>
                                         <td><?=$record['date_added'];?></td>
                                         <td><?=$record['description'];?></td>
                                         <td><?=anchor('health/delete_record/'.$record['health_record_id'],'<i class="fa fa-times"></i>',array('class'=>'text-danger'));?></td>
                                     </tr>
                              <?php } ?>
                                 </tbody>
                                 </thead>
                             </table>
                             <?php } ?>
                           </div>
                        </div>
                    </div>
                    
                    <div class="panel-footer">
                          <div class="text-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Tab Content -->
    </div>
</div>