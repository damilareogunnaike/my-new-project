<?php $this->load->view('header_files');?>
<body>
<div class="header navbar navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <?=anchor('','<i class="fa fa-institution"></i>' . $this->config->item("app_name"),array('class'=>'navbar-brand'))?>
            <button class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse" type="button">
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <?php if($this->session->userdata('logged_in')) { ?>
                     <?=anchor($this->session->userdata("role"),$this->session->userdata('username'). ' <i class="fa fa-caret-down"></i>',array('class'=>'dropdown-toggle','data-toggle'=>'dropdown'))?>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><?=anchor($this->session->userdata("role") . "/change_password",'<i class="fa fa-user-secret"></i> Change Password')?></li> 
                        <li><?=anchor('logout','<i class="fa fa-power-off"></i> Logout',array('class'=>'text text-danger'))?></li>
                    </ul>
                    <?php
                    } else  { ?>
                    <?=anchor('login','<i class="fa fa-lock"></i> Login')?>
                    <?php
                    }
                    ?>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="content">
<div class="container-fluid">
