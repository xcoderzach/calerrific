<?php 
  /** 
   * Class of view for JSON 
   * 
   * @author Juan Basso 
   * @url http://blog.cakephp-brasil.org/2008/09/11/trabalhando-com-json-no-cakephp-12/ 
   * @licence MIT 
   */ 

class JsonView extends View { 

  function render($action = null, $layout = null, $file = null) { 
	if (!isset($this->viewVars['json'])) { 
	  return parent::render($action, $layout, $file); 
	} 

	//header('Content-type: application/json'); 
	$out = json_encode($this->viewVars['json']);
	Configure::write('debug', 0); // Omit time in end of view 
	return $out; 
  } 
}
?> 
