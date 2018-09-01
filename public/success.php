<?php
if (!empty($_GET['status']) && is_numeric($_GET['status']) ) { 
?>		
<html>
<head>
<meta http-equiv="refresh" content="2; URL=profile.php" />  
</head>
<body>
	<p>Signup successful !,You're being redirected in few seconds...</p>
	<img src="../images/ajax-loader.gif" />
</body>
</html>

<?php
} else {
	header("Location: error.php");
	exit();
}
?>