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
require_once ('../includes/assign.php');
require_once ('../includes/messagebyfac.php');
?>
<?php
$mentor_id = isset($_POST['mid']) ? test_input($_POST['mid']): die('Please try again!');

if (is_null($mentor_id))die('Please try again! - Maybe some fields left blank!');
if ($mentor_id=='')die('Please try again!- Maybe some fields left blank!');
if (Mentor::validate_mentor($mentor_id)) {
   $studs_enrolls= Assign::find_all_students_of_mentor($mentor_id);
    // $msgByFac = MessageByFac::find_msgs_by_guid($mentor_id);
    // if(empty($msgByFac))die('No data to show');
    if(empty($studs_enrolls))die('No data to show');
    else { 
          $html = '<div id="trackMentorStuds">';
          $html .= '<table class="w3-table-all"><tr>
            <th>AdmisssionNo</th>
            <th>EnrollNo</th>
            <th>Student Name</th>
            <th>Course</th>
            <th>Semester</th>
            <th>Email</th>
            <th>#Messages sent</th>
          </tr>';

          foreach ($studs_enrolls as $stud) {
            if ($stud->student_enroll==''||is_null($stud->student_enroll))continue;
                $student = Student::find_student_by_EnrollNo($stud->student_enroll);
                 if(!empty($student)){
                    $html .=  '<tr>';
                    $html .=  '<td>'.$student->AdmissionNo.'</td>';
                    $html .=  '<td>'.$student->EnrollNo.'</td>';
                    $html .=  '<td>'.ucwords(strtolower($student->Student_Name)).'</td>';
                    $html .=  '<td>'.Program::get_course_name_by_id($student->Course_name).'</td>';
                    $html .=  '<td>'.Program::get_sem_name_by_id($student->Sem).'</td>';
                    $html .=  '<td>'.strtolower($student->email).'</td>';
                    $html .=  '<td>'.MessageByFac::count_msgs_by_mentor_to_enroll($student->EnrollNo,$mentor_id).' emails</td>';
                    $html .=  '</tr>';
                 }else{
                    $html .= '<tr>';
                    $html .= '<td>'."-".'</td>';
                    $html .= '<td>'.$stud->student_enroll.'</td>';
                    $html .= '<td>'."-".'</td>';
                    $html .= '<td>'."-".'</td>';
                    $html .= '<td>'."-".'</td>';
                    $html .= '<td>'."-".'</td>';
                    $html .= '<td>'."-".'</td>';
                    $html .= '</tr>';
                 }
          }
          $html .= '</table></div>';
          echo $html;
   }

}else {
  die('Mentor does not exist.');
}
// foreach ($msgByFac as $msg) {
//             $html .=  '<div  class="w3-panel w3-border"  class="msgBox">';
//             $html .= '<label class="msgTop">To:'.Student::find_student_name_by_EnrollNo($msg->to_enroll).' ('.$msg->to_enroll.')</label>';
//                 $html .= '<label class="msgTop">Date:'.fancy_dateTime($msg->msgTime).'</label>';           
//             $html .=  '<label class="msgTop">Subject: '.$msg->msgSub.'</label><br />';
//             $html .=  '<label class="msgTop" style="color:#009688!important"><b>Message: </b></label><br />';
//             $html .=  '<p class="msgBody">'.$msg->msgBody.'</p>';
//             $html .=  '</div>';
// }

?>