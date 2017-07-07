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
            if(isset($amount) && $amount != NULL){
                ?>
            <h3>Payment Made</h3>
             <table class="table table-bordered">
        <thead>
        <th>#</th>
        <th>Depositor Name</th>
        <th>Payment Description</th>
        <th>Amount Paid (N)</th>
        <th>Date</th>
        </thead>
        <tbody>
    <?php
    $count = 0;
        
            ?>
            <tr>
                <td><?=++$count;?></td>
                <td><?=$depositor_name;?></td>
                <td><?=$payment_description;?></td>
                <td><?=$amount;?></td>
                <td><?=date("Y/m/d H:i:s",time())?></td>
            </tr>
    <?php
        }
    
    ?>
        </tbody>
    </table>
       </div>
      <div class="panel-footer text-center">
             <button onclick="window.print()" class="btn btn-info"><i class="fa fa-print"> </i> Print</button>
         </div>
 </div>