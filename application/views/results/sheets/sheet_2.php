<style>
	
	@media print and(color){
		* {
			-webkit-print-color-adjust: exact;
			print-color-adjust: exact;
            
		}

        
	}

    @media print {
        body {
            
        }
        td {
            padding:0px;
        }
    }
	
    .result {
        margin-top:30px;
    }
</style>

<div class="container result">
     
        <div class="row " style="margin:0">
            <div class="col-md-12 text-center" >
                <?=img(array("src"=>$school_info['school_logo'],"class"=>"img"));?>
                <h4><?=get_arr_value($school_info,"school_name");?></h4>
				<span class="text-italics"><i><?=get_arr_value($school_info,"school_address");?></i></span>
                <!-- 
                <p>
                    <?=isset($school_email) ? $school_email : '';?> | 
                    <?=isset($school_phone_no) ? $school_phone_no : '';?><br>
                    <?=isset($school_website) ? $school_website : '';?> | <?=isset($school_address) ? $school_address : '';?>
                </p> -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2 text-left">
                <hr style="margin:0;">
                <?php if($result_display['picture'] == 1) {  ?>
                <?=img(array('src'=>$biodata['image'],'class'=>'img-passport pull-left img-thumbnail','style'=>'margin-right:20px;'));?>
                <?php } ?>
                <h5 style="font-weight:bold;">
                    <?php if($result_display['full_name'] == 1) {  ?>
                    <?=$student['student_name'];?>
                    <?php } ?>
                </h5>
                <?php if($result_display['class'] == 1) {  ?>
                <h5><?=$class['class_name'];?></h4>
                <?php } ?>
                <?php if($result_display['date_of_birth'] == 1) {  ?>
                <h5><?=$student['date_of_birth'];?></h4>
                <?php } ?>
                <?php if($result_display['gender'] == 1) {  ?>
                <h4><?=$student['gender'];?></h4>
                <?php } ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                 <table class="table table-bordered table-condensed">
                    <thead>
                    <th colspan="100%" class="text-center">
                        <?=isset($result_info) ? $result_info['session'] : '';?> | <?=isset($result_info) ? $result_info['term'] : '';?>
                    </th>
                    </thead>
                    <thead>
                        <th>#</th>
                        <th>Subject</th>
                        <th>CA-1</th>
                        <th>CA-2</th>
                        <th>Exam</th>
                        <th class="lg-padding">Total</th>
                        <th>Grade</th>
                        <th>Comment</th>
                    </thead>
                    <tbody>
                    <?php
                    $count = 0;
                    if(isset($subject_scores) && sizeof($subject_scores) > 0) {
                        foreach($subject_scores as $score)
                        {
                            ?>
                        <tr>
                            <td><?=++$count;?></td>
                            <td><?=$score['subject_name'];?></td>
                            <td><?=$score['ca1'];?></td>
                            <td><?=$score['ca2'];?></td>
                            <td><?=$score['exam'];?></td>
                            <td><?=number_format($score['total_score'],2);?></td>
                            <td><?=get_grade($score['total_score']);?></td>
                            <td><?=get_comment($score['total_score']);?></td>
                        </tr>

                        <?php
                        }
                    }
                        ?>
                </tbody>
                </table>

            </div>
        </div>
         <hr/>
        <p class="text-center">
            <span class="text-italics"><i><?=get_arr_value($school_info,"school_motto")?></i></span>
        </p>
</div>