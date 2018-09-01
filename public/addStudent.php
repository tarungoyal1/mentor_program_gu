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
$add_studEnroll = isset($_POST['enroll']) ? test_input($_POST['enroll']): die('Please try again!');
$add_studAdms = isset($_POST['adms']) ? test_input($_POST['adms']): die('Please try again!');
$add_name = isset($_POST['name']) ? test_input($_POST['name']): die('Please try again!');
$add_studcourse = isset($_POST['prog']) ? test_input($_POST['prog']): die('Please try again!');
$add_studSem = isset($_POST['sem']) ? test_input($_POST['sem']): die('Please try again!');
$add_studSession = isset($_POST['year']) ? test_input($_POST['year']): die('Please try again!');
$add_studemail = isset($_POST['email']) ? test_input($_POST['email']): die('Please try again!');
$add_studMentor = isset($_POST['mentor']) ? test_input($_POST['mentor']): die('Please try again!');

if (is_null($add_studEnroll))die('Please try again! - Maybe some fields left blank!');
if ($add_studEnroll=='')die('Please try again!- Maybe some fields left blank!');
if (!is_numeric($add_studEnroll)) die('Invalid enrollment number');
if (strlen($add_studEnroll)<10||strlen($add_studEnroll)>12) die('Invalid enrollment number');

if (is_null($add_studAdms))die('Please try again! - Maybe some fields left blank!');
if ($add_studAdms=='')die('Please try again!- Maybe some fields left blank!');
if (strlen($add_studAdms)<10||strlen($add_studAdms)>13) die('Invalid Admission number');

if (is_null($add_name))die('Please try again! - Maybe some fields left blank!');
if ($add_name=='')die('Please try again!- Maybe some fields left blank!');
if (strlen($add_name)<5||strlen($add_name)>25) die('Invalid name');

if (is_null($add_studcourse))die('Please try again! - Maybe some fields left blank!');
if ($add_studcourse=='')die('Please try again!- Maybe some fields left blank!');

if (is_null($add_studSem))die('Please try again! - Maybe some fields left blank!');
if ($add_studSem=='')die('Please try again!- Maybe some fields left blank!');

if (is_null($add_studSession))die('Please try again! - Maybe some fields left blank!');
if ($add_studSession=='')die('Please try again!- Maybe some fields left blank!');

if (is_null($add_studemail))die('Please try again! - Maybe some fields left blank!');
if ($add_studemail=='')die('Please try again!- Maybe some fields left blank!');
if (strlen($add_studemail)<5||strlen($add_studemail)>50) die('Invalid email');

if (is_null($add_studMentor))die('Please try again! - Maybe some fields left blank!');
if ($add_studMentor=='')die('Please try again!- Maybe some fields left blank!');



$student = Student::student_exists_by_enroll_Or_admsno($add_studEnroll,$add_studAdms);

if (!$student) {
  //student doesn't exist
  $stud_insert =  Student::add_new_student($add_studEnroll,$add_studAdms,$add_name,$add_studcourse,$add_studSem,$add_studSession,$add_studemail);
  if ($stud_insert) {
       if(Assign::manage_assign_student_to_mentor($add_studMentor,$add_studEnroll))die('Student inserted and mentor assigned successfully.');
       else die('Student inserted but mentor could not assigned.');
  }else die('Something went wrong, try again.'); 
}
else die('Student already exists.');

?>

