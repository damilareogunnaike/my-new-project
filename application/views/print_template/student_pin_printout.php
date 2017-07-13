<?php $this->load->view("results/header_content");?>
<style>
    @media print and(color){
        * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    p, ol li {
        line-height: 25px;
    }
    .container {
        padding-top:40px;
    }
</style>

<div class="container result">
    <div class="row" style="margin:0">
        <div class="col-md-12 text-center" >
            <?php
                if(isset($school_logo)){
                    ?>
                    <img src="<?=$school_logo;?>" class="">
            <?php
                }
            ?>
            <h2><?=isset($school_name) ? $school_name : '';?></h2>
            <p>
                <span class="text-italics"><i><?=isset($school_motto) ? $school_motto : '';?></i></span><br>
                <span class="text-italics"><i><?=isset($school_address) ? $school_address : '';?></i></span>
            </p>
        </div>
    </div>
    <br/>

    <hr style="margin:0;">
    <br/>
    <h4>Dear Parent/Guardian,</h4>

    <p>
        Kindly follow the procedures to access  the <?=$session_name;?> & <?=$term_name;?> result  for <strong><?=strtoupper($student_name);?>.</strong>
    </p>
    <ol>
        <li>Visit the school website www.evangelmodel.org</li>
        <li>Click on "Result Portal"</li>
        <li>Enter the PIN NO: <strong><?=$pin;?></strong></li>
        <li>Enter the Serial No: <strong><?=$serial;?></strong></li>
        <li>View students result and print.</li>
    </ol>

    <br/>

    <p>
        <em>For More inquiries or further clarifications,  please call 07055103574, 07036643100.</em>
    </p>
    <br>
    <br>
    <div class="text-center text-sm">
        <p>Powered by Apprize Technologies</p>
    </div>
</div>
<div style="page-break-after: always;"></div>