<?php
class TagsController extends AppController {

	var $name = 'Tags';

	function index() {
	  $this->view = 'Json';
	 
	  $id = $this->params['url']['id'];
	  $params = array('conditions' => array('id' => $id), 'recursive' => 0);
	  $raw = $this->Tag->find('first', $params);
	  if ($raw) {
		$this->set('json', $raw['Tag']['name']);
	  } else {
		$this->set('json', false);
	  }
	}

	function event($id) {
	  $this->view = 'Json';
	  $this->loadModel('Event');

	  $raw_rows = $this->Event->findById($id);
	  $tags = $raw_rows['Tag'];
	  $res = array();
	  foreach ($tags as $row) {
		$res[] = $row['name'];
	  }
	  $this->set('json', $res);
	}

}
?>