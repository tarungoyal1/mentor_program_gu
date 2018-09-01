<?php
if ($_SERVER['REQUEST_METHOD']=="GET") {
	header("Location: error.php");
	exit();
}
?>
<?php
require_once ('../includes/session.php');
if (!$session->is_logged_in()) {
    die("You have been logged out. Couldn't send message!");
    //redirect_to("index.php?redirect_to={$_SERVER['REQUEST_URI']}");
}
require_once ('../includes/config.php');
require_once ('../includes/database.php');
require_once ('../includes/functions.php');
require_once ('../includes/mentor.php');
require_once ('../includes/student.php');
require_once ('../includes/messagebyfac.php');
require '../includes/PHPMailer-master/PHPMailerAutoload.php';
?>
<?php
$mentor_id = isset($_POST['mid']) ? test_input($_POST['mid']): die('Please try again!');
$stud_enroll = isset($_POST['studEnroll']) ? test_input($_POST['studEnroll']): die('Please try again!');
$email_body = isset($_POST['mbody']) ? test_input($_POST['mbody']): die('Please try again!');
$email_sub = isset($_POST['mSub']) ? test_input($_POST['mSub']): die('Please try again!');

if (is_null($mentor_id)||is_null($stud_enroll)||is_null($email_body)||is_null($email_sub))die('Please try again! - Maybe some fields left blank!');
if ($mentor_id==''||$stud_enroll==''||$email_body== ''||$email_sub== '')die('Please try again!- Maybe some fields left blank!');
if (strlen($email_sub)>255)die('Subject body exceeds maximum limit of 255 characters.'); 
if (strlen($email_body)>1000)die('Email body exceeds maximum limit of 1000 characters.');

$ip = trim($_SERVER['REMOTE_ADDR']);
$dtstamp = trim(strftime("%Y-%m-%d %H:%M:%S", time()));

if(Mentor::validate_mentor($mentor_id)){
    if (Student::student_exists_has_email($stud_enroll)) {

        $student = Student::find_student_by_EnrollNo($stud_enroll);
        $recipient = $student->email;

        $mail = new PHPMailer;
        $mail->isSMTP();               // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';             // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                     // Enable SMTP authentication
        $mail->Username = 'tarun13317@gmail.com';          // SMTP username
        $mail->Password = 'itibin@@bigbucks'; // SMTP password
        $mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                          // TCP port to connect to
        
        $mail->setFrom('tarun13317@gmail.com', 'Tarun');
        // $mail->addReplyTo('info@example.com', 'CodexWorld');
     
        

        $mail->addAddress($recipient);   // Add a recipient
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        $mail->isHTML(true);  // Set email format to HTML

        $bodyContent = '<h5>This e-mail is from your mentor at Galgotias University </h5>';
        $bodyContent .= '<p>To : '.$student->Student_Name.'</p>';
        $bodyContent .= '<p>'.$email_body.'</p>';

        $mail->Subject = $email_sub;
        $mail->Body    = $bodyContent;

        if(!$mail->send()) {
            try {
               MessageByFac::insert_msg_by_mentor($mentor_id, $stud_enroll, $email_sub, $email_body, "failed", $dtstamp, $ip);
            } catch (Exception $e) {}
            die("error||Error: Message could not be sent !");
        } else {

            try {
               MessageByFac::insert_msg_by_mentor($mentor_id, $stud_enroll, $email_sub, $email_body, "sent", $dtstamp, $ip);
            } catch (Exception $e) {}
            die("Message has been sent");

        }
    }else die("Student has no e-mail.");
}else die("Mentor doesn't exists.");

?>

<!--<label class="w3-text">Subject:</label>
                          <input class="w3-input w3-border" type="text" id="emailSub" value="" style="width:80%;"></input>
                           
                          <label class="w3-text">Message:</label>
                          <textarea class="w3-input w3-border" type="text" id="emailBody" value="" rows="10" style="width:80%;"></textarea>
                          <br />
                          <input type="hidden" id="mid" value="<?php echo $mentor->employee_id;  ?>">
                          <input type="hidden" id="enroll" value="">
                          <input type="button" value="Send" id="sendMail" class="w3-button" style="width:80%;background-color: #F7B733" />
                          <br /> -->