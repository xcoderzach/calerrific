<?php
/* Tag Test cases generated on: 2011-03-28 13:56:38 : 1301338598*/
App::import('Model', 'Tag');

class TagTestCase extends CakeTestCase {
	var $fixtures = array('app.tag', 'app.event', 'app.tags_event', 'app.user', 'app.users_event');

	function startTest() {
		$this->Tag =& ClassRegistry::init('Tag');
	}

	function endTest() {
		unset($this->Tag);
		ClassRegistry::flush();
	}

}
?>