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
$studEnroll = isset($_POST['enroll']) ? test_input($_POST['enroll']): die('Please try again!');

if (is_null($studEnroll))die('Please try again! - Maybe some fields left blank!');
if ($studEnroll=='')die('Please try again!- Maybe some fields left blank!');
if (!is_numeric($studEnroll)) die('Invalid enrollment number');

// if (strlen($email_sub)>255)die('Subject body exceeds maximum limit of 255 characters.'); 
// if (strlen($email_body)>1000)die('Email body exceeds maximum limit of 1000 characters.');

// $ip = trim($_SERVER['REMOTE_ADDR']);
// $dtstamp = trim(strftime("%Y-%m-%d %H:%M:%S", time()));

$student = Student::find_student_by_EnrollNo($studEnroll);

if (!empty($student)) {
   $html  = '<ul class="w3-ul w3-small" style="width:100%">';
    //name edit
    $html .= '<li><label style="display: inline;">Name :</label> <input style="display: inline;width:200px"" class="w3-input w3-border" type="text" id="manageEditName" value="'.$student->Student_Name.'" /></li>';

      //email edit
    $html .= '<li><label style="display: inline;">Email :</label> <input style="display: inline;width:200px"" class="w3-input w3-border" type="text" id="manage_email" value="'.$student->email.'" /></li>';

    //course edit
    $html .= '<li><label style="display: inline;">Course :</label>';
    $html .= '<select id="manage_program_id" class="w3-select" style="width:200px;">';
                  $programs = Program::find_all_programs();
                  foreach ($programs as $p) {
                    if($p->id>1&&$p->id==$student->Course_name)
                           $html .= '<option value="'.$p->id.'" selected>'.$p->course_name.'</option>';
                    else  $html .=  '<option value="'.$p->id.'">'.$p->course_name.'</option>';
                  }//for each end
    $html .= '</select>';
    $html .= '</li>';

    //sem edit
    $html .= '<li><label style="display: inline;">Semester :</label>';
    $html .= '<select id="manage_sem_id" class="w3-select" style="width:200px;">';
                 $sems = Sem::find_all_sems();
                  foreach ($sems as $s) {
                    if($s>=1&&$s->id==$student->Sem)
                           $html .= '<option value="'.$s->id.'" selected>'.$s->sem_name.'</option>';
                    else  $html .=  '<option value="'.$s->id.'">'.$s->sem_name.'</option>';
                  }//for each end
    $html .= '</select>';
    $html .= '</li>';

    //session edit
    $html .= '<li><label style="display: inline;">Session :</label>';
    $html .= '<select id="manage_session_id" class="w3-select" style="width:200px;">';
                 $years = Year::find_all_years();
                  foreach ($years as $y) {
                    if($y>=1&&$y->id==$student->SessionName)
                           $html .= '<option value="'.$y->id.'" selected>'.$y->session.'</option>';
                    else  $html .=  '<option value="'.$y->id.'">'.$y->session.'</option>';
                  }//for each end
    $html .= '</select>';
    $html .= '</li>';


    //mentor edit
    $mentId = Assign::find_mentor_of_stud($studEnroll);
    if ($mentId=='unknown') {
      //no mentor assigned so insert flag;
       $html .= '<input type="hidden" id="mng_mt_qtype" value="insert">';
       $html .= '<li><label style="display: inline;">Mentor : No mentor assigned</label></li>';
       $html .= '<li><label style="display: inline;">Select mentor : </label>';
       $mentors = Mentor::find_all_mentors();
       $html .=   '<select id="manageAssignMid" class="w3-select" style="width:200px;">';

          foreach ($mentors as $m) $html .= '<option value="'.$m->employee_id.'">'.$m->employee_name.'</option>'; 
    }else {
      $html .= '<input type="hidden" id="mng_mt_qtype" value="update">';
      $html .= '<li><label style="display: inline;">Mentor: </label>';
       $html .=   '<select id="manageAssignMid" class="w3-select" style="width:200px;">';
       $mentors = Mentor::find_all_mentors();       
                foreach ($mentors as $m) {
                    if($m->employee_id==$mentId)
                      $html .= '<option value="'.$m->employee_id.'" selected>'.$m->employee_name.'</option>';
                    else  $html .= '<option value="'.$m->employee_id.'">'.$m->employee_name.'</option>';                 
                  }
    }
    $html .= '</select>';
    $html .= '</li>';
    //

    $html  .= '</ul>';
    
    $html .= '<input type="button" id="manageUpdateBtn" value="Save changes" class="w3-button " />';

  
   echo $html;
}
else die('Student does not exist.');

?>

