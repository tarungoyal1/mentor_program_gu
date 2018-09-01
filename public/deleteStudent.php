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
}
require_once ('../includes/config.php');
require_once ('../includes/database.php');
require_once ('../includes/functions.php');
require_once ('../includes/student.php');
require_once ('../includes/assign.php');

?>
<?php
$delete_studEnroll = isset($_POST['enroll']) ? test_input($_POST['enroll']): die('Please try again!');


if (is_null($delete_studEnroll))die('Please try again! - Maybe some fields left blank!');
if ($delete_studEnroll=='')die('Please try again!- Maybe some fields left blank!');
if (!is_numeric($delete_studEnroll)) die('Invalid enrollment number');


$student_exist = Student::student_exists_by_enroll($delete_studEnroll);

if ($student_exist) {
  $stud_delete = Student::deleteStudent($delete_studEnroll);
  if ($stud_delete) {
    if(Assign::deleteMentorOfStudent($delete_studEnroll)){
      die('Student deleted successfully.');
    }else  die('Student deleted successfully but mentor could not be unassigned.');
  }else die('No change couldnot be saved, try again.'); 
}else die('Student does not exist.');

?>

