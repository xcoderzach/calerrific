<?php
class EventsController extends AppController {

  var $helpers = array("Html", "Form");
  var $components = array('Session');
  var $name = 'Events';
	
  function index() {
	$this->view = "Json";

	$url = $this->params['url'];
	$raw_events = $this->_find_events_raw($url);
	$rows = $this->_extract_rows($raw_events);
  foreach($rows as $date => $row) {
    $rows[$date]["start_timestamp"] = DateTime::createFromFormat("Y-m-d H:i:s", $row["start_time"])->getTimestamp() * 1000;
    $rows[$date]["end_timestamp"] = DateTime::createFromFormat("Y-m-d H:i:s", $row["end_time"])->getTimestamp() * 1000;
  }
	$this->set('json', $this->_format_events($rows));
  }

  function _extract_rows($raw_events) {
	$res = array();
	foreach ($raw_events as $row) {
	  $res[] = $row['Event'];
	}
	return $res;
  }

  function _find_events_raw($url) {
	$conditions = array();
	$type = 'all';
	if (isset($url['start'])) {
	  $conditions['start_time >='] = $url['start'].' 00:00:00';
	}
	if (isset($url['end'])) {
	  $conditions['end_time <='] = $url['end'].' 00:00:00';
	}
	if (isset($url['id'])) {
	  $conditions['id'] = $url['id'];
	}
	if (isset($url['user'])) {
	  $conditions['user_id'] = $url['user'];
	}
	if (isset($url['location'])) {
	  $conditions['location'] = $url['location'];
	}
	
	return $this->Event->find('all', array('conditions' => $conditions,
										   'recursive' => 0));
  }

  function _format_events($rows) {
	$id_map = array();
	$day_map = array();

	// First, populate id_map with id->raw_event mappings
	// Then populate day_map with date->set of ids, where the elements in the
	// set are the keys of the array with true values.
	foreach ($rows as $actual_data) {
	  $id_map[$actual_data['id']] = $actual_data;
	  
	  $time = $this->_datetime_to_date($actual_data['start_time']);
	  $map = isset($day_map[$time]) ? $day_map[$time] : array();
	  $map[$actual_data['id']] = true;
	  $day_map[$time] = $map;
	 
	  
	  $time = $this->_datetime_to_date($actual_data['end_time']);
	  $map = isset($day_map[$time]) ? $day_map[$time] : array();
	  $map[$actual_data['id']] = true;
	  $day_map[$time] = $map;
	}


	$result = array();
	foreach ($day_map as $day => $set) {
	  $event_array = array();
	  foreach ($set as $id => $foo) {
		if ($foo) {
		  $event_array[] = $id_map[$id];
		}
	  }
	  $result[$day] = $event_array;
	}

	return $result;
  }

  function _datetime_to_date($datetime) {
	return substr($datetime, 0, 10);
  }

  function tag($id) {
	$this->view = 'Json';
	$this->loadModel('Tag');
	
	$raw_rows = $this->Tag->findById($id);
	$this->set('json', $this->_format_events($raw_rows['Event']));
  }

  function user($id) {
	$this->view = 'Json';
	$this->loadModel('User');
	
	$raw_rows = $this->User->findById($id);
	$events = $raw_rows['Event'];
	for ($i = 0; $i < count($events); $i++) {
	  $events[$i]['status'] = $events[$i]['EventsUser']['status'];
	  unset($events[$i]['EventsUser']);
	}
	$this->set('json', $this->_format_events($events));
  }

  function create() {
	$this->view = 'Json';

	$fields = array('name', 'description', 'start_time', 'end_time',
					'location');
	$post = $this->params['form'];
	// Validate more
	if ($this->Session->check('User.id') &&
		$this->_validate_fields($post, $fields)) {
	  $coreData = $this->_extract_fields($post, $fields);
	  $coreData['user_id'] = $this->Session->read('User.id');
	  $res = $this->Event->save(array('Event' => $coreData));
	  $this->set('json', ($res ? true : false));
	} else {
	  $this->set('json', false);
	}
  }

  function update() {
	$this->view = 'Json';

	$fields = array('name', 'description', 'start_time', 'end_time',
					'location');
	$post = $this->params['form'];
	// Validate more
	if ($this->Session->check('User.id') &&
		$this->_validate_fields($post, $fields)) {
	  $id = $post['id'];
	  $coreData = $this->_extract_fields($post, $fields);
	  $old = $this->Event->findById($id);
	  $coreData['user_id'] = $old['Event']['user_id'];
	  $coreData['id'] = $id;
	  $old['Event'] = $coreData;
	  if ($old['Event']['user_id'] == $this->Session->read('User.id')) {
		$this->Event->set($old);
		$res = $this->Event->save();
		$this->set('json', ($res ? true : false));
		return;
	  }
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

  function _extract_fields($obj, $fields) {
	$res = array();
	foreach($fields as $field) {
	  $res[$field] = $obj[$field];
	}
	return $res;
  }

  function addTag() {
	$this->view = 'Json';

	if ($this->Session->check('User.id')) {
	  $id = $this->params['form']['id'];
	  $tag_id = $this->params['form']['tag_id'];

	  $old = $this->Event->findById($id);
	  $newTags = $this->_extract_ids($old['Tag']);
	  $newTags[] = $tag_id;
	  $newTags = $this->_merge($newTags);
	  $old['Tag'] = array('Tag' => $newTags);
	  if ($old['Event']['user_id'] == $this->Session->read('User.id')) {
		$res = $this->Event->save($old);
		$this->set('json', $res ? true : false);
	  } else {
		$this->set('json', false);
	  }
	} else {
	  $this->set('json', false);
	}
  }

  function clearTags($id) {
	$this->view = 'Json';

	if ($this->Session->check('User.id')) {

	  $old = $this->Event->findById($id);
	  $old['Tag'] = array('Tag' => array());
	  if ($old['Event']['user_id'] == $this->Session->read('User.id')) {
		$res = $this->Event->save($old);
		$this->set('json', $res ? true : false);
	  } else {
		$this->set('json', false);
	  }
	} else {
	  $this->set('json', false);
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

  function attend($id) {
	$this->view = 'Json';

	$this->Session->write('User.id', 1);
	if ($this->Session->check('User.id')) {
	  $old = $this->Event->findById($id);
	  $userIds = $this->_extract_ids($old['User']);
	  $userIds[] = $this->Session->read('User.id');
	  $userIds = $this->_merge($userIds);
	  $new = array('Event' => array('id' => $id));
	  $new['User'] = array('User' => $userIds);
	  $res = $this->Event->save($new);
	  $this->set('json', $res ? true : false);
	} else {
	  $this->set('json', false);
	}
	$this->Session->destroy();
  }

  function delete($id) {
	$this->view = 'Json';

	$old = $this->Event->findById($id);
	if ($this->Session->check('User.id')) {
	  if ($old['Event']['user_id'] == $this->Session->read('User.id')) {
		$this->Event->delete($id);
		$this->set('json', true);
	  } else {
		$this->set('json', true);
	  }
	} else {
		$this->set('json', true);
	}
  }

}
?>
