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

	  $this->set('json', $this->_find($name));
	}
	
	function _find($name, $create = false) {
	  // Standard lookup
	  $res = $this->Tag->findByName($name);
	  if ($res) {
		return $res['Tag']['id'];
	  }

	  // Create
	  if ($create && $this->Session->check('User.id')) {
		$data = array('Tag' => array('name' => $name));
		$res = $this->Tag->save($data);
		return $res ? $res['Tag']['id'] : false;
	  } else {
		return false;
	  }
	}

  function _extract_ids($old) {
	$res = array();
	foreach ($old as $tag) {
	  $res[] = $tag['id'];
	}
	return $res;
  }

  function _merge($arr) {
	$res = array();
	$set = array();
	foreach ($arr as $v) {
	  $set[$v] = true;
	}
	foreach ($set as $key => $val) {
	  $res[] = $key;
	}
	return $res;
  }

	function add() {
	  $this->view = 'Json';
	  $this->loadModel('Event');

	  $tagsRaw = $this->params['url']['tags'];
	  $tags = explode(',', $tagsRaw);
	  $id = $this->params['url']['id'];
	  $old = $this->Event->findById($id);
	  $newTags = $this->_extract_ids($old['Tag']);
	  for ($i = 0; $i < count($tags); $i++) {
		$newTags[] = $this->_find(trim($tags[$i]), true);
	  }
	  $newTags = $this->_merge($newTags);
	  $old['Tag'] = array('Tag' => $newTags);
	  
	  if ($this->Session->check('User.id') &&
		  $old['Event']['user_id'] == $this->Session->read('User.id')) {
		$res = $this->Event->save($old);
		$this->_makeSearchable();
		$this->set('json', $res ? true : false);
	  } else {
		$this->set('json', false);
	  }
	}

  function clear($id) {
	$this->view = 'Json';
	$this->loadModel('Event');

	if ($this->Session->check('User.id')) {

	  $old = $this->Event->findById($id);
	  $old['Tag'] = array('Tag' => array());
	  if ($old['Event']['user_id'] == $this->Session->read('User.id')) {
		$res = $this->Event->save($old);
		$this->_makeSearchable();
		$this->set('json', $res ? true : false);
	  } else {
		$this->set('json', false);
	  }
	} else {
	  $this->set('json', false);
	}
  }

  function _makeSearchable() {
    $raw = $this->Event->find('all', array('recursive' => 1));
    foreach($raw as $item) {
      $id = $item["Event"]["id"];
      $index = implode(" ", $item["Event"]).
               implode(" ", $item["Tag"]).
               implode(" ", $item["Owner"]).
               implode(" ", $item["User"]);
      $this->Event->id = $id;
      $this->Event->save(array('search_index' => $index));
    }
  }

  function all() {
	$this->view = 'Json';
	
	$all = $this->Tag->find('all', array('recursive' => 0));
	$res = array();
	foreach ($all as $tag) {
	  $res[] = $tag['Tag']['name'];
	}
	$this->set('json', $res);
  }

}
?>