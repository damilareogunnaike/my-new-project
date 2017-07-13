<!-- Import Scripts -->
<script src="<?=base_url('assets/js/jquery-1.11.3.min.js')?>"></script>
<script src="<?=base_url('assets/js/jquery.dataTables.js')?>"></script>
<script src="<?=base_url('assets/node_modules/sweetalert/dist/sweetalert.min.js')?>"></script>
<script src="<?=base_url('assets/bootstrap/js/bootstrap.js')?>"></script>
<script src="<?=base_url('assets/plugins/modernizr.js')?>"></script>

<!-- Date picker Javascript and CSS FIles -->
<script src="<?=base_url('assets/datepicker/jquery.ui.core.js')?>"></script>
<script src="<?=base_url('assets/datepicker/jquery.ui.datepicker.js')?>"></script>

<?php if(isset($uses_angular)) { include_once("angular_files.php"); } ?>
<script>
$(document).ready(function() {
    $( "#date_of_birth" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            yearRange: "-50:-10"
    });
        });    

$(".datatable").dataTable();
</script>
<script src="<?=base_url('assets/js/script.js')?>"></script>
</body>
</html>