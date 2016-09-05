<?php

class index extends Pedetes\controller {

	function __construct($ctn) {
		parent::__construct($ctn);
	}

	function indexAction() {
		$file = $this->ctn['config']['path']['serverLog'];
		if($file && file_exists($file)) {
			$log = file_get_contents($file);
			$this->view->assign("log", $log);
			$this->view->render('index.tpl', true);
		} else {
			$this->error( "Log not found: ".$file );
		}
	}

	function infoAction() {
		phpinfo();
	}

	function clearAction() {
		session_unset();
		die('CLEARED');
	}

}
