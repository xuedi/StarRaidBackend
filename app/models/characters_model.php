<?php
namespace Pedetes;

use \PDO;

class characters_model extends \Pedetes\model {

	function __construct($ctn) {
		parent::__construct($ctn);
		$this->pebug->log( "characters_model::__construct()" );
	}

	public function load($id=null) {
		$retVal = array();
		if($id) {
			$para = array('id' => $id);
			$sql = "SELECT c.*, COUNT(o.id) AS obj_sum 
					FROM characters c 
					LEFT JOIN objects o ON o.character_id = c.id 
					WHERE c.id = :id 
					GROUP BY c.id ";
			$retVal = $this->db->selectOne($sql, $para);
		} else {
			$sql = "SELECT c.*, COUNT(o.id) AS obj_sum 
					FROM characters c 
					LEFT JOIN objects o ON o.character_id = c.id 
					GROUP BY c.id ";
			$main = $this->db->select($sql);
			foreach($main as $value) {
				$retVal[] = array(
					'id' => $value['id'],
					'login_id' => $value['login_id'],
					'name' => $value['name'],
					'prestige' => $value['prestige'],
					'obj_sum' => $value['obj_sum']
					);
			}
		}
		return $retVal;
	}

	public function selectList() {
		return $this->db->selectList("SELECT id, name FROM characters ", array());
	}

	public function add($para) {
		//echo "<pre>".print_r($para,true)."</pre>"; die();
		$this->db->insert("characters", $para);
		//TODO, do custom here
	}

	public function save($para, $id) {
		if(!is_numeric($id)) $this->pebug->error("characters_model::save(): Invalid ID ");
		$this->db->update("characters", $para, "id=$id");
	}

	public function delete($id) {
		if(!is_numeric($id)) $this->pebug->error("characters_model::save(): Invalid ID ");
		$sql = "DELETE FROM characters WHERE id = :id";
		$para = array('id'=>$id);
		$this->db->select($sql, $para);
	}


}