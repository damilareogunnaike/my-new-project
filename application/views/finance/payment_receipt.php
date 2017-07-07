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
        <th></th>
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
                <td><?=anchor("finance/del_payment/".$student_id . "/" . $row['fees_payment_id'],"<i class='fa fa-times'></i>",array("class"=>"text-sm text-danger"));?>
            </tr>
    <?php
        }
    }
    ?>
        </tbody>
    </table>