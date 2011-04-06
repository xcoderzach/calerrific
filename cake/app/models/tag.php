<?php
class Tag extends AppModel {
	var $name = 'Tag';
var $validate = array(
	'name' => array(
		'rule' => array('maxLength', 64),
		'required' => true		
		)
	);
	var $useDbConfig = 'groupWdb';
	var $hasAndBelongsToMany = 'Event';
}
?>
