<?php
/* Event Test cases generated on: 2011-03-28 13:55:21 : 1301338521*/
App::import('Model', 'Event');

class EventTestCase extends CakeTestCase {
	var $fixtures = array('app.event', 'app.tag', 'app.tags_event', 'app.user', 'app.users_event');

	function startTest() {
		$this->Event =& ClassRegistry::init('Event');
	}

	function endTest() {
		unset($this->Event);
		ClassRegistry::flush();
	}

}
?>