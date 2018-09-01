<?php

class Mentor {
	public $employee_id;
	public $employee_name;
	public $designation;
	public $room_no;
	public $contact;
	public $email;
	public $place;


	public static function  find_all_mentors() {
	global $db;
	$query = "SELECT * FROM mentors ";
	$result = self::find_by_sql($query);
	return $result;
	}

	public static function  find_all_mentors_except($mentorId="") {
	global $db;
	$query = "SELECT * FROM mentors WHERE employee_id!='$mentorId'";
	$result = self::find_by_sql($query);
	return $result;
	}

	public static function find_mentor_by_id($mid="") {
		global $db;
		$sanitized_mid= $db->string_prep($mid);
		$result_array = self::find_by_sql("SELECT * FROM mentors WHERE employee_id='{$sanitized_mid}' LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}


	public static function find_mentor_id($id=0) {
		global $db;
		$result_array = self::find_by_sql("SELECT employee_id FROM mentors WHERE employee_id={$id} LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function find_mentor_profile($mentor_id="") {
		global $db;
		$sanitized_mentor_id = $db->string_prep($mentor_id);
		$result_array = self::find_by_sql("SELECT * FROM mentors WHERE employee_id='$sanitized_mentor_id' LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public function find_mentor_name($mentor_id="") {
		global $db;
		$sanitized_mentor_id = $db->string_prep($mentor_id);
		$query = "SELECT employee_name FROM mentors WHERE employee_id='$sanitized_mentor_id' LIMIT 1";
		$result = $db->perform_query($query);
		$array = $db->fetch_array($result);
		if (!empty($array)) {
			$string = implode(" ", $array);
			return ucwords($string);
		} else {
			return null;
		}		
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

	public static function validate_mentor($mentor="") {
		global $db;
		$sanitized_guid = $db->string_prep($mentor);
		$result_array = self::find_by_sql("SELECT employee_id FROM mentors WHERE employee_id='$sanitized_guid'");
		return !empty($result_array) ? true : false;
	}

	public static function insert_mentor($mentorid="", $mentorname="") {
		global $db;
		$sanitized_guid = $db->string_prep($mentorid);
		$sanitized_guname = $db->string_prep($mentorname);
		$query  = "INSERT INTO mentors (employee_id, employee_name) ";
		$query .= "VALUES ('$sanitized_guid', '$sanitized_guname ') ";
		$result = $db->perform_query($query);
	}

	public static function authenticate_mentor_login($guid="", $password="") {
		global $db;
		$sanitized_guid = $db->string_prep($guid);
		$sanitized_password = $db->string_prep($password);

		$sql_query  = "SELECT fac_pass_hash FROM mentor_login ";
		$sql_query .= "WHERE guid='$sanitized_guid'";
		$result = $db->perform_query($sql_query);
		$got_mentor = $db->fetch_array($result);
		if (!empty($got_mentor)) {
			if (crypt($sanitized_password, $got_mentor['fac_pass_hash'])==$got_mentor['fac_pass_hash']) {
			$sql  = "SELECT id,guid FROM mentor_login WHERE ";
			$sql .= "guid='$sanitized_guid' AND ";
			$sql .= "fac_pass_hash = '{$got_mentor['fac_pass_hash']}'";
			$sql .= " AND active_by_admin=1 AND ";
			$sql .= "verified=1 ";
			$sql .= "LIMIT 1";
			$result = $db->perform_query($sql);
			$mentor = $db->fetch_array($result);
			return !empty($mentor) ? $mentor : null;
		    }
		}
		return null;
				
	}

	public static function authenticate_mentor_for_login_on_signup($mentorname="", $password="") {
		global $db;
		$sql_query  = "SELECT mentor_pass FROM mentors ";
		$sql_query .= "WHERE mentor_login='$mentorname' ";
		$result = $db->perform_query($sql_query);
		$got_mentor = $db->fetch_array($result);
		if (crypt($password, $got_mentor['mentor_pass'])==$got_mentor['mentor_pass']) {
			$sql  = "SELECT * FROM mentors WHERE ";
			$sql .= "mentor_login='$mentorname' AND ";
			$sql .= "mentor_pass = '{$got_mentor['mentor_pass']}' ";
			$sql .= "LIMIT 1";
			$result_array = self::find_by_sql($sql);
			return !empty($result_array) ? array_shift($result_array) : false;
		}		
	}

	public function full_name($mentor_id="") {
		global $db;
		$sanitized_mentor_id = $db->string_prep($mentor_id);
		$query = "SELECT first_name, last_name FROM mentor_profiles WHERE mentor_id='$sanitized_mentor_id' LIMIT 1";
		$result = $db->perform_query($query);
		$array = $db->fetch_array($result);
		if (!empty($array)) {
			$string = implode(" ", $array);
			return ucwords($string);
		} else {
			return null;
		}		
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

class Creatementor extends Mentor {

	public static function validate_mentor($guid="", $email="") {
		global $db;
		$sanitized_guid = trim($db->string_prep($guid));
		$sanitized_email = trim($db->string_prep($email));
		$result_array = self::find_by_sql("SELECT employee_id FROM mentors WHERE employee_id='$sanitized_guid' AND email='$sanitized_email'");
		return !empty($result_array) ? true : false;
	}

	public static function check_already_exists($guid="") {
		global $db;
		$sanitized_guid = trim($db->string_prep($guid));
		$sql = "SELECT guid,verified FROM mentor_login WHERE guid='$sanitized_guid'";
		$result = $db->perform_query($sql);
	    $result_array = $db->fetch_array($result);
	    // $db->free_result($result);
	    return  !empty($result_array) ? true : false;  
	}


	public static function check_already_verified($guid="") {
		global $db;
		$sanitized_guid = trim($db->string_prep($guid));
		if(self::check_already_exists($guid)){
			//now check if mentor is verified or not
				$sql = "SELECT verified FROM mentor_login WHERE guid='$sanitized_guid'";
		        $result = $db->perform_query($sql);
	            $result_array = $db->fetch_array($result);
	            if(!empty($result_array)){
	            	return $result_array['verified']==1 ? true :false;
	            }
		}	
	    return false;  
	}

	public static function insert_mentor_verify_token($guid="",$vtoken){
		global $db;
		$sanitized_guid = trim($db->string_prep($guid));
		$sanitized_vtoken= trim($db->string_prep($vtoken));
		//insert
		$sql  = "INSERT INTO mentor_login (guid, verified, verify_token) ";
	    $sql .= "VALUES ('$sanitized_guid', 0, $sanitized_vtoken) ";
	    echo $sql;
	   $result = $db->perform_query($sql);
	   if ($db->get_affected_rows()==1) return true;
	   return false;
	}

	public static function update_mentor_verify_token($guid="",$vtoken){
		global $db;
		$sanitized_guid = trim($db->string_prep($guid));
		$sanitized_vtoken= trim($db->string_prep($vtoken));
		//insert
		$query = "UPDATE mentor_login SET ";
		$query .= "verify_token='$sanitized_vtoken' ";
		$query .= " WHERE guid='$sanitized_guid'";
	   $result = $db->perform_query($query);
	   if ($db->get_affected_rows()==1) return true;
	   return false;
	}

	public static function validate_mentor_token($guid="", $token="") {
		global $db;
		$sanitized_guid = trim($db->string_prep($guid));
		$sanitized_token = trim($db->string_prep($token));
		$result_array = self::find_by_sql("SELECT guid FROM mentor_login WHERE guid='$sanitized_guid' AND verify_token='$sanitized_token'");
		return !empty($result_array) ? true : false;
	}

	public static function update_choosen_password($guid="",$token="",$pass="") {
		global $db;
		$sanitized_guid = trim($db->string_prep($guid));
		$sanitized_token = trim($db->string_prep($token));
		$hashed_password = self::password_encrypt($pass);
		$sanitized_pass = trim($db->string_prep($hashed_password));

		$query = "UPDATE mentor_login SET ";
		$query .= "fac_pass_hash='$sanitized_pass', ";
		$query .= "active_by_admin=1, ";
		$query .= "verified=1 ";
		$query .= "WHERE guid='$sanitized_guid' AND verify_token=$sanitized_token";
	    $result = $db->perform_query($query);
	   if ($db->get_affected_rows()==1) return true;
	   return false;
	}


	public function signupmentor() {
		global $db;
		$small_mentorname = strtolower($this->mentor_login);
		$hashed_password = self::password_encrypt($this->mentor_pass);
		$query  = "INSERT INTO mentors (mentor_login, mentor_pass, mentor_date_time, mentor_status, account_status, mentor_regd_ip) ";
		$query .= "VALUES ('$small_mentorname', '$hashed_password', '$this->mentor_date_time', '$this->mentor_status', '$this->account_status', '$this->mentor_regd_ip') ";
		$result = $db->perform_query($query);
	}

	public function update_profile($mentor_id) {
		global $db;
		$sanitized_mentor_id = $db->string_prep($mentor_id);
		$query  = "INSERT INTO mentor_profiles (mentor_id, first_name, last_name, gender, email, email_type) ";
		$query .= "VALUES ('$sanitized_mentor_id', '$this->first_name', '$this->last_name', '$this->gender', '$this->email', '$this->email_type') ";
		$result = $db->perform_query($query);
		if (!empty($mentor_id) && $result) {
			return true;
		} else {
			return false;			
		}
	}	

	public static function password_encrypt($password) {
  	  $hash_format = "$2y$10$";   // Tells PHP to use Blowfish with a "cost" of 10
	  $salt_length = 22; 					// Blowfish salts should be 22-characters or more
	  $salt = self::generate_salt($salt_length);
	  $format_and_salt = $hash_format . $salt;
	  $hash = crypt($password, $format_and_salt);
		return $hash;
	}
	
	private static function generate_salt($length) {
	  // Not 100% unique, not 100% random, but good enough for a salt
	  // MD5 returns 32 characters
	  $unique_random_string = md5(uniqid(mt_rand(), true));
	  
		// Valid characters for a salt are [a-zA-Z0-9./]
	  $base64_string = base64_encode($unique_random_string);
	  
		// But not '+' which is valid in base64 encoding
	  $modified_base64_string = str_replace('+', '.', $base64_string);
	  
		// Truncate string to the correct length
	  $salt = substr($modified_base64_string, 0, $length);
	  
		return $salt;
	}

	public function authenticate_mentorname() {
		global $db;
		$small_mentorname = strtolower($this->mentor_login);
		$query  = "SELECT mentor_login FROM mentors WHERE mentor_login='$small_mentorname'";
		$result = $db->perform_query($query);
		$count = mysqli_num_rows($result);
		if($count > 0){
		    return false;
		}
		else {
		    return true;
		}
	}
}
?>