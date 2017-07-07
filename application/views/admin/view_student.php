<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="active">
                <?=anchor('#view_student','View Student',array('data-toggle'=>'tab'));?>
            </li>
        </ul>
        <?=isset($msg) ? $msg : '';?>
        <div class="tab-content">
            
              <!-- View Student -->
            <div class="tab-pane active" id="view_student">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center text-uppercase">View Students</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped datatable">
                            <thead>
                                <th>#</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th></th>
                            </thead>
                            <tbody>
                                <?php 
                                if(isset($students) && sizeof($students) > 0)
                                {
                                    $count = 0;
                                    foreach($students as $student)
                                    {
                                    ?>
                                <tr>
                                    <td class="text-center"><?=++$count?></td>
                                    <td><?= anchor('admin/view_stud_record/'.$student['id'],$student['surname']. " " . $student['first_name'] . " " . $student['middle_name']);?> </td> 
                                    <td><?=$student['class']?></td>
                                    <td>
                                        <?= anchor('admin/view_stud_record/'.$student['id'],'<i class="fa fa-eye"></i>',array('class'=>' btn btn-info btn-xs'))?>
                                        <?=anchor('admin/del_student/'.$student['id'],'<i class="fa fa-trash"></i>',array('class'=>'btn-danger btn-xs pull-right del_btn'));?>
                                    </td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>