<?php
include 'header.php';
?>
<?php
if (!$session->is_logged_in()) {
    redirect_to("index.php?redirect_to={$_SERVER['REQUEST_URI']}");
}else{
  if ($session->is_logged_in()) {
    if ($session->user_id==$crguid) {
        redirect_to("crdash.php");        
    }
 }
}
// if($session->user_id==0)redirect_to("index.php?redirect_to={$_SERVER['REQUEST_URI']}");
 ?>
<div class="clear"></div>
<div id="dashboardContainer">
    <div id="leftsidebar" class="w3-border">
        
                        <?php
                        if ($session->is_logged_in()) { 
                        // echo "<a href=\"upload_photo.php\" class=\"upload\">Upload</a>";                    
                            $mentor = Mentor::find_mentor_profile(trim($session->user_id));
                            // echo " &nbsp;&nbsp;Welcome, ";
                            // echo "<strong>".$user->first_name($user->id)."</strong>";
                            ?>
                            <!-- &nbsp;<img id="header_user_icon" src="../images/user.png" /> -->
                            <!-- <div class="clear"></div> -->
                        <ul class="w3-ul">
                          <li class="dashMystudWelcome">Welcome, <br /><?php echo ucwords(strtolower($mentor->employee_name)); ?><br />
                             (<b><?php echo $mentor->employee_id; ?></b>)
                          </li>
                          <?php 
                          $page = isset($_GET['p']) ? test_input($_GET["p"]) : 0;
                          if (isset($_GET['p'])) {
                                 $page = test_input($_GET["p"]);
                                 if ($page!=0) {
                                  if ($page==1||$page==2||$page==3||$page==4){$page=$page;}else $page = 0;
                                 }else $page = 0;                               
                          }else $page = 0;
                           ?>

                          <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="dashSideLink"><li class="dashMystud" <?php if($page==0)echo 'id="selected"'; ?>>My students</li></a>

                          <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?p=1" class="dashSideLink"><li class="dashMystud" <?php if($page==1)echo 'id="selected"'; ?>>My Profile</li></a>
                          <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?p=2" class="dashSideLink"><li class="dashMystud" <?php if($page==2)echo 'id="selected"'; ?>>Contact Coordinator</li></a>
                          <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?p=3" class="dashSideLink"><li class="dashMystud" <?php if($page==3)echo 'id="selected"'; ?>>Inbox<span style="font-size:12px;"> (msgs from CR)</span></li></a>
                          <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?p=4" class="dashSideLink"><li class="dashMystud" <?php if($page==4)echo 'id="selected"'; ?>>
                          Sent<span style="font-size:12px;"> (msgs to students)</span></li></a>        
                          <li><a href="/mentor/public/logout.php" class="w3-button" style="background-color: #F7B733;width: 100%;">Logout</a></li>
                        </ul>
                        
    </div>
  <div id="dashContent" class="w3-border">
	<?php echo 	$session->message(); ?>
          
          <?php 
              if ($page==0) {
                $studs_enrolls= Assign::find_all_students_of_mentor($mentor->employee_id);
          ?>
          <div class="w3-panel w3-blue" id="dashTitle" >
              My students (<?php 
              echo sizeof($studs_enrolls);
              ?>)
          </div>
        <div>
         <table class="w3-table-all">
           <tr>
            <th>AdmisssionNo</th>
            <th>EnrollNo</th>
            <th>Student Name</th>
            <th>Course</th>
            <th>Session</th>
            <th>Semester</th>
            <th>Batch</th>
            <th>Email</th>
            <th>Contact</th>
          </tr>
            <?php  
           $count=0;
           $unassarray = array();
            foreach ($studs_enrolls as $stud) {
                if ($stud->student_enroll==''||is_null($stud->student_enroll))continue;
                $student = Student::find_student_by_EnrollNo($stud->student_enroll);
                if(!empty($student)){
                  echo '<tr>';
                 echo '<td>'.$student->AdmissionNo.'</td>';
                echo '<td>'.$student->EnrollNo.'</td>';
                echo '<td>'.ucwords(strtolower($student->Student_Name)).'</td>';
                echo '<td>'.Program::get_course_name_by_id($student->Course_name).'</td>';
                echo '<td>'.Year::get_session_name_by_id($student->SessionName).'</td>';
                echo '<td>'.Program::get_sem_name_by_id($student->Sem).'</td>';
                echo '<td>'.$student->Batch_name.'</td>';
                echo '<td>'.strtolower($student->email).'</td>';
                echo '<td><span id="'.$student->EnrollNo.'" class="my_popup_open"></span></td>';
                // echo '<td><img src="/mentor/includes/emailcircle_opt.png" height="32" width="32" /></td>';
                echo '</tr>';
                ++$count;
                }else{
                   $unassarray[]=$stud->student_enroll;
                   echo '<tr>';
                echo '<td>'."-".'</td>';
                echo '<td>'.$stud->student_enroll.'</td>';
                echo '<td>'."-".'</td>';
                echo '<td>'."-".'</td>';
                echo '<td>'."-".'</td>';
                echo '<td>'."-".'</td>';
                echo '<td>'."-".'</td>';
                echo '<td>'."-".'</td>';
                echo '<td>'."-".'</td>';
                echo '</tr>';
                }
                
            }
             ?>
             </table>
   <div id="my_popup">
      <button class="my_popup_close">Close</button>
        <div id="insidePopup" >
          <label id="sendingtoLabel"></label>
          <label class="w3-text">Subject:</label>
          <input class="w3-input w3-border" type="text" id="emailSub" value="" style="width:80%;"></input>
           
          <label class="w3-text">Message:</label>
          <textarea class="w3-input w3-border" type="text" id="emailBody" value="" rows="10" style="width:80%;"></textarea>
          <br />
          <input type="hidden" id="mid" value="<?php echo $mentor->employee_id;  ?>">
          <input type="hidden" id="enroll" value="">
          <input type="button" value="Send" id="sendMail" class="w3-button" style="width:80%;background-color: #F7B733" />
          <br />
       </div>      
      <!-- Add an optional button to close the popup -->
      <div id="pag_flash" style="visibility: hidden;"><img  width="32" height="32" src="/mentor/style/comment.gif" /></div>
  </div>
             <?php } elseif ($page==1) {
              ?>
              <div class="w3-panel w3-blue" id="dashTitle" >
              My Profile
             </div>
             <div class="mentorInfo">
             <ul class="w3-ul w3-card-2" style="width:80%">
                <li>Mentor Name: <?php echo $mentor->employee_name; ?></li>
                <li>Mentor designation: <?php echo $mentor->designation; ?></li>
                <li>Mentor Room_no: <?php echo $mentor->room_no; ?></li>
                <li>Mentor contact: <?php echo $mentor->contact; ?></li>
                <li>Mentor email: <?php echo $mentor->email; ?></li>
                <li>Mentor place: <?php echo $mentor->place; ?></li>
              </ul>
             </div>

            <?php } elseif ($page==2) { ?>
              <div class="w3-panel w3-blue" id="dashTitle" >
              Contact Coordinator
             </div>
             <div id="contactCr">
              <h5>Here, You as a mentor may contact <?php echo $crname; ?>, the Coordinator of Mentors-Mentees.You can approach her for any help, sensitize her about any issue or suggest her with almost any insight you may have pertinent to this program. </h5>
                 <input type="hidden" id="ccMid" value="<?php echo $mentor->employee_id;  ?>">
                <label class="w3-text">Subject:</label>
                <input class="w3-input w3-border" type="text" id="contCrSub" value="" style="width:60%;"></input>
                <br />
                <label class="w3-text">Message:</label>
                <textarea class="w3-input w3-border" type="text" id="contCrBody" value="" rows="10" style="width:60%;"></textarea>
                <br /><br />
                <input type="button" value="Send" id="contCrSend" class="w3-button" style="width:60%;background-color: #F7B733" />
                <br />
                <div id="pag_flash_cc" style="visibility: hidden;"><img  width="32" height="32" src="/mentor/style/comment.gif" /></div>
             </div>
            
            <?php  } elseif ($page==3) { ?>
             <div class="w3-panel w3-blue" id="dashTitle" style="width:300px;height:60px;" >
              Messages from Coordinator
             </div>
             <div id="mentorInbox">
              <?php
                    $msgByCr = MessageByCR::find_msgs_by_cr_to_mid($mentor->employee_id);
                    if(empty($msgByCr)){
                      echo '<h5>No message to show</h5>';
                    }else { 
                    foreach ($msgByCr as $msg) { ?>
                    <div  class="w3-panel w3-border"  class="msgBox">
                     <label class="msgTop">From: <?php echo $crname; ?></label>
                     <label class="msgTop">Date: <?php echo fancy_dateTime($msg->msgTime); ?></label>                     <label class="msgTop">Subject: <?php echo $msg->msgSub; ?></label><br />
                     <label class="msgTop" style="color:#009688!important"><b>Message: </b></label><br />
                     <p class="msgBody"><?php echo $msg->msgBody; ?></p>
                    </div>

                   <?php } }?>

             </div>
            <?php } elseif ($page==4) {  ?><!-- end if page==4 -->
              <div class="w3-panel w3-blue" id="dashTitle" style="width:300px;height:50px;" >
              Messages to students by me 
             </div>
             <div id="mentorSent">
                <?php
                    $msgByFac = MessageByFac::find_msgs_by_guid($mentor->employee_id);
                    if(empty($msgByFac)){
                      echo '<h5>No message to show</h5>';
                    }else { 
                    foreach ($msgByFac as $msg) { ?>
                    <div  class="w3-panel w3-border"  class="msgBox">
                     <label class="msgTop">To: <?php echo Student::find_student_name_by_EnrollNo($msg->to_enroll).' ('.$msg->to_enroll.')'; ?></label>
                     <label class="msgTop">Date: <?php echo fancy_dateTime($msg->msgTime); ?></label>                     <label class="msgTop">Subject: <?php echo $msg->msgSub; ?></label><br />
                     <label class="msgTop" style="color:#009688!important"><b>Message: </b></label><br />
                     <p class="msgBody"><?php echo $msg->msgBody; ?></p>
                    </div>

                   <?php } }?>
             </div>
            <?php } ?>
            <?php } ?><!-- end if session is logged in -->
        </div>
    </div>
</div>
 <div class="clear"></div>
 <div
  <?php if($page==0)echo 'id="footerDash" class="w3-blue">'; ?>
  <?php if($page==1)echo 'id="footer" class="w3-blue">'; ?>
  <?php if($page==2)echo 'id="footerDash" class="w3-blue w3-border-top" style="color:white;">'; ?> 
  <?php if($page==3)echo 'id="footerDash" class="w3-blue w3-border-top" style="color:white;">'; ?> 
  <?php if($page==4)echo 'id="footerDash" class="w3-blue w3-border-top" style="color:white;">'; ?> 
     <b>Galgotias University</b> <br />
    Plot No.2, Sector 17-A, Yamuna Expressway,<br />
Greater Noida, Gautam Buddh Nagar,<br />
Uttar Pradesh, India<br />
Call: 0120-4370000
 </div>

 </div> <!-- container div -->
 </body>
 </html>