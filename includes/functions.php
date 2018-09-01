<?php

function redirect_to($location){
	header("Location: ".$location);
	exit();
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function mysql_time() {
	return strftime("%Y-%m-%d %H:%M:%S", time());
}

function fancy_dateTime($input="") {
	return date("h:i a | d-m-Y", strtotime($input));
}

?>