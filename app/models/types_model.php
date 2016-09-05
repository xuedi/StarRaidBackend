<?php
namespace Pedetes;

use \PDO;

class types_model extends \Pedetes\model {

	function __construct($ctn) {
		parent::__construct($ctn);
		$this->pebug->log( "types_model::__construct()" );
	}

	public function load($id=null) {
		$retVal = array();
		if($id) {
			$para = array('id' => $id);
			$sql = "... ";
			$retVal = $this->db->selectOne($sql, $para);
		} else {
			$sql = "...";
			$main = $this->db->select($sql);
			foreach($main as $value) {
				$retVal[] = array(
					'id' => $value['id'],
					'status' => $value['status'],
					'username' => $value['username'],
					'password' => $value['password'],
					'char_sum' => $value['char_sum']
					);
			}
		}
		return $retVal;
	}

	public function selectList() {
		return $this->db->selectList("SELECT id, name FROM objects_type ", array());
	}

	public function add($para) {
		//$this->db->insert("login", $para);
	}

	public function save($para, $id) {
		//if(!is_numeric($id)) $this->pebug->error("types_model::save(): Invalid ID ");
		//$this->db->update("login", $para, "id=$id");
	}

	public function delete($id) {
		//if(!is_numeric($id)) $this->pebug->error("types_model::save(): Invalid ID ");
		//$sql = "DELETE FROM login WHERE id = :id";
		//$para = array('id'=>$id);
		//$this->db->select($sql, $para);
	}


}