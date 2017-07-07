<table class="table table-bordered">
    <thead>
        <th colspan="100%" class="text-center">
            <?=isset($result_session) ? $result_session : '';?> 1st term - 3rd term
    </th>
    </thead>
     <thead>
        <th>#</th>
        <th>Subject</th>
        <th>First Term</th>
        <th>Second Term</th>
        <th>Third Term</th>
        <th>Total</th>
        <th>Average</th>
        <th>Max Score</th>
        <th>Min Score</th>
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
            $subject_results = $student_result['subject_results'];
            foreach($subject_results as $record)
            {
                ?>
            <tr>
                <td><?=++$count;?></td>
                <td><?=$record['subject_name'];?></td>
                <td><?=number_format($record['First Term'],2);?></td>
                <td><?=number_format($record['Second Term'],2);?></td>
                <td><?=number_format($record['Third Term'],2);?></td>
                <td><?=number_format($record['total_score'],2);?></td>
                <td><?=  number_format($record['avg_score'],2);?></td>
                <td><?=  number_format($record['max_score'],2);?></td>
                <td><?=  number_format($record['min_score'],2);?></td>
                <td><?=print_position($record['position']);?></td>
                <td><?=($record['grade']);?></td>
                <td><?=($record['comment']);?></td>
                 <?php if($result_display['teacher_name'] == 1) {  ?>
                <td><?=$record['staff_name'];?></td>
                <?php } ?>
            </tr>

            <?php
            }
        }
            ?>
    </tbody>
    </table>