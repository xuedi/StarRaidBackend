<?php
namespace Pedetes;

class layout_model extends \Pedetes\model {

	function __construct($ctn) {
		parent::__construct($ctn);
	}

	public function getBaseData() {
		$retVal = array();
		$retVal['tplFile'] = $this->mem->get('url')[0];
		return $retVal;
	}

}