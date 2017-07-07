<!-- Main Content -->
<div class="row main-view">    
    <!-- Side Bar -->    
    <div class="col-md-2 sidebar">        
          <ul class="nav">
                 <li><?=anchor($this->session->userdata("role"),'<i class="fa fa-dashboard"></i> Dashboard');?></li>  
                 <li class="divider"></li>        
                 <li><?=anchor('#','<i class="fa fa-child"></i> Students <i class="fa pull-right fa-caret-right"></i>',array('class'=>'dropdown-toggle','data-toggle'=>'submenu'))?> 
                    <ul class="submenu nav">
                        <li><?=anchor('admin/p/add_student','Add Student')?></li> 
                        <li><?=anchor('admin/p/view_student','Search for Student')?></li>                                                                                                                                                    
                        <li><?=anchor('admin/p/student_parent','Student Parent')?></li>                                                                                                                                                       
                    </ul>            
                </li>  
                <li><?=anchor('#','<i class="fa fa-user"></i> Staff <i class="fa pull-right fa-caret-right"></i>',array('class'=>'dropdown-toggle','data-toggle'=>'submenu'))?>                
                    <ul class="submenu nav">                    
                        <li><?=anchor('admin/p/staff','Add/View Staff')?></li>                    
                        <li><?=anchor('admin/p/assign_class_teacher','Assign Staff to Class')?></li>
                        <li><?=anchor('admin/p/assign_subject_teacher','Assign Staff to Subject')?></li>                
                    </ul>            
                </li>
                <li><?=anchor('#','<i class="fa fa-users"></i> Users <i class="fa pull-right fa-caret-right"></i>',array('class'=>'dropdown-toggle','data-toggle'=>'submenu'))?>
                    <ul class="submenu nav">                    
                        <li><?=anchor('admin/p/manage_parent_users','Parent Users')?></li>
                    </ul>             
                </li>        
                <li class="divider"></li>         
                <li>
                    <?=anchor('#','<i class="fa fa-list-alt"></i> Result <i class="fa pull-right fa-caret-right"></i>',array('class'=>'dropdown-toggle','data-toggle'=>'submenu'))?>                
                    <ul class="submenu nav">                    
                        <li><?=anchor('results/input-result','Input Results')?></li>
                        <li><?=anchor('results/calculate-result','Calculate Report')?></li>
                        <li><?=anchor('admin/class_students','Student Results')?></li>                   
                        <li><?=anchor('admin/p/change_class','Class Change')?></li>                    
                        <li><?=anchor('admin/p/principal_comments','Principal\'s Comment')?></li>                    
                        <li><?=anchor('admin/p/report_settings','Report Sheet Settings')?></li>                    
                        <li><?=anchor('admin/p/result_pins','Result Pins')?></li>                
                    </ul>            
                </li>      
                 <li>
                    <?=anchor('#','<i class="fa fa-list-alt"></i> Classes <i class="fa pull-right fa-caret-right"></i>',array('class'=>'dropdown-toggle','data-toggle'=>'submenu'))?>                
                    <ul class="submenu nav">                    
                        <li><?=anchor('classes','Manage Classes')?></li>                    
                        <li><?=anchor('classes/change-class','Change Students Class')?></li>    
                    </ul>            
                </li>   
				<li>
                    <?=anchor('#','<i class="fa fa-bar-chart"></i> Report Wizard<i class="fa pull-right fa-caret-right"></i>',array('class'=>'dropdown-toggle','data-toggle'=>'submenu'))?>                
                    <ul class="submenu nav">      
                        <li><?=anchor('reports/basic-reports','Basic Reports')?></li>                           
                    </ul>            
                </li>		
                
                <li class="divider"></li>  
                 <li>
                    <?=anchor('#','<i class="fa fa-envelope"></i> Messaging <i class="fa pull-right fa-caret-right"></i>',array('class'=>'dropdown-toggle','data-toggle'=>'submenu'))?>
                     <ul class="submenu nav">                    
                        <li><?=anchor('messages','In-app Messaging')?></li>
                        <li><?=anchor('messages/sms','SMS Messaging')?></li>
                    </ul> 
                    
                </li>
                <li>
                    <?=anchor('events','<i class="fa fa-calendar"></i> Events Calendar')?>
                </li>
                <li class="divider"></li>    
                <!-- <li>
                  <?=anchor('#','<i class="fa fa-language"></i> Administration')?>
                </li> --> 
                <li><?=anchor('#','<i class="fa fa-institution"></i> School Setup <i class="fa pull-right fa-caret-right"></i>',array('class'=>'dropdown-toggle','data-toggle'=>'submenu'))?>
                    <ul class="submenu nav ">                    
                        <li><?=anchor('school_setup/manage_class','Manage Classes')?></li>                    
                        <li><?=anchor('school_setup/manage_subject','Manage Subjects')?></li>                    
                        <li><?=anchor('admin/p/manage_term','Manage Session/Term')?></li>
                    </ul>
                </li>
                <li>
                    <?=anchor('#','<i class="fa fa-list-alt"></i> Academic Settings <i class="fa pull-right fa-caret-right"></i>',array('class'=>'dropdown-toggle','data-toggle'=>'submenu'))?>                
                    <ul class="submenu nav">                    
                        <li><?=anchor('subject-config','Subjects Config.')?></li>                    
                    </ul>            
                </li>   
                <li><?=anchor('admin/p/school_setting','<i class="fa fa-cog"></i> School Settings')?></li>           
                <!-- <li><?=anchor('admin/p/sys_integ','<i class="fa fa-exchange"></i> System Integration')?></li> -->
                <li class="divider"></li>    
                <li><?=anchor('admin/p/administrators','<i class="fa fa-male"></i> Administrators')?></li>
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
            <div class="col-md-9">            
            </div>        
        </div>        
        <loader-view></loader-view>
        <?=$page_content?>    
        </div>
</div>