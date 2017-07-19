<?php $this->load->view("results/header_content");?>
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
                <span class="text-italics"><i><?=isset($school_address) ? $school_address : '';?></i></span></br>
                <span class="text-italics"><i><?=isset($school_motto) ? $school_motto : '';?></i></span><br>
            </p>
        </div>
    </div>
    <br/>

    <hr style="margin:0;">
    <br/>

    <p>Dear Parent/Guardian,</p>

    <p>
        Kindly follow the procedures to access  the <span class="bold"><?=$session_name;?> - </span><span class="bold"><?=$term_name;?></span> result  for the following student.
    </p>

    <p>Name: <span class="bold"><?=$student_name;?></span></p>
    <p>
        Class: <span class="bold"><?=$class_name;?></span>
    </p>

    <div class="steps-list">
        <h4 class="bold">Step 1:</h4>
        <p>Visit the school website www.evangelmodel.org</p>

        <h4 class="bold">Step 2:</h4>
        <p>Click on "Result Portal"</p>

        <h4 class="bold">Step 3</h4>
        <p>Enter the PIN NO: <strong><?=$pin;?></strong></p>

        <h4 class="bold">Step 4</h4>
        <p>Enter the Serial No: <strong><?=$serial;?></strong></p>

        <h4 class="bold">Step 5</h4>
        <p>View students result and print.</p>
    </div>

    <br/>
    <p>
        <span class="italic">For More inquiries or further clarifications,  please call <span class="bold">07055103574, 07036643100.</span> or send an email to
        <span class="bold">info@evangelmodel.org</span></span>
    </p>
    <br>
    <br>
    <div class="text-center text-sm company-tag">
        <p>Powered by Apprize Technologies</p>
    </div>
</div>