<?php
/* EventTag Fixture generated on: 2011-03-28 13:47:50 : 1301338070 */
class EventTagFixture extends CakeTestFixture {
	var $name = 'EventTag';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'tag_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'event_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'tag_id' => 1,
			'event_id' => 1
		),
	);
}
?>