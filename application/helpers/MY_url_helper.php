<?php


function base_file($file_path){
	return str_replace("\\", "/", FCPATH . $file_path);
}

?>