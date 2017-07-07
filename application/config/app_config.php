<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* App Settings */

$config['app_name'] = "Evangel Model";

$config['pdf_upload_base'] = "uploads/pdf_report/";

$config['pdf_report_path'] = str_replace("\\", "/", FCPATH . $config['pdf_upload_base']);

$config['use_uppercase'] = false;

$config['jwt_secret'] = "whatisthemeaningofallthis??";

$config['jwt_hashing_algorithm'] = "HS256";
