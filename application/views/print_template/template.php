<div class="container">
     <div class="panel panel-default">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
            <div class="row ">
                <div class="col-md-12 text-center">
                    <?=isset($school_logo) ? img(array('src'=>$school_logo,'class'=>'img-passport img-thumbnail')) : '';?>
                    <h2><?=isset($school_name) ? $school_name : '';?></h2>
                    <p class="text-italics"><i><?=isset($school_motto) ? $school_motto : '';?></i></p>
                    <p class="text-italics"><i><?=isset($school_address) ? $school_address : '';?></i></p>
                    <!-- 
                    <p>
                        <?=isset($school_email) ? $school_email : '';?> | 
                        <?=isset($school_phone_no) ? $school_phone_no : '';?><br>
                        <?=isset($school_website) ? $school_website : '';?> | <?=isset($school_address) ? $school_address : '';?>
                    </p> -->
                </div>
            </div>
            <div id="print-data">
            </div>
        </div>
         <div class="panel-footer text-center">
             <button onclick="window.print()" class="btn btn-info"><i class="fa fa-print"> </i> Print</button>
         </div>
       </div>
</div>
<script>
    document.getElementById("print-data").innerHTML = print_data;
    </script>