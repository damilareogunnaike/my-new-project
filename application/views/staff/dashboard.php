<div class="row">
    <div class="col-md-12">
        <div class="tile tile-blue tile-medium tile-wide">
            <div class="tile-content">
                <h2><i class="fa fa-building"></i> <br>Classes</h2>
                <h1><?=isset($total_classes) ? $total_classes : 0;?></h1>
            </div>
        </div>
        <div class="tile tile-orange tile-medium tile-wide">
            <div class="tile-content">
                <h2><i class="fa fa-book"></i> <br>Subjects</h2>
                <h1><?=isset($total_subjects) ? $total_subjects : 0;?></h1>
            </div>
        </div>
        <div class="tile tile-purple tile-medium tile-wide">
            <div class="tile-content">
                <h2><i class="fa fa-envelope"></i> <br>Messages</h2>
                <h1><?=isset($total_msgs) ? $total_msgs : 0;?></h1>
            </div>
        </div>
        
        <div class="tile tile-lime tile-medium tile-wide">
            <div class="tile-content">
                <h2><i class="fa fa-calendar"></i> <br>Calendar</h2>
                <h1><?=isset($total_events) ? $total_events : 0;?></h1>
            </div>
        </div>
    </div>
</div>