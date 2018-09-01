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

if (is_null($enroll))die('Please try again! - Maybe some fields left blank!');
if ($enroll=='')die('Please try again!- Maybe some fields left blank!');

// if (strlen($email_sub)>255)die('Subject body exceeds maximum limit of 255 characters.'); 
// if (strlen($email_body)>1000)die('Email body exceeds maximum limit of 1000 characters.');

// $ip = trim($_SERVER['REMOTE_ADDR']);
// $dtstamp = trim(strftime("%Y-%m-%d %H:%M:%S", time()));

if (Student::student_exists_by_enroll($enroll)) {

   $mentId = Assign::find_mentor_of_stud($enroll);
   $html = '<div id="settingFetchedData">';
   if ($mentId=='unknown') {
      $html .=  "Student is not assigned to any mentor!<br />";
      $html .=  "You can assign this student to a mentor below:<br />";
   }else{
     $mentor = Mentor::find_mentor_by_id($mentId);
     // $html .= '<label>Current mentor id: '.$mentor->employee_id.'</label><br />';
     $html .= '<label>Current mentor name: '.$mentor->employee_name.'</label><br /><br />';
     $html .= '<label>You can change mentor of this mentor below:</label><br /><br />';
   }
     $html .=   '<select id="assignMid" class="w3-select" style="width:200px;">';
                 //if ($yid==0)echo '<option value="0" selected>All</option>';
                 //else    echo '<option value="0">All</option>';

                  $mentors = Mentor::find_all_mentors_except($mentId);
                  foreach ($mentors as $m) {
                  $html .= '<option value="'.$m->employee_id.'">'.$m->employee_name.'</option>';
                  }
    $html .= '</select><br /><br />';
    $html .= '<input type="button" id="settingSave" value="Save changes" class="w3-button " />';
    $html .= '</div>';
    echo $html;

   


}else die('Student does not exists.');

// $pr_id=1;
// $yr_id=0;
// $sem_id=0;

// if ($pr_id>=1&&$yr_id>=0&&$sem_id>=0) {
//         $students = Student::find_students_by_Program_sem($pr_id,$yr_id,$sem_id);
//         $html = '<div class="w3-panel w3-teal w3-large w3-serif">';
//         $html .= '<p>';
//          if (!empty($students)) $html .= count($students)." students found";
//          else $html .= "No student found";
//         $html .= '</p></div>';
//         if (!empty($students)) {
//              $html .= '<table class="w3-table-all">';
//              $html .= '<tr>
//               <th>AdmisssionNo</th>
//               <th>EnrollNo</th>
//               <th>Student Name</th>
//               <th>Course</th>
//               <th>Session</th>
//               <th>Semester</th>
//               <th>Mentor Name</th>
//               <th>Mentor id</th>
//               <th>Change</th>
//             </tr>';
//              foreach ($students as $s) {
//                     $mentor_id = trim(Assign::find_mentor_of_stud($s->EnrollNo));
//                     $html .= '<tr>';
//                     $html .= '<td>'.$s->AdmissionNo.'</td>';
//                     $html .= '<td>'.$s->EnrollNo.'</td>';
//                     $html .= '<td>'.$s->Student_Name.'</td>';
//                     $html .= '<td>'.Program::get_course_name_by_id($pr_id).'</td>';
//                     $html .= '<td>'.Year::get_session_name_by_id($s->SessionName).'</td>';
//                     $html .= '<td>'.Program::get_sem_name_by_id($s->Sem).'</td>';
//                     if($mentor_id!="unknown"){
//                         $mentor = Mentor::find_mentor_by_id($mentor_id);
//                         $html .= '<td>'.$mentor->employee_name.'</td>';
//                         $html .= '<td>'.$mentor->employee_id.'</td>';
//                     }else {
//                         $html .= '<td>Unknown</td>';
//                         $html .= '<td>Unknown</td>';
//                     }
//                     $html .= '<td><span id="'.$s->EnrollNo.'" class="my_popup_open setting_popup"></span></td>';
//                     $html .= '</tr>';
//              }
//              $html .= '</table>';
//              echo $html;
//         }    
// }
?>

