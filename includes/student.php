<?php

class Student {
	public $EnrollNo;
	public $AdmissionNo;
	public $Student_Name;
	public $SessionName;
	public $Course_name;
	public $Sem;
	public $Batch_name;
	public $email;


	public static function  find_all_students() {
	global $db;
	$query = "SELECT * FROM students ";
	$result = $db->perform_query($query);
	return $result;
	}

	public static function find_student_by_EnrollNo($enroll=0) {
		global $db;
		$sanitized_enroll = $db->string_prep($enroll);
		$result_array = self::find_by_sql("SELECT * FROM students WHERE EnrollNo={$sanitized_enroll} LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : array();
	}

	public static function find_student_by_AddmNo($addno="") {
		global $db;
		$sanitized_AddmNo = $db->string_prep($addno);
		$result_array = self::find_by_sql("SELECT * FROM students WHERE AdmissionNo='$sanitized_AddmNo' LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function find_students_by_Program($program=1) {
		global $db;
		$sanitized_course = $db->string_prep($program);
		$result_array = self::find_by_sql("SELECT * FROM students WHERE Course_name={$sanitized_course}");
		return !empty($result_array) ? $result_array : false;
	}

	public static function find_students_by_Program_sem($program=1,$year=0,$sem=0) {
		global $db;
		$sanitized_course = $db->string_prep($program);
		$sanitized_year = $db->string_prep($year);
		$sanitized_sem = $db->string_prep($sem);
		if ($year>=1) {
			if ($sem>=1) {
				$result_array = self::find_by_sql("SELECT * FROM students WHERE Course_name={$sanitized_course} AND SessionName={$sanitized_year} AND Sem={$sanitized_sem}");
			}else{
				$result_array = self::find_by_sql("SELECT * FROM students WHERE Course_name={$sanitized_course} AND SessionName={$sanitized_year}");
			}
		}else{
			if ($sem>=1) {
				$result_array = self::find_by_sql("SELECT * FROM students WHERE Course_name={$sanitized_course} AND Sem={$sanitized_sem}");
			}else{
				$result_array = self::find_by_sql("SELECT * FROM students WHERE Course_name={$sanitized_course}");
			}
		}
		
		return !empty($result_array) ? $result_array : array();
	}

	public static function student_exists_has_email($enroll=0) {
		global $db;
		$sanitized_enroll = $db->string_prep($enroll);
		$sql = "SELECT * FROM students WHERE EnrollNo={$sanitized_enroll} LIMIT 1";
		$result = $db->perform_query($sql);
		$result_array = $db->fetch_array($result);
		if(!empty($result_array)){
			$mail = $result_array['email'];
			if (!is_null($mail)&&$mail!=='') {
				return true;
			}
		}
		return false;
	}

	public static function student_exists_by_enroll($enroll=0) {
		global $db;
		$sanitized_enroll = $db->string_prep($enroll);
		$sql = "SELECT * FROM students WHERE EnrollNo={$sanitized_enroll} LIMIT 1";
		$result = $db->perform_query($sql);
		$result_array = $db->fetch_array($result);
		if(!empty($result_array))return true;
		return false;
	}

	public static function student_exists_by_enroll_Or_admsno($enroll=0,$addno="") {
		global $db;
		$sanitized_enroll = trim($db->string_prep($enroll));
		$sanitized_adms = trim($db->string_prep($addno));
		$sql = "SELECT * FROM students WHERE EnrollNo={$sanitized_enroll} OR  AdmissionNo='{$sanitized_adms}' LIMIT 1";
		$result = $db->perform_query($sql);
		$result_array = $db->fetch_array($result);
		if(!empty($result_array))return true;
		return false;
	}

	public static function update_stud_email($enroll=0,$email="") {
		global $db;
		$sanitized_enroll = trim($db->string_prep($enroll));
		$sanitized_email = trim($db->string_prep($email));
		

		$query = "UPDATE students SET ";
		$query .= "email='$sanitized_email' ";
		$query .= "WHERE EnrollNo=$sanitized_enroll LIMIT 1";
	    $result = $db->perform_query($query);
	   if ($db->get_affected_rows()==1) return true;
	   return false;
	}

	public static function update_stud_data($enroll=0,$name="",$course=1,$sem=1,$year=1,$email="") {
		global $db;
		$sanitized_enroll = trim($db->string_prep($enroll));
		$sanitized_name = trim($db->string_prep($name));
		$sanitized_course = trim($db->string_prep($course));
		$sanitized_sem = trim($db->string_prep($sem));
		$sanitized_year = trim($db->string_prep($year));
		$sanitized_email = trim($db->string_prep($email));
		
		$query = "UPDATE students SET ";
		$query .= "Student_Name='$sanitized_name'";
		$query .= ", Course_name=$sanitized_course";
		$query .= ", Sem=$sanitized_sem";
		$query .= ", SessionName=$sanitized_year";
		$query .= ", email='$sanitized_email' ";
		$query .= "WHERE EnrollNo=$sanitized_enroll LIMIT 1";
		
	    $result = $db->perform_query($query);
	   if ($db->get_affected_rows()==0||$db->get_affected_rows()==1) return true;
	   return false;
	}




	public static function find_student_name_by_EnrollNo($enroll=0) {
		global $db;

		$sanitized_enroll = trim($db->string_prep($enroll));
		$sanitized_name = trim($db->string_prep($name));
		$sanitized_course = trim($db->string_prep($course));
		$sanitized_sem = trim($db->string_prep($sem));
		$sanitized_year = trim($db->string_prep($year));
		$sanitized_email = trim($db->string_prep($email));
		$sql = "SELECT Student_Name FROM students WHERE EnrollNo={$sanitized_enroll} LIMIT 1" ;
		$result = $db->perform_query($sql);
		$array = $db->fetch_array($result);
		return $array['Student_Name']!=='' ? $array['Student_Name'] : "Unknown";
	}

	public function add_new_student($enroll=0,$adms="",$name="",$course=1,$sem=1,$year=1,$email="") {
		global $db;

		$sanitized_enroll = trim($db->string_prep($enroll));
		$sanitized_adms = trim($db->string_prep($adms));
		$sanitized_name = trim($db->string_prep($name));
		$sanitized_course = trim($db->string_prep($course));
		$sanitized_sem = trim($db->string_prep($sem));
		$sanitized_year = trim($db->string_prep($year));
		$sanitized_email = trim($db->string_prep($email));

		$query  = "INSERT INTO students (EnrollNo, AdmissionNo, Student_Name, Course_name, Sem, SessionName, email) ";
		$query .= "VALUES ($sanitized_enroll, '$sanitized_adms', '$sanitized_name', $sanitized_course, $sanitized_sem, $sanitized_year, '$sanitized_email') ";
		$result = $db->perform_query($query);
		if ($db->get_affected_rows()==0||$db->get_affected_rows()==1) return true;
	   return false;
	}

	public function deleteStudent($enroll=0) {
		global $db;

		$sanitized_enroll = trim($db->string_prep($enroll));

		$query  = "DELETE FROM students WHERE  EnrollNo={$sanitized_enroll} LIMIT 1";
		$result = $db->perform_query($query);
		if ($db->get_affected_rows()==1) return true;
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