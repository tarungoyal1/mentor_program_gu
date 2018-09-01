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
require_once ('../includes/messagebycr.php');
require '../includes/PHPMailer-master/PHPMailerAutoload.php';
?>
<?php
$mentor_id = isset($_POST['mid']) ? test_input($_POST['mid']): die('Please try again!');
$email_body = isset($_POST['mbody']) ? test_input($_POST['mbody']): die('Please try again!');
$email_sub = isset($_POST['mSub']) ? test_input($_POST['mSub']): die('Please try again!');
// die($mentor_id);
if (is_null($mentor_id)||is_null($email_body)||is_null($email_sub))die('Please try again! - Maybe some fields left blank!');
if ($mentor_id==''||$email_body== ''||$email_sub== '')die('Please try again!- Maybe some fields left blank!');
if (strlen($email_sub)>255)die('Subject body exceeds maximum limit of 255 characters.'); 
if (strlen($email_body)>1000)die('Email body exceeds maximum limit of 1000 characters.');

$ip = trim($_SERVER['REMOTE_ADDR']);
$dtstamp = trim(strftime("%Y-%m-%d %H:%M:%S", time()));

if(Mentor::validate_mentor($mentor_id)){
        
        // uncomment following 3 lines of code in production
        // $mentor = Mentor::find_mentor_by_id($mentor_id); 
        // $recipient =  $mentor->email;
        // if (is_null($recipient)||$mentor_id=='')die('Mentor email id is either invalid or not found!');

        // comment or remove following 2 lines
       $mentor = Mentor::find_mentor_by_id($mentor_id);
       $recipient = "taruntube9@gmail.com";
  
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

        $bodyContent = '<h5>This e-mail is from Dr. Pallavi M. Goel at Galgotias University </h5>';
        $bodyContent .= '<p>To mentor: '.$mentor->employee_name.'</p>';
        $bodyContent .= '<p>'.$email_body.'</p>';

        $mail->Subject = $email_sub;
        $mail->Body    = $bodyContent;

        if(!$mail->send()) {
            try {
               MessageByCR::insert_msg_of_cr($mentor_id, $email_sub, $email_body, "failed", $dtstamp, $ip);
            } catch (Exception $e) {}
            die("error||Error: Message could not be sent !");
        } else {

            try {
               MessageByCR::insert_msg_of_cr($mentor_id, $email_sub, $email_body, "sent", $dtstamp, $ip);
            } catch (Exception $e) {}
            die("Message has been sent");

        }
}else die("Mentor doesn't exists.");

?>

