<?php
include 'header.php';
?>
<?php
if (!$session->is_logged_in()) {
    redirect_to("index.php?redirect_to={$_SERVER['REQUEST_URI']}");
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
                          <li class="dashMystudWelcomeCR">Welcome, <br /><?php echo ucwords(strtolower($mentor->employee_name)); ?><br />
                             (<b><?php echo $mentor->employee_id; ?></b>)
                             <br />
                             <span>
                                You've been  logged in as Coordinator
                             </span>  
                          </li>
                          <?php 
                          $page = isset($_GET['p']) ? test_input($_GET["p"]) : 0;
                          if (isset($_GET['p'])) {
                                 $page = test_input($_GET["p"]);
                                 if ($page!=0) {
                                  if ($page==1||$page==2||$page==3||$page==4||$page==5||$page==6||$page==7||$page==8){$page=$page;}else $page = 0;
                                 }else $page = 0;                               
                          }else $page = 0;
                           ?>

                          <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="dashSideLink"><li class="dashMystud" <?php if($page==0)echo 'id="selected"'; ?>>My students</li></a>
                          <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?p=1" class="dashSideLink"><li class="dashMystud" <?php if($page==1)echo 'id="selected"'; ?>>My Profile</li></a>
                          <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?p=2" class="dashSideLink"><li class="dashMystud" <?php if($page==2)echo 'id="selected"'; ?>>Assign / change mentor</li></a>
                          <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?p=3" class="dashSideLink"><li class="dashMystud" <?php if($page==3)echo 'id="selected"'; ?>>Track mentor activity</li></a>
                          <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?p=4" class="dashSideLink"><li class="dashMystud" <?php if($page==4)echo 'id="selected"'; ?>>Contact a mentor</li></a>
                          <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?p=5" class="dashSideLink"><li class="dashMystud" <?php if($page==5)echo 'id="selected"'; ?>>Inbox<span style="font-size:12px;"> (msgs from mentors)</span></li></a>
                          <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?p=6" class="dashSideLink"><li class="dashMystud" <?php if($page==6)echo 'id="selected"'; ?>>
                          Sent<span style="font-size:12px;"> (msgs to students)</span></li></a>
                          <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?p=7" class="dashSideLink"><li class="dashMystud" <?php if($page==7)echo 'id="selected"'; ?>>Manage a student</li></a>                      
                          <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?p=8" class="dashSideLink"><li class="dashMystud" <?php if($page==8)echo 'id="selected"'; ?>>Add a student</li></a>                      
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
            <div class="w3-panel w3-blue" id="dashTitle" style="width:250px;height:50px;">
              Assign mentor to students
             </div>

             <div id="assignStud">
                  <select id="program_id" class="w3-select" style="width:200px;">
                  <?php
                  $pid=0;
                  $sid=0;
                  $yid=0;
                  $programs = Program::find_all_programs();
                  foreach ($programs as $p) {
                  if($pid>1&&$pid==$p->id){
                  ?>
                  <option value="<?php echo $p->id; ?>" selected><?php echo $p->course_name; ?></option>
                  <?php
                  }else{
                  ?>  
                  <option value="<?php echo $p->id; ?>"><?php echo $p->course_name; ?></option>

                  <?php 
                  }//end if-else
                  }//for each end
                  ?>
                  </select>

                  <select id="year_id" class="w3-select" style="width:200px;">
                  <?php
                   if ($yid==0){
                    echo '<option value="0" selected>All</option>';
                   }else {
                    echo '<option value="0">All</option>';
                   }
                  $years = Year::find_all_years();
                  foreach ($years as $y) {
                  // $years[$y->id] = $y->session;
                  if($yid>1&&$yid==$y->id){
                  ?>
                  <option value="<?php echo $y->id; ?>" selected><?php echo $y->session; ?></option>
                  <?php
                  }else{
                  ?>  
                  <option value="<?php echo $y->id; ?>"><?php echo $y->session; ?></option>

                  <?php 
                  }//end if-else
                  }//for each end
                  ?>
                  </select>

                  <select id="sem_id" class="w3-select" style="width:200px;">
                  <?php 
                  if ($sid==0){
                    echo '<option value="0" selected>All</option>';
                  }else {
                    echo '<option value="0">All</option>';
                 }
                  $sems = Sem::find_all_sems();
                  foreach ($sems as $s) {
                  // $semarray[$s->id] = $s->sem_name;
                  if($sid>=1&&$sid==$s->id){
                  ?>
                  <option value="<?php echo $s->id; ?>" selected><?php echo $s->sem_name; ?></option>
                  <?php
                  }else{
                  ?>  
                  <option value="<?php echo $s->id; ?>"><?php echo $s->sem_name; ?></option>
                  <?php 
                  }//end if-else
                  }//for each end
                  ?>
                  </select>
                  <input type="button" id="fetchStud" value="Fetch" class="w3-button " />
                  <span id="pag_flash" style="visibility: hidden;"><img  width="32" height="32" src="/mentor/style/comment.gif" /></span>
                  <div id="fetchedData">
                  </div>
                    <div id="my_popup">
                      <button class="my_popup_close">Close</button>
                        <div id="insidePopup" >
                           <label id="setting_studEnroll"></label>
                          <label id="setting_studName" class="w3-text"></label>
                          <div id="settingData"></div>
                          <div id="setting_pag_flash" style="visibility: hidden;"></div>
                       </div>                            
                  </div>
            </div>

            <?php } elseif ($page==3) { ?><!-- end if page==2 -->

            <div class="w3-panel w3-blue" id="dashTitle" style="width:300px;" >
              Track mentor activity
             </div>
             <div id="trackMentor">
                    <select id="trackMid" class="w3-select" style="width:200px;">
                    <?php
                     echo '<option value="0" selected>Select mentor:</option>';
                    $mentors = Mentor::find_all_mentors();
                    // $mid=0;
                    foreach ($mentors as $m) {
                    // $years[$y->id] = $y->session;
                    //if($mid!=1&&$mid==$m->id){
                    ?>
                    <option value="<?php echo $m->employee_id; ?>"><?php echo $m->employee_name; ?></option>
                    <?php 
                    }//for each end
                    ?>
                    </select>
                    <input type="button" id="trackFetch" value="Fetch" class="w3-button " />
                    <div id="track_pag_flash" style="visibility:hidden;"></div>

                    <div id="trackData"></div>
                  

             </div>
              
            <?php  } elseif ($page==4) { ?><!-- end if page==3 -->
                    <div class="w3-panel w3-blue" id="dashTitle" >
                        Contact a mentor
                       </div>
                       <div id="contactCr">
                        <h5>Here, You as the Coordinator of Mentors-Mentees may contact any mentor by selecting him/her below.</h5>
                        <!-- fist select mentor  -->

                          <select id="contMentorId" class="w3-select" style="width:200px;">
                              <?php
                               echo '<option value="0" selected>Select mentor:</option>';
                              $mentors = Mentor::find_all_mentors();
                              // $mid=0;
                              foreach ($mentors as $m) {
                              // $years[$y->id] = $y->session;
                              //if($mid!=1&&$mid==$m->id){
                              ?>
                              <option value="<?php echo $m->employee_id; ?>"><?php echo $m->employee_name; ?></option>
                              <?php 
                              }//for each end
                              ?>
                        </select>

                        <br />
                        <br />

                        <!-- then fill email info to send -->
                          <label class="w3-text">Subject:</label>
                          <input class="w3-input w3-border" type="text" id="contMentorSub" value="" style="width:60%;"></input>
                          <br />
                          <label class="w3-text">Message:</label>
                          <textarea class="w3-input w3-border" type="text" id="contMentorBody" value="" rows="10" style="width:60%;"></textarea>
                          <br /><br />
                          <input type="button" value="Send" id="contMentorSend" class="w3-button" style="width:60%;background-color: #F7B733" />
                          <br />
                          <div id="pag_flash_cc" style="visibility: hidden;"><img  width="32" height="32" src="/mentor/style/comment.gif" /></div>
                    </div>
             
            <?php } elseif ($page==5) {  ?><!-- end if page==4 -->
             <div class="w3-panel w3-blue" id="dashTitle" style="width:300px;height:60px;" >
              Messages from mentors to me
             </div>
             <div id="mentorInbox">
              <?php
                    $msgtoCr = MessageToCR::find_all_messages();
                    if(empty($msgtoCr)){
                      echo '<h5>No message to show</h5>';
                    }else { 
                    foreach ($msgtoCr as $msg) { ?>
                    <div  class="w3-panel w3-border"  class="msgBox">
                     <label class="msgTop">From: <?php echo Mentor::find_mentor_name($msg->from_guid); ?></label>
                     <label class="msgTop">Date: <?php echo fancy_dateTime($msg->msgTime); ?></label>                     <label class="msgTop">Subject: <?php echo $msg->msgSub; ?></label><br />
                     <label class="msgTop" style="color:#009688!important"><b>Message: </b></label><br />
                     <p class="msgBody"><?php echo $msg->msgBody; ?></p>
                    </div>

                   <?php } }?>

             </div>
              
            <?php }elseif ($page==6) {  ?><!-- end if page==5 -->
              <div class="w3-panel w3-blue" id="dashTitle" style="width:300px;height:50px;" >
                  Message sent to students by me 
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

            <?php } elseif ($page==7) { ?><!-- end if page==6 -->
            <div class="w3-panel w3-blue" id="dashTitle" style="width:300px;height:50px;" >
                  Manage a student
                 </div>
                 <div id="manageStudent">
                          <div id="manageFields">
                            <label class="w3-text">Enroll no / Admission no.</label>
                            <input class="w3-input w3-border" type="text" id="manageEnroll" value="" />
                            <input type="button" value="Submit" id="manageSubmit" class="w3-button" style="background-color: #F7B733" />
                            <span id="manage_flash_cc" style="visibility: hidden;"></span>
                          </div>
                          <div id="manageSudDetails">
                            
                          </div>
                          <div id="my_popup">
                      <button class="my_popup_close">Close</button>
                        <div id="insidePopup" >
                           <!-- <label id="setting_studEnroll"></label>
                          <label id="setting_studName" class="w3-text"></label> -->
                          <div id="manageData"></div>
                          <div id="manage_pag_flash" style="visibility: hidden;"></div>
                       </div>                            
                  </div>
             </div>

            <?php } elseif ($page==8){ ?> 
             <div class="w3-panel w3-blue" id="dashTitle" style="width:300px;height:50px;" >
                  Add a new student
              </div>
                 <div id="AddStudent">
                          <div id="AddFields">
                            <ul class="w3-ul w3-large" style="width:60%">
                              <li>
                                <label style="display: inline;">Enrollment no.: </label> 
                                <input style="display: inline;width:300px" class="w3-input w3-border" type="text" id="addStudEnroll" value="" />
                              </li>
                              <li>
                                <label style="display: inline;">Admission no.: </label> 
                                <input style="display: inline;width:300px" class="w3-input w3-border" type="text" id="addStudAdms" value="" />
                              </li>
                              <li>
                                <label style="display: inline;">Name: </label> 
                                <input style="display: inline;width:300px" class="w3-input w3-border" type="text" id="addStudName" value="" />
                              </li>
                              <li>
                                <label style="display: inline;">Course: </label> 
                                <select id="add_program_id" class="w3-select" style="width:300px;">
                                    <?php
                                  $programs = Program::find_all_programs();
                                  foreach ($programs as $p) 
                                    echo '<option value="'.$p->id.'">'.$p->course_name.'</option>';
                                  ?>
                                </select>
                              </li>
                              <li>
                                <label style="display: inline;">Sem: </label> 
                                <select id="add_sem_id" class="w3-select" style="width:300px;">
                                    <?php
                                  $sems = Sem::find_all_sems();
                                  foreach ($sems as $s) 
                                    echo '<option value="'.$s->id.'">'.$s->sem_name.'</option>';
                                  ?>
                                </select>
                              </li>
                              <li>
                                <label style="display: inline;">Session: </label> 
                                <select id="add_session_id" class="w3-select" style="width:300px;">
                                    <?php
                                  $years = Year::find_all_years();
                                  foreach ($years as $y) 
                                    echo '<option value="'.$y->id.'">'.$y->session.'</option>';
                                  ?>
                                </select>
                              </li>
                              <li>
                                <label style="display: inline;">Email: </label> 
                                <input style="display: inline;width:300px" class="w3-input w3-border" type="text" id="addStudEmail" value="" />
                              </li>
                              <li>
                                 <label style="display: inline;">Mentor: </label> 
                                 <select id="addStudMentorID" class="w3-select" style="width:300px;">
                                  <?php
                                    $mentors = Mentor::find_all_mentors();
                                     foreach ($mentors as $m)
                                      echo '<option value="'.$m->employee_id.'">'.$m->employee_name.'</option>';                 
                                   ?>
                                  </select>
                              </li>
                            </ul>
                            
                            <br />
                            <input type="button" value="Submit" id="addStudSubmit" class="w3-button" style="background-color: #F7B733" />
                            <span id="add_flash_cc" style="visibility: hidden;"></span>                         
                  </div>
             </div>

             <?php } ?><!-- end else -->
            <?php } ?><!-- end if session is logged in -->
        </div>
    </div>
