<?php

class Assign {
	public $id;
	public $emp_id;
	public $student_enroll;
	


	public static function find_all_mentors() {
	global $db;
	$query = "SELECT * FROM assignment ";
	$result = self::find_by_sql($query);
	return $result;
	}

	public static function find_all_students_of_mentor($m="") {
	global $db;
	$sanitized_mentor_id = $db->string_prep($m);
	$query = "SELECT * FROM assignment WHERE emp_id = '$sanitized_mentor_id'";
	$result = self::find_by_sql($query);
	return $result;
	}

	public static function find_mentor_of_stud($studenroll=0) {
		global $db;
		$sql = "SELECT emp_id FROM assignment WHERE student_enroll={$studenroll} LIMIT 1";
		$result = $db->perform_query($sql);
	    $array = $db->fetch_array($result);
	   return !empty($array) ? $array['emp_id'] : 'unknown';
	}

	public static function assign_student_to_mentor($mentor_id, $student_enroll) {
		global $db;
		if (self::auth_assign($mentor_id, $student_enroll)) {
			return false;
		}
		$sanitized_mentor_id = $db->string_prep($mentor_id);
		$sanitized_student_enroll = $db->string_prep($student_enroll);
		$query  = "INSERT INTO assignment (emp_id, student_enroll) ";
		$query .= "VALUES ('$sanitized_mentor_id', '$sanitized_student_enroll') ";
		$result = $db->perform_query($query);
		if ($db->get_affected_rows()==1) {
			return true;
		} else {
			return false;			
		}
	}


	public function update_assign_student_to_mentor($mentor_id, $student_enroll) {
		global $db;
		if (self::auth_assign($mentor_id, $student_enroll)) {
			return false;
		}
		$sanitized_mentor_id = $db->string_prep($mentor_id);
		$sanitized_student_enroll = $db->string_prep($student_enroll);
		$query  = "UPDATE assignment SET emp_id = '$sanitized_mentor_id' WHERE student_enroll = $sanitized_student_enroll LIMIT 1";
		$result = $db->perform_query($query);
		if ($db->get_affected_rows()==1) {
			return true;
		} else {
			return false;			
		}
	}

	public function deleteMentorOfStudent($student_enroll=0) {
		global $db;
		$sanitized_student_enroll = trim($db->string_prep($student_enroll));
		if (self::find_mentor_of_stud($sanitized_student_enroll)=='unknown') {
			return true;
		}
		$query  = "DELETE FROM assignment WHERE student_enroll={$sanitized_student_enroll} LIMIT 1";
		$result = $db->perform_query($query);
		if ($db->get_affected_rows()==0||$db->get_affected_rows()==1)return true;
	    return false;
	}


	public static function auth_assign($mentor_id="",$student_enroll=""){
       global $db;
       $sanitized_mentor_id = trim($db->string_prep($mentor_id));
       $sanitized_student_enroll = trim($db->string_prep($student_enroll));
       $sql="SELECT * FROM assignment WHERE emp_id='{$sanitized_mentor_id}' AND student_enroll='{$sanitized_student_enroll}' LIMIT 1";
	   $result = $db->perform_query($sql);
	   $array = $db->fetch_array($result);
	   return !empty($array) ? true : false;
	  }

	  public function manage_assign_student_to_mentor($mentor_id, $student_enroll) {
		global $db;
		if (self::auth_assign($mentor_id, $student_enroll)) {
			return true;
		}
		$sanitized_mentor_id = $db->string_prep($mentor_id);
		$sanitized_student_enroll = $db->string_prep($student_enroll);
		$query  = "INSERT INTO assignment (emp_id, student_enroll) ";
		$query .= "VALUES ('$sanitized_mentor_id', '$sanitized_student_enroll') ";
		$result = $db->perform_query($query);
		if ($db->get_affected_rows()==0||$db->get_affected_rows()==1)return true;
	    return false;
	}

	public function manage_update_assign_student_to_mentor($mentor_id, $student_enroll) {
		global $db;
		if (self::auth_assign($mentor_id, $student_enroll)) {
			return true;
		}
		$sanitized_mentor_id = $db->string_prep($mentor_id);
		$sanitized_student_enroll = $db->string_prep($student_enroll);
		$query  = "UPDATE assignment SET emp_id = '$sanitized_mentor_id' WHERE student_enroll = $sanitized_student_enroll LIMIT 1";
		$result = $db->perform_query($query);
		if ($db->get_affected_rows()==0||$db->get_affected_rows()==1)return true;
	    return false;		
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