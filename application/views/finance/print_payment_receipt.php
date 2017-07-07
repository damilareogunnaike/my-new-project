 <div class="panel panel-default report">
        <div class="panel-heading">
            <h3 class="panel-title text-center text-uppercase">Payment Receipt</h3>
        </div>
        <div class="panel-body">
            <div class="row ">
                <div class="col-md-12 text-center">
                    <?=isset($school_logo) ? img(array('src'=>$school_logo,'class'=>'img-passport img-thumbnail')) : '';?>
                    <h2><?=isset($school_name) ? $school_name : '';?></h2>
                    <p class="text-italics"><i><?=isset($school_motto) ? $school_motto : '';?></i></p>
                    <!-- 
                    <p>
                        <?=isset($school_email) ? $school_email : '';?> | 
                        <?=isset($school_phone_no) ? $school_phone_no : '';?><br>
                        <?=isset($school_website) ? $school_website : '';?> | <?=isset($school_address) ? $school_address : '';?>
                    </p> -->
                </div>
            </div>
            <hr>
            <?php
            if(isset($payments_made) && $payments_made != NULL){
                ?>
                <h3 class="text-center">
                    <?=$student_details['surname'] . " " . $student_details['first_name'];?></strong>
                </h3>
            <table class="table table-bordered">
        <thead>
        <th>#</th>
        <th>Bank Name</th>
        <th>Teller Number</th>
        <th>Payment Purpose</th>
        <th>Amount Paid (N)</th>
        <th>Date</th>
        </thead>
        <tbody>
    <?php
    $count = 0;
        foreach($payments_made as $row){
            ?>
            <tr>
                <td><?=++$count;?></td>
                <td><?=$row['bank_name'];?></td>
                <td><?=$row['teller_no'];?></td>
                <td><?=$row['payment_purpose'];?></td>
                <td><?=$row['amount'];?></td>
                <td><?=$row['date_added'];?></td>
            </tr>
    <?php
        }
    }
    ?>
        </tbody>
    </table>
                    <?php
            
            ?>
       </div>
      <div class="panel-footer text-center">
             <button onclick="window.print()" class="btn btn-info"><i class="fa fa-print"> </i> Print</button>
         </div>
 </div>