</div>
 <div class="clear"></div>
 <div
  <?php if($page==0)echo 'id="footerDash" class="w3-blue">'; ?>
  <?php if($page==1)echo 'id="footer" class="w3-blue">'; ?>
  <?php if($page==2)echo 'id="footerDash" class="w3-blue w3-border-top" style="color:white;">'; ?> 
  <?php if($page==3)echo 'id="footerDash" class="w3-blue">'; ?>
  <?php if($page==4)echo 'id="footerDash" class="w3-blue w3-border-top" style="color:white;">'; ?> 
  <?php if($page==5)echo 'id="footerDash" class="w3-blue w3-border-top" style="color:white;">'; ?>
  <?php if($page==6)echo 'id="footerDash" class="w3-blue w3-border-top" style="color:white;">'; ?>
  <?php if($page==7)echo 'id="footerDash" class="w3-blue w3-border-top" style="color:white;">'; ?>
  <?php if($page==8)echo 'id="footerDash" class="w3-blue w3-border-top" style="color:white;">'; ?>
     <b>Galgotias University</b> <br />
    Plot No.2, Sector 17-A, Yamuna Expressway,<br />
Greater Noida, Gautam Buddh Nagar,<br />
Uttar Pradesh, India<br />
Call: 0120-4370000
 </div>

 </div> <!-- container div -->
 </body>
 </html>