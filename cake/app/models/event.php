<?php
class Event extends AppModel {
	var $name = 'Event';
	var $useDbConfig = 'groupWdb';
	var $hasAndBelongsToMany = array('Tag', 'User');
}
?>
