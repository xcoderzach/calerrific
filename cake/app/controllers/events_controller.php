<?php
class EventsController extends AppController {

  var $helpers = array("Html", "Form");
  var $name = 'Events';
	
  function index() {
	$this->set("events", $this->Event->find("all"));
	$this->view = "Json";
	$this->set("json", "events");
  }

}
?>