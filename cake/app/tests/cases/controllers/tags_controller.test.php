<?php
/* Tags Test cases generated on: 2011-03-30 13:18:35 : 1301509115*/
App::import('Controller', 'Tags');

class TestTagsController extends TagsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class TagsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.tag', 'app.event', 'app.tags_event', 'app.user', 'app.users_event');

	function startTest() {
		$this->Tags =& new TestTagsController();
		$this->Tags->constructClasses();
	}

	function endTest() {
		unset($this->Tags);
		ClassRegistry::flush();
	}

}
?>