<?php
namespace Pedetes;

use \PDO;

class objects_model extends \Pedetes\model {

	function __construct($ctn) {
		parent::__construct($ctn);
		$this->pebug->log( "objects_model::__construct()" );
	}

	public function load($id=null) {
		$retVal = array();
		if($id) {
			$para = array('id' => $id);
			$sql = "SELECT * FROM objects WHERE id = :id ";
			$retVal = $this->db->selectOne($sql, $para);
		}
		return $retVal;
	}

	public function getList() {
		$retVal = array();
		$objects = $this->db->select("SELECT * FROM objects ");
		$accounts = $this->db->selectChildArray("SELECT * FROM login ");
		$types = $this->db->selectChildArray("SELECT * FROM objects_type ");
		$characters = $this->db->selectChildArray("SELECT * FROM characters ");
		foreach($objects as $value) {
			$id = $value['id'];
			$character_id = $value['character_id'];
			$login_id = $characters[$character_id]['login_id'];

			$retVal[$id] = $value;
			$retVal[$id]['type'] = $types[$value['type_id']];
			$retVal[$id]['character'] = $characters[$character_id];
			$retVal[$id]['account'] = $accounts[$login_id];
		}
		//dbg($retVal);
		return $retVal;
	}

	public function add($para) {
		//echo "<pre>".print_r($para,true)."</pre>"; die();
		//$this->db->insert("characters", $para);
		//TODO, do custom here
	}

	public function save($para, $id) {
		//if(!is_numeric($id)) $this->pebug->error("characters_model::save(): Invalid ID ");
		//$this->db->update("characters", $para, "id=$id");
	}

	public function delete($id) {
		if(!is_numeric($id)) $this->pebug->error("characters_model::save(): Invalid ID ");
		//$sql = "DELETE FROM characters WHERE id = :id";
		//$para = array('id'=>$id);
		//$this->db->select($sql, $para);
	}


}