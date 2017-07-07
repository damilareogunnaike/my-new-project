<div class="row">
    <div class="col-md-12">
        <div class="tile tile-orange tile-wide tile-medium">
            <div class="tile-content">
                <h2><i class="fa fa-envelope"></i> <br>Payment Types</h2>
                <h1><?=isset($total_payment_types) ? $total_payment_types : 0;?></h1>
            </div>
        </div>
        <div class="tile tile-alizarin tile-wide tile-medium">
            <div class="tile-content">
                <h2><i class="fa fa-envelope"></i> <br>Total Payments</h2>
                <h1><?=isset($total_payments) ? $total_payments : 0;?></h1>
            </div>
        </div>
        <div class="tile tile-teal tile-wide tile-medium">
            <div class="tile-content">
                <h2><i class="fa fa-calendar"></i> <br>Event Calendar</h2>
                <h1><?=isset($total_events) ? $total_events : 0;?></h1>
            </div>
        </div>
    </div>
</div>