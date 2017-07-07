<style>
	
	@media print and(color){
		* {
			-webkit-print-color-adjust: exact;
			print-color-adjust: exact;
			
		}
	}
	
</style>

<div class="container result">
     <div class="panel panel-default report">
        <div class="panel-heading">
            <h3 class="panel-title text-center text-uppercase">Report Sheet</h3>
        </div>
        <div class="panel-body">
            <div class="row " style="margin:0">
                <div class="col-md-12 text-center" >
                    <?=isset($school_logo) ? img(array('src'=>$school_logo,'class'=>'img-passport img-thumbnail')) : '';?>
                    <h2><?=isset($school_name) ? $school_name : '';?></h2>
					<p>
						<span class="text-italics"><i><?=isset($school_motto) ? $school_motto : '';?></i></span><br>
						<span class="text-italics"><i><?=isset($school_address) ? $school_address : '';?></i></span>
					</p>
                    <!-- 
                    <p>
                        <?=isset($school_email) ? $school_email : '';?> | 
                        <?=isset($school_phone_no) ? $school_phone_no : '';?><br>
                        <?=isset($school_website) ? $school_website : '';?> | <?=isset($school_address) ? $school_address : '';?>
                    </p> -->
                </div>
            </div>
            <hr style="margin:0;">
            <div class="row">
                <div class="col-md-6 text-left">
                    <?php if($result_display['picture'] == 1) {  ?>
                    <?=img(array('src'=>$biodata['image'],'class'=>'img-passport pull-left img-thumbnail','style'=>'margin-right:20px;'));?>
                    <?php } ?>
                    <h4 style="font-weight:bold;">
                        <?php if($result_display['full_name'] == 1) {  ?>
                        <?=$biodata['surname'];?> <?=$biodata['first_name'];?> <?=$biodata['middle_name'];?>
                        <?php } ?>
                    </h4>
                    <?php if($result_display['class'] == 1) {  ?>
                    <h4><?=$biodata['class'];?></h4>
                    <?php } ?>
                    <?php if($result_display['date_of_birth'] == 1) {  ?>
                    <h4><?=$biodata['date_of_birth'];?></h4>
                    <?php } ?>
                    <?php if($result_display['gender'] == 1) {  ?>
                    <h4><?=$biodata['gender'];?></h4>
                    <?php } ?>
                </div>
                <div class="col-md-6 text-right">
                   
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                    <?php if(!isset($result_table) || (isset($result_table) && $result_table != "all_terms")) { 
                      ?>
                     <table class="table table-bordered">
                        <thead>
                        <th colspan="100%" class="text-center">
                            <?=isset($result_session) ? $result_session : '';?> | <?=isset($result_term) ? $result_term : '';?>
                        </th>
                        </thead>
                        <thead>
                            <th>#</th>
                            <th>Subject</th>
                            <th>CA-1</th>
                            <th>CA-2</th>
                            <th>Exam</th>
                            <th class="lg-padding">Total</th>
                            <th >Class Max</th>
                            <th >Class Avg.</th>
                            <th>Class Min.</th>
                            <th>Position</th>
                            <th>Grade</th>
                            <th>Comment</th>
                            <?php if($result_display['teacher_name'] == 1) {  ?>
                            <th>Subject Teacher </th>
                            <?php } ?>
                        </thead>
                        <tbody>
                        <?php
                        $count = 0;
                        if(isset($student_result) && sizeof($student_result) > 0) {
                            foreach($student_result as $record)
                            {
                                ?>
                            <tr>
                                <td><?=++$count;?></td>
                                <td><?=$record['subject_name'];?></td>
                                <td><?=$record['ca1'];?></td>
                                <td><?=$record['ca2'];?></td>
                                <td><?=$record['exam'];?></td>
                                <td><?=number_format($record['total_score'],2);?></td>
                                <td><?=  number_format($record['max_score'],2);?></td>
                                <td><?=  number_format($record['avg_score'],2);?></td>
                                <td><?=  number_format($record['min_score'],2);?></td>
                                <td><?=print_position($record['position']);?></td>
                                <td><?=get_grade($record['total_score']);?></td>
                                <td><?=get_comment($record['total_score']);?></td>
                                 <?php if($result_display['teacher_name'] == 1) {  ?>
                                    <td><?=$record['staff_name'];?></td>
                                    <?php } ?>
                            </tr>

                            <?php
                            }
                            ?>
                    </tbody>
                    </table>
                    <?php } 
                    }
                    else if(isset($result_table) && $result_table == "all_terms") {  
                        echo $this->load->view('results/all_terms',NULL,true);
                    } ?>
                   <div class="row">
    <div class="col-md-8">
        <table class="table table-bordered table-responsive table-condensed pull-left">
                     <thead>
                     <th colspan="100%" class="text-center">Result Summary</th>
                    </thead>
                    <tr>
                        <td>Max. Obtainable: <?=$student_result_summary['max_obtainable'];?></td>
                        <td>Total Score: <?=  number_format($student_result_summary['total_score'],2);?></td>
                        <td>Max Score: <?= number_format($student_result_summary['max_score'],2);?></td>
                        <td>Min Score: <?=  number_format($student_result_summary['min_score'],2);?></td>
						<td>Position in Class: <?=  print_position($class_result_summary['position']);?></td>
                    </tr>
                    <tr>
                        
						<td>Avg Score: <?=  number_format($student_result_summary['avg_score'],2);?></td>
                        <td>Students in Class: <?= $class_result_summary['class_size'];?></td>
                        <td>Class Max Score: <?=  number_format($class_result_summary['max_score'],2);?></td>
                        <td>Class Min Score: <?=  number_format($class_result_summary['min_score'],2);?></td>
                        <td>Class Avg Score: <?=  number_format($class_result_summary['avg_score'],2);?></td>
                    </tr>
                    <tr>
                        <td>Principal's Comment: </td>
                        <td colspan="100%"><?=$student_result_summary['principals_comment'];?></td>
                    </tr>
        </table>
        </div>
    <div class="col-md-4">
        <?php if(isset($result_display['cognitive_skills']) && $result_display['cognitive_skills'] == 1) {  ?>
        <table class="table table-bordered pull-right">
            <thead>
            <th>#</th>
            <th>Skill</th>
            <th>Rating (max: 5)</th>
            </thead>
            <tbody>
                <?php
                if(isset($cog_skills_result) && sizeof($cog_skills_result))
                {
                    $count = 0;
                    foreach($cog_skills_result as $record) {
                    ?>
                <tr>
                    <td><?=++$count;?></td>
                    <td>
                        <?=$record['skill'];?>
                    </td>
                    <td>
                        <?=($record['rating'] != NULL) ? $record['rating'] : 0;?>
                    </td>
                </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <?php } ?>
    </div>
</div>
                </div>
            </div>
        </div>
         <div class="panel-footer text-center">
             <button onclick="window.print()" class="btn btn-info print-btn"><i class="fa fa-print"> </i> Print</button>
         </div>
       </div>
</div>