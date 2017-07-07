
<html>
    <head>
    <style>	
    	@media print and(color){
    		* {
    			-webkit-print-color-adjust: exact;
    			print-color-adjust: exact;
    			
    		}
    	}

        .result-content {
            margin: 0 auto;
            width: 100%;
        }

        body {
            font-family: Arial;
            font-size: 10px;
        }

        div, p {
            padding: 3px;
        }

        img {
            width:100px;
            height:100px;
        }
        .text-center {
            text-align: center;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
            font-size: 15px;
        }

        table tr>td {
        }
    	
        table.result td, table.result th {
            border:1px solid #000;
            padding:5px;
        }

        td.score {
            width: 50px;
        }

        thead:before, thead:after { display: none; }
        tbody:before, tbody:after { display: none; }

        
    </style>
    </head>
    <body>
        <div class="result-content">
        <table>
            <tr>
                <td class="text-center">
                    <img src="<?=base_file($school_info['school_logo']);?>">
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <h2><?=get_arr_value($school_info,"school_name");?></h2>
                    <p>
                        <span class="text-italics"><i><?=get_arr_value($school_info,"school_motto")?></i></span><br>
                        <span class="text-italics"><i><?=get_arr_value($school_info,"school_address");?></i></span>
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?=$student['student_name'];?></h4>
                    <h4><?=$class['class_name'];?></h4>
                </td>
            </tr>
            <tr>
                <td>
                  <?php if(!isset($result_table) || (isset($result_table) && $result_table != "all_terms")) { 
                      ?>
                     <table class="table table-bordered result">
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
                            if(isset($subject_scores) && sizeof($subject_scores) > 0) {
                                foreach($subject_scores as $score)
                                {
                                    ?>
                                <tr>
                                    <td><?=++$count;?></td>
                                    <td><?=$score['subject_name'];?></td>
                                    <td class="score"><?=$score['ca1'];?></td>
                                    <td class="score"><?=$score['ca2'];?></td>
                                    <td class="score"><?=$score['exam'];?></td>
                                    <td class="score"><?=number_format($score['total_score'],2);?></td>
                                    <td class="score"><?=number_format($score['class_max_score'],2);?></td>
                                    <td class="score"><?=number_format($score['class_avg_score'],2);?></td>
                                    <td class="score"><?=number_format($score['class_min_score'],2);?></td>
                                    <td ><?=print_position($score['position']);?></td>
                                    <td ><?=get_grade($score['total_score']);?></td>
                                    <td ><?=get_comment($score['total_score']);?></td>
                                </tr>

                                <?php
                                }
                                ?>
                        </tbody>
                    </table>
                    <?php } 
                    }?>
                </td>
            </tr>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td colspan="3">
                           <table class="result">
                                 <thead>
                                 <th colspan="100%" class="text-center">Result Summary</th>
                                </thead>
                                <tr>
                                    <td>Max. Obtainable: <?=number_format($student_report_overview['max_obtainable'],2);?></td>
                                    <td>Total Score: <?=number_format($student_report_overview['total_score'], 2);?></td>
                                    <td>Max Score: <?=$student_report_overview['max_score'];?></td>
                                    <td>Min Score: <?=$student_report_overview['min_score'];?></td>
                                    <td>Position in Class: <?=  print_position($student_report_overview['position']);?></td>
                                </tr>
                                <tr>
                                    
                                    <td>Avg Score: <?=  number_format($student_report_overview['avg_score'],2);?></td>
                                    <td>Students in Class: <?= $class_report_overview['class_size'];?></td>
                                    <td>Class Max Score: <?=  number_format($class_report_overview['max_score'],2);?></td>
                                    <td>Class Min Score: <?=  number_format($class_report_overview['min_score'],2);?></td>
                                    <td>Class Avg Score: <?=  number_format($class_report_overview['avg_score'],2);?></td>
                                </tr>
                                <tr>
                                    <td>Principal's Comment: </td>
                                    <td colspan="100%"><?=$student_report_overview['comment'];?></td>
                                </tr>
                            </table>
                            </td>
                            <td style="width:300px">
                            <table class="result">
                                <thead>
                                <th>#</th>
                                <th>Skill</th>
                                <th>Rating (max: 5)</th>
                                </thead>
                                <tbody>
                                    <?php
                                    if(isset($cog_skills_report) && sizeof($cog_skills_report))
                                    {
                                        $count = 0;
                                        foreach($cog_skills_report as $record) {
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
                </td>
                        </tr>
                    </table>
              
                </td>
            </tr>
        </table>
    </div>
        </body>
</html>