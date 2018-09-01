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
$manage_studEnroll = isset($_POST['enroll']) ? test_input($_POST['enroll']): die('Please try again!');
$manage_name = isset($_POST['name']) ? test_input($_POST['name']): die('Please try again!');
$manage_studcourse = isset($_POST['prog']) ? test_input($_POST['prog']): die('Please try again!');
$manage_studSem = isset($_POST['sem']) ? test_input($_POST['sem']): die('Please try again!');
$manage_studSession = isset($_POST['year']) ? test_input($_POST['year']): die('Please try again!');
$manage_studemail = isset($_POST['email']) ? test_input($_POST['email']): die('Please try again!');
$manage_studMentor = isset($_POST['mentor']) ? test_input($_POST['mentor']): die('Please try again!');
$manage_studMentorQtype = isset($_POST['mentorqtype']) ? test_input($_POST['mentorqtype']): die('Please try again!');

if (is_null($manage_studEnroll))die('Please try again! - Maybe some fields left blank!');
if ($manage_studEnroll=='')die('Please try again!- Maybe some fields left blank!');
if (!is_numeric($manage_studEnroll)) die('Invalid enrollment number');

if (is_null($manage_name))die('Please try again! - Maybe some fields left blank!');
if ($manage_name=='')die('Please try again!- Maybe some fields left blank!');

if (is_null($manage_studcourse))die('Please try again! - Maybe some fields left blank!');
if ($manage_studcourse=='')die('Please try again!- Maybe some fields left blank!');

if (is_null($manage_studSem))die('Please try again! - Maybe some fields left blank!');
if ($manage_studSem=='')die('Please try again!- Maybe some fields left blank!');

if (is_null($manage_studSession))die('Please try again! - Maybe some fields left blank!');
if ($manage_studSession=='')die('Please try again!- Maybe some fields left blank!');

if (is_null($manage_studemail))die('Please try again! - Maybe some fields left blank!');
if ($manage_studemail=='')die('Please try again!- Maybe some fields left blank!');

if (is_null($manage_studMentor))die('Please try again! - Maybe some fields left blank!');
if ($manage_studMentor=='')die('Please try again!- Maybe some fields left blank!');

if (is_null($manage_studMentorQtype))die('Please try again! - Maybe some fields left blank!');
if ($manage_studMentorQtype=='')die('Please try again!- Maybe some fields left blank!');
if ($manage_studMentorQtype!='insert'&&$manage_studMentorQtype!='update')die('Please try again!- Maybe some fields left blank!');



$student = Student::find_student_by_EnrollNo($manage_studEnroll);

if (!empty($student)) {

  $stud_profile_update = Student::update_stud_data($manage_studEnroll,$manage_name,$manage_studcourse,$manage_studSem,$manage_studSession,$manage_studemail);
  if ($stud_profile_update) {
    if ($manage_studMentorQtype=='insert') {
       if(Assign::manage_assign_student_to_mentor($manage_studMentor,$manage_studEnroll))die('All changes successfully saved and mentor assigned.'); 
    }elseif ($manage_studMentorQtype=='update') {
      if(Assign::manage_update_assign_student_to_mentor($manage_studMentor,$manage_studEnroll))die('All changes successfully saved and mentor updated.'); 
    }
  }else die('No change couldnot be saved, try again.'); 
}
else die('Student does not exists.');

?>

