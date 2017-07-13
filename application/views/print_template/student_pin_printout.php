<?php $this->load->view("results/header_content");?>
<style>
    @media print and(color){
        * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    p, ol li, h4 {
        line-height: 25px;
        font-size:16px;
    }
    .container {
        padding-top:40px;
    }

    .steps-list {
        padding-top: 6px;
    }

    .steps-list h4 {
        font-weight:700;
        padding: 0px;
        margin: 0px;
        margin-top: 3px;
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

    <p>Dear Parent/Guardian,</p>

    <p>
        Kindly follow the procedures to access  the <strong><?=$session_name;?> - </strong><strong><?=$term_name;?></strong> result  for the following student.
    </p>

    <p>Name: <strong><?=$student_name;?></strong></p>
    <p>
        Class: <strong><?=$class_name;?></strong>
    </p>

    <div class="steps-list">
        <h4>Step 1:</h4>
        <p>Visit the school website www.evangelmodel.org</p>

        <h4>Step 2:</h4>
        <p>Click on "Result Portal"</p>

        <h4>Step 3</h4>
        <p>Enter the PIN NO: <strong><?=$pin;?></strong></p>

        <h4>Step 4</h4>
        <p>Enter the Serial No: <strong><?=$serial;?></strong></p>

        <h4>Step 5</h4>
        <p>View students result and print.</p>
    </div>

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