<?php

class Year {
	public $id;
	public $session;
	
	public static function find_all_years() {
	global $db;
	$query = "SELECT * FROM year ORDER BY id ASC ";
	$result = self::find_by_sql($query);
	return $result;
	}


	public static function get_session_name_by_id($sid=1) {
	global $db;
	$sanitized_sid = $db->string_prep($sid);
	$sql = "SELECT * FROM year WHERE id={$sanitized_sid} ";
	$result = $db->perform_query($sql);
	$array = $db->fetch_array($result);
	return $array['session'];
	}


	public static function find_by_sql($sql="") {
		global $db;
		$result = $db->perform_query($sql);
		$object_array =array();
		 while ($row = $db->fetch_array($result)) {
		 	$object_array[] = self::instantiate($row);
		 }
		return $object_array;
	}

	private static function instantiate($record) {
		$object = new self;
		foreach($record as $attribute=>$value) {
			if ($object->has_attribute($attribute)) {
				$object->$attribute = $value;				
			}
		}
		return $object;
	}

 	private function has_attribute($attribute) {
 		$object_vars = get_object_vars($this);
 		return array_key_exists($attribute, $object_vars);
 	}
 	
}
?>