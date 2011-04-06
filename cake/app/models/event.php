<?php
class Event extends AppModel {
	var $name = 'Event';
	var $useDbConfig = 'groupWdb';
  var $belongsTo = array('Owner' => array(
    'className' => 'User',
    'foreignKey' => 'user_id'
  ));
	var $hasAndBelongsToMany = array('Tag', 'User');
}
?>
