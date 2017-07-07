<?php

function get_arr_value($arr, $key){
	$val = isset($arr[$key]) ? $arr[$key] : "";
	return $val;
}


function sort_multi_array(&$array, $entity, $order = "asc") {

	$str_func_name = "sort_" . $entity;
	usort($array, $str_func_name);	
	return ($order == "asc") ? $array : array_reverse($array);
	return $array;
}


function sort_subjects($a, $b){
	if($a['subject_name'] == $b['subject_name']) return 0;
	return ($a['subject_name'] < $b['subject_name']) ? -1 : 1;
}


function sort_scores($a, $b){
	if($a['total_score'] == $b['total_score']) return 0;
	return ($a['total_score'] < $b['total_score']) ? -1 : 1;
}

function sort_avg_score($a, $b){
	if($a['avg_score'] == $b['avg_score']) return 0;
	return ($a['avg_score'] < $b['avg_score']) ? -1 : 1;
}


/*
Merges an array while removing duplicate entries identified by a column in the array.
*/
function merge_array_remove_duplicate($arr1, $arr2, $duplicate_key){
	if(is_array($arr1)){
		$existing_keys = array_column($arr1, $duplicate_key);

		if(is_array($arr2)) {
			foreach($arr2 as $val){
				if( ! in_array($val[$duplicate_key], $existing_keys) ){

					$arr1[] = $val;
					$existing_keys[] = $val[$duplicate_key];
				}
			}
		}
		return $arr1;
	}
	return array();
}



/**
Returns an array of a property in an array of arrays..
@param $arr - Array of arrays
@param $value_key - Property key to return..
*/
function get_array_values($arr, $value_key){
	$return_data = array();
	if(is_array($arr) && sizeof($value_key) > 0){
		foreach($arr as $value){
			if(isset($value[$value_key])) {
				$return_data[] = $value[$value_key];
			}
		}
	}
	return $return_data;
}


/* RESTFUL RESPONSE HELPERS */

function rest_success($data = "") {
	return array("success"=>true, "data"=>$data);
}


function rest_error($msg) {
	return array("success"=>false, "msg"=>$msg);
}

?>