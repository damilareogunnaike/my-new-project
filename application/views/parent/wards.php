<div class="row">
    <div class="col-md-12">
        <?=isset($msg) ? $msg : '';?>
        <?php
            if(isset($my_wards) && sizeof($my_wards) > 0)
            {
                echo "<ul class='media-list'>";
                foreach($my_wards as $ward)
                {
                    ?>
                <li class="media">
                    <?=img(array('src'=>$ward['image'],'class'=>'pull-left img-passport media-object img img-responsive img-thumbnail'));?>
                    <div class="media-body">
                        <h3 class="media-heading"><?=anchor('parent/view_ward/'.$ward['student_id'],$ward['full_name']);?></h3>
                        <p>
                        <h4>Class: <?=$ward['class_name'];?></h4>
                        <h4>Date of Birth: <?=$ward['date_of_birth'];?></h4>
                        </p>
                    </div>
                </li>
        <?php
                }
                echo "</ul>";
            }
        ?>
    </div>
</div>