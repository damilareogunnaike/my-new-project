<?php


function msg($type,$message)
{
    $output = '<div class="alert alert-'. $type . ' alert-dismissable mw800 
    center-block">' . $message . ' <span class="text-right pull-right"><button type="button" class="close pull-right" data-dismiss="alert" 
    aria-hidden="true" color="blue"><i class="fa fa-times"></i></button> </span></div>';
    return $output;
}


function error_msg($message)
{
    return msg('danger',$message);
}

function info_msg($message)
{
    return msg('info','<i class="fa fa-info-circle"></i> '. $message);
}

function success_msg($message)
{
    return msg('success',"<i class='fa fa-check-circle'></i> " . $message);
}

function warning_msg($message){
    return msg('warning',$message);
}


function title_text($url)
{
	$url = str_replace("_"," ",$url);
    $url = str_replace("-"," ",$url);
	return ucwords($url);
}


function title_url($url)
{
    return str_replace("-"," ",$url);
}


function download_icon($tip = 'Download')
{
    return button('download',$tip);
}


function open_icon($tip)
{
    return button('open',$tip);
}


function play_icon($tip)
{
    return button('play',$tip);
}

function play_circle_icon($tip)
{
    return button('play-circle',$tip);
}

function button($what,$tip)
{
    return "<span data-original-title='{$tip}' data-placement='top' style='z-index:1000;' class='glyphicon glyphicon-{$what}'></span>";
}

function the_date()
{
    ini_set('date.timezone','Africa/Lagos');
    $date = date("Y-m-d H:i:s",time());
    return $date;
}

 function pad_number($number,$length = 5)
{
    $num_len = strlen($number);
    $val = str_repeat("0", $length - $num_len) . $number ;
    return $val;
}

/*
	Generates dropdown option with elements in array supplied as first argument.
	First argument is a one dimensional array which is transformed to a two-dimensional
	array and passed to print_dropdown() function
*/
function print_arr_dropdown($values,$selected_val){
	$drop_data = array();
	foreach($values as $value){
		$drop_data[] = array("name"=>$value);
	}
	return print_dropdown($drop_data,"name","name",$selected_val);
}

function print_dropdown($array,$key,$val,$selected = NULL)
{
    $option = "";
    foreach($array as $k=>$v)
    {
        $option .= "<option value='{$v[$key]}'";
        $option .= ($selected != NULL && $selected == $v[$key]) ? " selected " : '';
        $option .= ">{$v[$val]}</option>";
    }
    return $option;
}


function text($which,$text)
{
    $html = "<p class='text text-{$which}'>{$text}</p>";
    return $html;
}

function success_text($msg)
{
    return text("success",$msg);
}

function info_text($msg)
{
    return text("info",$msg);
}

function warning_text($msg)
{
    return text("warning",$msg);
}

function danger_text($msg)
{
    return text("danger",$msg);
}

function orange_text($msg) {
    return text("orange",$msg);
}

function default_text($msg)
{
    return text("default",$msg);
}

function get_grade($score)
{
    if($score <= 100 && $score >= 70) { return success_text("A"); }
    else if($score < 70 && $score >= 60) { return info_text("B"); }
    else if($score < 60 && $score >= 50) { return default_text ("C");}
    else if($score < 50 && $score >= 40) { return warning_text ("D");}
    else if($score < 40 ) { return orange_text ("F"); }
    
}


function get_comment($score)
{
    if($score <= 100 && $score >= 70) { return success_text("Distinction"); }
    else if($score < 70 && $score >= 60) { return info_text("Excellent"); }
    else if($score < 60 && $score >= 50) { return default_text ("Good");}
    else if($score < 50 && $score >= 40) { return warning_text ("Pass");}
    else if($score < 40 ) { return orange_text ("Fair"); }
}


function print_position($score)
{
    $string_val = "{$score}";
    if(strlen($string_val) < 1) return "n-th";
    $last_char = substr($string_val,  strlen($string_val) - 1);
    if($score == 11 || $score == 12 || $score == 13) { return "{$score}th"; }
    else if($last_char == "1") { return "{$string_val}st"; }
    else if($last_char == "2"){ return "{$string_val}nd"; }
    else if($last_char == "3") { return "{$string_val}rd"; }
    else if((int)$last_char == 0 || ((int)$last_char >= 4  && (int)$last_char <= 9))
    {
        return "{$score}th";
    }
}


/**
*	Returns value of an array and is used in situations where the key might not exist
* 	in the array and we don't want to check using isset cos that's a long process
* 	
*/
function arr_val($arr,$key){
	return isset($arr[$key]) ? $arr[$key] : "";
}


function is_active_tab($active_tab,$tab_name){
	return ($active_tab == $tab_name) ? "active" : "";
}



?>