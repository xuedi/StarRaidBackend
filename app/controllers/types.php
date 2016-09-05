<?php

class types extends Pedetes\controller {

	function __construct($ctn) {
		parent::__construct($ctn);

		// common stuff
		$types = $this->loadModel('types');
		$this->view->assign("types", $types->getList());
		$id = $this->request->get('id', 'NUMBER', null, true);
		if($id) {
			$this->view->assign("account", $types->load($id));
		}
	}

	function indexAction() {
		$this->view->assign("action", "index");
		$this->view->render('types.tpl', true);
	}

	function editAction() {
		$this->view->assign("action", "edit");
		$this->view->render('types.tpl', true);
	}

	function deleteAction() {
		$this->view->assign("action", "delete");
		$this->view->render('types.tpl', true);
	}

	function doAction() {
		$options = array('add','save','delete');
		$action = $this->request->get('action', 'ARRAY', $options, true);
		switch($action) {
		    case 'add':
		    	$para = array();
		    	$para['status'] = $this->request->get('status', 'TEXT');
		    	$para['username'] = $this->request->get('username', 'TEXT');
		    	$para['password'] = $this->request->get('password', 'TEXT');
		    	$types = $this->loadModel('types');
		    	$types->add($para);
		        break;
		    case 'save':
		    	$para = array();
		    	$para['status'] = $this->request->get('status', 'TEXT');
		    	$para['username'] = $this->request->get('username', 'TEXT');
		    	$para['password'] = $this->request->get('password', 'TEXT');
		    	$types = $this->loadModel('types');
		    	$types->save($para, $this->request->get('id', 'NUMBER') );
		        break;
		    case 'delete':
		        $id = $this->request->get('id', 'NUMBER');
		    	$types = $this->loadModel('types');
		    	$types->delete($id);
		        break;
		}
		$this->redirect('/types');
	}



	function infoAction() {
		phpinfo();
	}

}
