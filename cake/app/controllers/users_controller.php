<?php
class UsersController extends AppController {

  var $name = 'Users';

	function index() {
	  $this->view = "Json";

	  $conditions = array('id' => $this->params['url']['id']);
	  $params = array('conditions' => $conditions);
	  $raw = $this->User->find('first', $params);
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
	  $post = $this->params['form'];
	  // Validate more
	  if ($this->Session->check('User.id') &&
		  $this->_validate_fields($post, $fields)) {
		$id = $post['id'];
		$coreData = $this->_extract_fields($post, $fields);
		$old = $this->User->findById($id);
		$coreData['id'] = $id;
		$old['User'] = $coreData;
		if ($id == $this->Session->read('User.id')) {
		  $this->User->set($old);
		  $res = $this->User->save();
		  $this->set('json', ($res ? true : false));
		} else {
		  $this->set('json', false);
		}
	  } else {
		$this->set('json', false);
	  }
	}

	function _validate_fields($obj, $fields) {
	  foreach($fields as $field) {
		if (!isset($obj[$field]) || $obj[$field] === '') {
		  return false;
		}
	  }
	  return true;
	}
  }
?>