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
                <h4><?=isset($class_name) ? $class_name : "";?></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                 <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <td>S/N</td>
                            <td>Student Name</td>
                            <td>Pin</td>
                            <td>Serial</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count = 0;
                    if(isset($pins) && sizeof($pins) > 0) {
                        foreach($pins as $record)
                        {
                            ?>
                        <tr>
                            <td><?=++$count;?>.</td>
                            <td><?=$record['student_name'];?></td>
                            <td><?=$record['pin'];?></td>
                            <td><?=$record['serial'];?></td>
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
</div>