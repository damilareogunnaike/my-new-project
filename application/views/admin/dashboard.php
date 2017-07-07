<div class="row">
    <div class="col-md-12">
        <div class="tile tile-orange tile-wide tile-medium">
            <div class="tile-content">
                <h2><i class="fa fa-user"></i> <br>Staff</h2>
                <h1><?=isset($total_staffs) ? $total_staffs : 0;?></h1>
            </div>
        </div>
        <div class="tile tile-alizarin tile-wide tile-medium">
            <div class="tile-content">
                <h2><i class="fa fa-child"></i> <br>Students</h2>
                <br>
                <h2>Total : <?=$total_students;?></h2>
                <h4>
                    <span class="">Male: <?=$total_male_students;?></span> 
                    <span class="">Female:  <?=$total_female_students;?></span>
                </h4>
            </div>
        </div>
        <div class="tile tile-double tile-wide tile-medium">
            <div class="tile-content">
                <h2><i class="fa fa-institution"></i> <br>Classes</h2>
                <h1><?=isset($total_classes) ? $total_classes : 0;?></h1>
            </div>
        </div>
        <div class="tile tile-turquoise tile-wide tile-medium">
            <div class="tile-content">
                <h2><i class="fa fa-book"></i> <br>Subjects</h2>
                <h1><?=isset($total_subjects) ? $total_subjects : 0;?></h1>
            </div>
        </div>
        <div class="tile tile-emerald tile-wide tile-medium">
            <div class="tile-content">
                <h2><i class="fa fa-smile-o"></i> <br>Parents</h2>
                <h1><?=isset($total_parents) ? $total_parents: 0;?></h1>
            </div>
        </div>
        <div class="tile tile-asbestos tile-wide tile-medium">
            <div class="tile-content">
                <h2><i class="fa fa-envelope"></i> <br>Messages</h2>
                <h1><?=isset($total_wards) ? $total_msgs : 0;?></h1>
            </div>
        </div>
        <div class="tile tile-teal tile-wide tile-medium">
            <div class="tile-content">
                <h2><i class="fa fa-calendar"></i> <br>Event Calendar</h2>
                <h1><?=isset($total_wards) ? $total_wards : 0;?></h1>
            </div>
        </div>
    </div>
</div>