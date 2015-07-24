<?php

class characters extends Pedetes\controller {

	function __construct($ctn) {
		parent::__construct($ctn);

		//TODO: condence here
		$characters = $this->loadModel('characters');
		$this->view->assign("characters", $characters->load());
		$this->view->assign("loginList", $this->_getLoginList());
		$id = $this->request->get('id', 'NUMBER');
		if($id) { // if needed
			$this->view->assign("character", $characters->load($id));
		}
	}

	function indexAction() {
		$this->view->assign("action", "index");
		$this->view->render('characters.tpl', true);
	}

	function editAction() {
		$this->view->assign("action", "edit");
		$this->view->render('characters.tpl', true, false);
	}

	function deleteAction() {
		$this->view->assign("action", "delete");
		$this->view->render('characters.tpl', true, false);
	}

	function doAction() {
		//TODO: load models only once
		$options = array('add','save','delete');
		$action = $this->request->get('action', 'ARRAY', $options, true);
		switch($action) {
		    case 'add':
		    	$para = array();
		    	$para['login_id'] = $this->request->get('login_id', 'NUMBER');
		    	$para['name'] = $this->request->get('name', 'TEXT');
		    	$para['prestige'] = $this->request->get('prestige', 'NUMBER');
		    	$characters = $this->loadModel('characters');
		    	$characters->add($para);
		        break;
		    case 'save':
		    	$para = array();
		    	$para['login_id'] = $this->request->get('login_id', 'NUMBER');
		    	$para['name'] = $this->request->get('name', 'TEXT');
		    	$para['prestige'] = $this->request->get('prestige', 'NUMBER');
		    	$characters = $this->loadModel('characters');
		    	$characters->save($para, $this->request->get('id', 'NUMBER') );
		        break;
		    case 'delete':
		        $id = $this->request->get('id', 'NUMBER');
		    	$characters = $this->loadModel('characters');
		    	$characters->delete($id);
		        break;
		}
		$this->redirect('/characters~FC');
	}

	function _getLoginList() {
		$retVal = array();
		$accounts = $this->loadModel('accounts');
		$list = $accounts->load();
		foreach($list as $key => $value) {
			$retVal[$value['id']] = $value['username'];
		}
		return $retVal;
	}

}
