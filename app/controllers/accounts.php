<?php

class accounts extends Pedetes\controller {

	function __construct($ctn) {
		parent::__construct($ctn);

		// common stuff
		$accounts = $this->loadModel('accounts');
		$this->view->assign("accounts", $accounts->load());
		$id = $this->request->get('id', 'NUMBER', null, true);
		if($id) {
			$this->view->assign("account", $accounts->load($id));
		}
	}

	function indexAction() {
		$this->view->assign("action", "index");
		$this->view->render('accounts.tpl', true);
	}

	function editAction() {
		$this->view->assign("action", "edit");
		$this->view->render('accounts.tpl', true);
	}

	function deleteAction() {
		$this->view->assign("action", "delete");
		$this->view->render('accounts.tpl', true);
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
		    	$accounts = $this->loadModel('accounts');
		    	$accounts->add($para);
		        break;
		    case 'save':
		    	$para = array();
		    	$para['status'] = $this->request->get('status', 'TEXT');
		    	$para['username'] = $this->request->get('username', 'TEXT');
		    	$para['password'] = $this->request->get('password', 'TEXT');
		    	$accounts = $this->loadModel('accounts');
		    	$accounts->save($para, $this->request->get('id', 'NUMBER') );
		        break;
		    case 'delete':
		        $id = $this->request->get('id', 'NUMBER');
		    	$accounts = $this->loadModel('accounts');
		    	$accounts->delete($id);
		        break;
		}
		$this->redirect('/accounts');
	}



	function infoAction() {
		phpinfo();
	}

}
