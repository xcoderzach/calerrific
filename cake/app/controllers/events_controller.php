<?php
class EventsController extends AppController {

  var $helpers = array("Html", "Form");
  var $name = 'Events';
	
  function index() {
	$this->view = "Json";

	$url = $this->params['url'];
	$raw_events = $this->_find_events_raw($url);
	$rows = $this->_extract_rows($raw_events);
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
	  $type = 'first';
	}
	if (isset($url['user'])) {
	  $conditions['user_id'] = $url['user'];
	}
	if (isset($url['location'])) {
	  $conditions['location'] = $url['location'];
	}
	
	return $this->Event->find($type, array('conditions' => $conditions,
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

}
?>