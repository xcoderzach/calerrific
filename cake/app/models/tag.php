<?php
class Tag extends AppModel {
	var $name = 'Tag';
	var $useDbConfig = 'groupWdb';
	var $hasAndBelongsToMany = 'Event';
}
?>