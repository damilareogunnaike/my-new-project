    <?php
    if(isset($amount) && $amount != NULL){
        ?>
        <h3 class="text-center">
            Payment Receipt 
        </h3>
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