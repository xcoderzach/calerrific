<?php
class TagsController extends AppController {

	var $name = 'Tags';

	function index() {
	  $this->view = 'Json';
	 
	  $id = $this->params['url']['id'];
	  $params = array('conditions' => array('id' => $id));
	  $raw = $this->Tag->find('first', $params);
	  if ($raw) {
		$this->set('json', $raw['Tag']['name']);
	  } else {
		$this->set('json', false);
	  }
	}

}
?>