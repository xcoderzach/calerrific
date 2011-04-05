<?php
class TagsController extends AppController {

	var $name = 'Tags';
	var $components = array('Session');

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

	function find() {
	  $this->view = 'Json';
	  $name = $this->params['url']['name'];

	  // Standard lookup
	  $res = $this->Tag->findByName($name);
	  if ($res) {
		$this->set('json', $res['Tag']['id']);
		return;
	  }

	  // Create
	  if ($this->Session->check('User.id')) {
		$data = array('Tag' => array('name' => $name));
		$res = $this->Tag->save($data);
		$this->set('json', $res ? $res['Tag']['id'] : false);
	  } else {
		$this->set('json', false);
	  }
	}

}
?>