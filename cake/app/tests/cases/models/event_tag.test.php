<?php
/* EventTag Test cases generated on: 2011-03-28 13:47:50 : 1301338070*/
App::import('Model', 'EventTag');

class EventTagTestCase extends CakeTestCase {
	var $fixtures = array('app.event_tag');

	function startTest() {
		$this->EventTag =& ClassRegistry::init('EventTag');
	}

	function endTest() {
		unset($this->EventTag);
		ClassRegistry::flush();
	}

}
?>