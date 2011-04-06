<?php
class UsersController extends AppController {

  var $name = 'Users';
  var $components = array('Session', 'Auth');

  function beforeFilter() {
	$this->Auth->allow('*');
  }

	function index() {
	  $this->view = "Json";

	  $raw = $this->User->findById($this->params['url']['id']);
	  $raw = $raw['User'];

	  $fields = array('name', 'username', 'position', 'email', 'title',
					  'department');
	  $this->set('json', $this->_extract_fields($raw, $fields));
	}

	function _extract_fields($obj, $fields) {
	  $res = array();
	  foreach ($fields as $field) {
		if (isset($obj[$field]) && $obj[$field] != '') {
		  $res[$field] = $obj[$field];
		}
	  }
	  return $res;
	}

	function event($id) {
	  $this->view = 'Json';
	  $this->loadModel('Event');
	  
	  $raw = $this->Event->findById($id);
	  $users = $raw['User'];
	  $res = array();
	  foreach ($users as $user) {
		$res[] = array('id' => $user['id'], 'name' => $user['name']);
	  }
	  $this->set('json', $res);
	}

	function update() {
	  $this->view = 'Json';

	  $fields = array('name', 'username', 'position', 'email',
					  'title', 'deparment');
	  $post = $this->params['url'];
	  // Validate more
	  if ($this->Session->check('User.id')) {
		$id = $post['id'];
		$coreData = $this->_extract_fields($post, $fields);
		$old = $this->User->findById($id);
		$coreData['id'] = $id;
		$old['User'] = $this->_merge_maps($old['User'], $coreData);
		if ($id == $this->Session->read('User.id')) {
		  $res = $this->User->save($old);
		  $this->set('json', ($res ? true : false));
		} else {
		  $this->set('json', false);
		}
	  } else {
		$this->set('json', false);
	  }
	}

	function _merge_maps($base, $new) {
	  foreach ($new as $key => $val) {
		$base[$key] = $val;
	  }
	  return $base;
	}

	function _validate_fields($obj, $fields) {
	  foreach($fields as $field) {
		if (!isset($obj[$field]) || $obj[$field] === '') {
		  return false;
		}
	  }
	  return true;
	}

	function login() {
	  $this->view = 'Json';
	  // NEVER EVER DO THIS
	  
	  $post = $this->params['url'];
	  $user = $post['username'];

	  $pw = $this->Auth->password($post['pw']);
	  $record = $this->User->findByUsername($user);

	  if($record['User']['pw'] == $pw) {
		$this->Session->write('User.id', $record['User']['id']);
		$this->set('json', true);
	  } else {
		$this->set('json', false);
	  }
	}

	function logout() {
	  $this->view = 'Json';

	  $this->Session->destroy();
	  $this->set('json', true);
	}

	function id() {
	  $this->view = 'Json';
	  
	  if ($this->Session->check('User.id')) {
		$this->set('json', $this->Session->read('User.id'));
	  } else {
		$this->set('json', false);
	  }
	}
  }
?>
