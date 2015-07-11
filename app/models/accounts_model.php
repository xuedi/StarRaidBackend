<?php
namespace Pedetes;

use \PDO;

class accounts_model extends \Pedetes\model {

	function __construct($ctn) {
		parent::__construct($ctn);
		$this->pebug->log( "accounts_model::__construct()" );
	}

	public function load($id=null) {
		$retVal = array();
		if($id) {
			$para = array('id' => $id);
			$sql = "SELECT L.*, COUNT(C.id) AS char_sum 
					FROM login L 
					LEFT JOIN characters C ON L.id = C.login_id 
					WHERE L.id = :id 
					GROUP BY L.id ";
			$retVal = $this->db->selectOne($sql, $para);
		} else {
			$sql = "SELECT L.*, COUNT(C.id) AS char_sum 
					FROM login L 
					LEFT JOIN characters C ON L.id = C.login_id 
					GROUP BY L.id ";
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

	public function add($para) {
		$this->db->insert("login", $para);
	}

	public function save($para, $id) {
		if(!is_numeric($id)) $this->pebug->error("accounts_model::save(): Invalid ID ");
		$this->db->update("login", $para, "id=$id");
	}

	public function delete($id) {
		if(!is_numeric($id)) $this->pebug->error("accounts_model::save(): Invalid ID ");
		$sql = "DELETE FROM login WHERE id = :id";
		$para = array('id'=>$id);
		$this->db->select($sql, $para);
	}


}