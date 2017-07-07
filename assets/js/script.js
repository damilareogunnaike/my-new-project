$(document).ready(function()
{
    $(document).on("click",".del_btn",function(event)
    {
       if(confirm("You are about to delete a record. Action not reversible!!!.Click OK to Proceed"))
       {
            return true;
       }
       else 
       {
           event.preventDefault();
       }
    });


    //Add fade attribute to tab panes
    $(".tab-pane.active").addClass("in");
    $(".tab-pane").addClass("fade");
	

    //Bootstrap Dropdown handlers
    // ADD SLIDEDOWN ANIMATION TO DROPDOWN //
    $('.dropdown').on('show.bs.dropdown', function(e){
      $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
    });

	
    // ADD SLIDEUP ANIMATION TO DROPDOWN //
    $('.dropdown').on('hide.bs.dropdown', function(e){
      $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
    });

    
    
    //Activate Popovers
    $("[data-toggle='popover']").popover();
    
    //Activate Tooltips
    $('[data-toggle="tooltip"]').tooltip();
    

    //Make admin biodata form not to be editable by default
    $("#biodata form input,#biodata form select,#biodata form textarea").attr("disabled","disabled");

	
    //request to edit biodata form
    $("#biodata #edit_form_btn").click(function()
    {
        $("#biodata form input,#biodata form select,#biodata form textarea").removeAttr("disabled");
    });

   
    //Print Report Button Clicked
    $(".print_report_btn").bind('click',function()
    {
        print_url = $(this).attr("data-print-url");
        student_id = $(this).attr("data-student-id");
        session_id = $("#session_id").val();
        term_id = $("#term_id").val();
        url = print_url + "/" + student_id + "/" + session_id + "/" + term_id;
        window.open(url);
    });

    
    //Print Receipt Button
    $("#print_receipt_btn").click(function(){
       student_id = $(this).attr("data-student-id");
       url = $(this).attr("data-url");
       print_window = window.open(url,"","width=800,height=700");
    });

	
     //Print Receipt Button
    $("#print_cash_receipt_btn").click(function(){
       student_id = $(this).attr("data-insert-id");
       url = $(this).attr("data-url");
       print_window = window.open(url,"","width=800,height=700");
    });

	
    $("#print-btn").click(function()
    {
        $(".del_btn").remove();
        url = $(this).attr("data-url");
        var print_data = $("#print-window").html();
        print_window = window.open(url,"","width=800,height=700");
        print_window.print_data = print_data;
    });
	
	
	$(".back-btn").click(function(e){
		e.preventDefault();
		window.history.back();
	})
        
        
    
    //To check a list of checkboxes by toggling 
    $("#check-all-btn").click(function(){
        currVal = $(this).prop("checked");
        $elems = $($(this).attr("data-toggle"));
        $elems.each(function(i){
            $($elems[i]).attr("checked",currVal);
        });
    });
    
    
    $(".validate-checked-boxes").click(function(e){
        //e.preventDefault();
        checkElems = $(this).attr("data-check");
        checkedItems = $(checkElems).pro
    });
    
    
    
    /* New Updates */
    //Dropdown Submenu
    $("[data-toggle='submenu']").click(function(e){
        html = new String($(this).html());
        str1 = "fa-caret-right";
        str2 = "fa-caret-down";
        if($(this).parent("li").hasClass("active")) {
            $(this).parent("li").removeClass("active");
            $($(this).next(".submenu")[0]).removeClass("active");
            html = html.replace(str2,str1);
        }
        else {
            $(this).parent("li").addClass("active");
            $($(this).next(".submenu")[0]).addClass("active");
            html = html.replace(str1,str2);
        }
        $(this).html(html);
        
       // $(".submenu").not($(this).next(".submenu")[0]).slideUp();
        $($(this).next(".submenu")[0]).slideToggle();
        e.preventDefault();
    });
    
    $subAnchors = $(".submenu li a");
    $subAnchors.each(function(index,elem){
        $(elem).html("<i class='fa fa-caret-right'></i> " + $(elem).html());
    });

});