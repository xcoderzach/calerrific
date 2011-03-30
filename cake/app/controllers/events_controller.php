<?php
class EventsController extends AppController {

  var $helpers = array("Html", "Form");
  var $name = 'Events';
	
  function index() {
	$this->view = "Json";
	$this->set("json", $this->Event->find("all"));
  }

}
?>