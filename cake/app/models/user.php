<?php
class User extends AppModel {
	var $name = 'User';
	var $useDbConfig = 'groupWdb';
	var $hasAndBelongsToMany = 'Event';
}
?>