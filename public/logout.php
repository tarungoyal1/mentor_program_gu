<?php
require_once ('../includes/initialize.php');

	$session->logout();
	if (isset($_COOKIE['kpl'])) {
		setcookie("kpl", '', time()-42000, '/');
	}
	session_destroy();
	redirect_to("/mentor/public/");	
?>
