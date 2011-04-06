<?php
class User extends AppModel {
	var $name = 'User';
var $validate = array(
	'name' => array(
		'rule' => array('maxLength', 64),
		'required' => true		
		),
	'pw' => array(
		'rule' => array('maxLength', 64),
		'required' => true		
		),
	'username' => array(
		'rule' => array('maxLength', 64),
		'required' => true		
		),
	'position' => array(
		'rule' => array('maxLength', 64),
		'required' => true		
		),
	'email' => array(
		'rule' => array('maxLength', 64),
		'required' => true		
		),
	'title' => array(
		'rule' => array('maxLength', 64),
		'required' => true		
		),
	'department' => array(
		'rule' => array('maxLength', 64),
		'required' => true		
		)
	);
	var $useDbConfig = 'groupWdb';
	var $hasAndBelongsToMany = 'Event';
}
?>
