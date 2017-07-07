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
            <div class="col-md-8 col-md-offset-2">
                 <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <td class="text-center" colspan="100%">
                                <h4><?=$result_info['session'] . " | " . $result_info['term'] . " | " . $result_info['class'];?></h4>
                            </td>
                        </tr>
                    </thead>
                    <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>Total Score</th>
                        <th>Average Score</th>
                        <th>Position</th>
                    </thead>
                    <tbody>
                    <?php
                    $count = 0;
                    if(isset($class_result) && sizeof($class_result) > 0) {
                        foreach($class_result as $record)
                        {
                            ?>
                        <tr>
                            <td><?=++$count;?></td>
                            <td><?=$record['student_name'];?></td>
                            <td><?=$record['total_score'];?></td>
                            <td><?=$record['average'];?></td>
                            <td><?=print_position($record['position']);?></td>
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