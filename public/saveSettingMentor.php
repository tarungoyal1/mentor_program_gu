<?php
if ($_SERVER['REQUEST_METHOD']=="GET") {
	header("Location: error.php");
	exit();
}
?>
<?php
require_once ('../includes/session.php');
if (!$session->is_logged_in()) {
    die("You have been logged out.");
    //redirect_to("index.php?redirect_to={$_SERVER['REQUEST_URI']}");
}
require_once ('../includes/config.php');
require_once ('../includes/database.php');
require_once ('../includes/functions.php');
require_once ('../includes/mentor.php');
require_once ('../includes/student.php');
require_once ('../includes/assign.php');
require_once ('../includes/program.php');
require_once ('../includes/sem.php');
require_once ('../includes/year.php');
?>
<?php
$enroll = isset($_POST['enroll']) ? test_input($_POST['enroll']): die('Please try again!');
$mid = isset($_POST['mid']) ? test_input($_POST['mid']): die('Please try again!');

if (is_null($enroll)||is_null($mid))die('Please try again! - Maybe some fields left blank!');
if ($enroll==''||$mid=='')die('Please try again!- Maybe some fields left blank!');

// if (strlen($email_sub)>255)die('Subject body exceeds maximum limit of 255 characters.'); 
// if (strlen($email_body)>1000)die('Email body exceeds maximum limit of 1000 characters.');

// $ip = trim($_SERVER['REMOTE_ADDR']);
// $dtstamp = trim(strftime("%Y-%m-%d %H:%M:%S", time()));

if (Student::student_exists_by_enroll($enroll)) {
  if (Assign::find_mentor_of_stud($enroll)=='unknown') {
      //this means this student has no mentor yet, so insert query
    if (Assign::assign_student_to_mentor($mid,$enroll))
      echo '<label>changes successfully saved.</label>';
    else '<label>could not save changes, maybe this mentor is already assigned to student.</label>';
  }else{
    if (Assign::update_assign_student_to_mentor($mid,$enroll))
      echo '<label>changes successfully saved.</label>';
    else '<label>could not save changes, maybe this mentor is already assigned to student.</label>';
  }
   
}else die('Student does not exists.');

?>

