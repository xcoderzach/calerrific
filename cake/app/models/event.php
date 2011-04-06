<?php
class Event extends AppModel {
	var $name = 'Event';
var $validate = array(
	'name' => array(
		'rule' => array('maxLength', 64),
		'required' => true		
		),
	'description' => array(
		'rule' => array('maxLength', 10000),
		'required' => true		
		),
	'location' => array(
		'rule' => array('maxLength', 64),
		'required' => true		
		),
	'position' => array(
		'rule' => array('maxLength', 64),
		'required' => true		
		)
	);
	var $useDbConfig = 'groupWdb';
	var $hasAndBelongsToMany = array('Tag', 'User');
}
?>
