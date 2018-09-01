<?php
if ($_SERVER['REQUEST_METHOD']=="GET") {
	header("Location: error.php");
	exit();
}
?>
<?php
require_once ('../includes/session.php');
if (!$session->is_logged_in()) {
    die('You have been logged out.');
    //redirect_to("index.php?redirect_to={$_SERVER['REQUEST_URI']}");
}
require_once ('../includes/config.php');
require_once ('../includes/database.php');
require_once ('../includes/functions.php');
require_once ('../includes/mentor.php');
require_once ('../includes/student.php');
require_once ('../includes/program.php');
require_once ('../includes/year.php');
require_once ('../includes/assign.php');
require_once ('../includes/messagebyfac.php');
?>
<?php
$studEnroll = isset($_POST['enroll']) ? test_input($_POST['enroll']): die('Please try again!');

if (is_null($studEnroll))die('Please try again! - Maybe some fields left blank!');
if ($studEnroll=='')die('Please try again!- Maybe some fields left blank!');

if (!is_numeric($studEnroll)){
  if (strtolower(substr($studEnroll, 2,4))=="scse") {
      $student = Student::find_student_by_AddmNo($studEnroll);
      $studEnroll = $student->EnrollNo;
  }else die('Invalid enrollment number');
} else $student = Student::find_student_by_EnrollNo($studEnroll);


if (!empty($student)) {

    $html  = '<ul class="w3-ul w3-large" style="width:40%">';
    $html .= '<li>Enrollment no: '.$student->EnrollNo.'</li>';
    $html .= '<li>Admission no: '.$student->AdmissionNo.'</li>';
    $html .= '<li>Name: '.$student->Student_Name.'</li>';
    $html .= '<li>Course: '.Program::get_course_name_by_id($student->Course_name).'</li>';
    $html .= '<li>Semester: '.Program::get_sem_name_by_id($student->Sem).'</li>';
    $html .= '<li>Session: '.Year::get_session_name_by_id($student->SessionName).'</li>';
    $html .= '<li>Batch: '.$student->Batch_name.'</li>';
    $html .= '<li>Email: '.$student->email.'</li>';

    try {
      $mentor = Assign::find_mentor_of_stud($student->EnrollNo);
      if ($mentor=='unknown')$html .= '<li>Current mentor: Unknown</li>';
      else $html .= '<li>Current mentor: '.Mentor::find_mentor_name($mentor).'</li>';
    } catch (Exception $e) {$html .= '<li class="w3-padding-small">Current mentor: Unknown</li>';}
    $html  .= '</ul>';

    $html .= '<div class="manageEditDiv"><span style="display:inline;"  id="'.$studEnroll.'" class="my_popup_open manage_popup"><i style="display:inline;" class="fa fa-pencil fa-fw"></i> Edit</span></div>';
    $html .= '<input type="hidden" id="managehiddenenroll" value="'.$studEnroll.'" />';
    $html .= '<div id="delete_pag_flash" style="visibility: hidden;"></div>';
    $html .= '<input type="button" id="manage_delete" value="Delete" class="w3-button " />';



    // $html .=   '<select id="manageAssignMid" class="w3-select" style="width:200px;">';
    //              //if ($yid==0)echo '<option value="0" selected>All</option>';
    //              //else    echo '<option value="0">All</option>';

    //               $mentors = Mentor::find_all_mentors_except($mentId);
    //               foreach ($mentors as $m) {
    //               $html .= '<option value="'.$m->employee_id.'">'.$m->employee_name.'</option>';
    //               }
    // $html .= '</select><br /><br />';
    // $html .= '<input type="button" id="settingSave" value="Save changes" class="w3-button " />';
    echo $html;   
}else die('Student does not exist.');

?>