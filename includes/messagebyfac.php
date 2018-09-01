<?php

class MessageByFac {
	public $id;
	public $to_enroll;
	public $from_guid;
	public $msgSub;
	public $msgBody;
	public $msgTime;
	public $status;
	public $senderip;

	public static function  find_all_messages() {
	global $db;
	$query = "SELECT * FROM messages_by_fac ";
	$result = self::find_by_sql($query);
	return $result;
	}

	public static function find_msgs_by_guid($mid="") {
		global $db;
		$sanitized_mid= $db->string_prep($mid);
		$result_array = self::find_by_sql("SELECT * FROM messages_by_fac WHERE from_guid='{$sanitized_mid}' ORDER BY msgTime DESC");
		return !empty($result_array) ? $result_array : array();
	}

	public static function find_msgs_to_enroll($enroll=0, $mid="") {
		global $db;
		$sanitized_mid= $db->string_prep($mid);
		$sanitized_enroll= $db->string_prep($enroll);
		$result_array = self::find_by_sql("SELECT * FROM messages_by_fac WHERE to_enroll={$sanitized_enroll} AND from_guid='{$sanitized_mid}'");
		return !empty($result_array) ? $result_array : array();
	}

	 public static function count_msgs_by_mentor_to_enroll($enroll=0, $mid=""){
    	global $db;
      $sanitized_mid= $db->string_prep($mid);
	  $sanitized_enroll= $db->string_prep($enroll);
      $sql="SELECT count(*) as rowcount FROM messages_by_fac WHERE to_enroll={$sanitized_enroll} AND from_guid='{$sanitized_mid}'";
	    $result = $db->perform_query($sql);
   	    $array= $db->fetch_array($result);   	
        $count=$array['rowcount'];
        // $db->free_result($result);
        return $count;
    }


    public function insert_msg_by_mentor($mentor_id, $stud_enroll, $email_sub, $email_body, $status, $dtstamp, $ip) {
		global $db;
		$sanitized_mentor_id = $db->string_prep($mentor_id);
		$sanitized_stud_enroll = $db->string_prep($stud_enroll);
		$sanitized_email_sub = $db->string_prep($email_sub);
		$sanitized_email_body = $db->string_prep($email_body);
		$sanitized_timestamp = $db->string_prep($dtstamp);
		$sanitized_status = $db->string_prep($status);
		$sanitized_ip = $db->string_prep($ip);

		$query  = "INSERT INTO messages_by_fac (from_guid, to_enroll, msgSub, msgBody, msgTime, status, senderip) ";
		$query .= "VALUES ('$sanitized_mentor_id', $sanitized_stud_enroll, '$sanitized_email_sub', '$sanitized_email_body', '$sanitized_timestamp', '$sanitized_status', '$sanitized_ip') ";
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