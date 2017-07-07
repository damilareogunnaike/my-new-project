<?php


include_once("App_Controller.php");

/**
 * 
 */

class Events extends App_Controller {
    
    
    public function __construct() {
        parent::__construct();
        
        $this->folder = "events/event";
        $this->page_title = get_class();
    }
}