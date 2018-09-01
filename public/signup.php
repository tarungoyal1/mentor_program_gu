<?php
$signup_page="";
include 'header.php';
require '../includes/PHPMailer-master/PHPMailerAutoload.php';
// require_once("../includes/PHPMailer-master/class.phpmailer.php");
// require_once("../includes/PHPMailer-master/class.smtp.php");
// require_once("../includes/PHPMailer-master/language/phpmailer.lang-en.php");
?>
<?php
if ($session->is_logged_in()) {
	redirect_to("index.php");
}
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 if(isset($_POST['facSignup'])){
	$fac_guid = isset($_POST['signup_guid']) ? test_input($_POST["signup_guid"]) : null;	
	$fac_email = isset($_POST['signup_email']) ? test_input($_POST["signup_email"]) : null;
    if (!is_null($fac_guid)&&!is_null($fac_email)) {
    	if (!$fac_guid==0&&!$fac_email==0) {
    		if (Creatementor::validate_mentor($fac_guid, $fac_email)) {
    			//mentor exists
    			//now check if mentor already is verified and active
    				$fid = substr($fac_guid, 2,strlen($fac_guid));
    				$lastsix = substr($fac_guid, -6);
    				$token = $lastsix.time();
    				$verifylink = "http://localhost/mentor/public/verify.php?i=".$fid."&tid=".$token;
    			if(!Creatementor::check_already_exists(strtolower($fac_guid))){
    				//this checks whether mentor exists in mentor_login table
    				//if not then it is a new signup and follow the code in if statement below

    				if(Creatementor::insert_mentor_verify_token(strtolower($fac_guid),$token)){
    					// echo "working";

    					// if true means token is genrated and a new entry with guid and token is inserted in mentor_login table
    						//send mail
                        $mail = new PHPMailer;
                        $mail->isSMTP();               // Set mailer to use SMTP
                        $mail->Host = 'smtp.gmail.com';             // Specify main and backup SMTP servers
                        $mail->SMTPAuth = true;                     // Enable SMTP authentication
                        $mail->Username = 'gumentormentee@gmail.com';          // SMTP username
                        $mail->Password = 'gumentormenteeh3llo2u'; // SMTP password
                        $mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
                        $mail->Port = 587;                          // TCP port to connect to

                        $mail->setFrom('gumentormentee@gmail.com', 'GU Mentor Mentee System');
                        // $mail->addReplyTo('info@example.com', 'CodexWorld');
                        //here add email address of mentor taken from mentor table
                        $mail->addAddress($fac_email);   // Add a recipient
                        // $mail->addCC('cc@example.com');
                        // $mail->addBCC('bcc@example.com');

                        $mail->isHTML(true);  // Set email format to HTML

                        $bodyContent = '<h4>This e-mail contains verification link of GU Mentor-Mentee Online</h4>';
                        $bodyContent .= '<a href="'.$verifylink.'">'.$verifylink.'</a>';

                        $mail->Subject = 'Verification';
                        $mail->Body    = $bodyContent;

                        if(!$mail->send()) {
                            $session->message("Something went wrong! try again.");
                            redirect_to($_SERVER['REQUEST_URI']);
                            // echo 'Message could not be sent.';
                            // echo 'Mailer Error: ' . $mail->ErrorInfo;
                        } else {
                            $session->message("Verification link has been sent to your GU email.");
                            redirect_to($_SERVER['REQUEST_URI']);
                        }

                    }
    	// 			$to_name = "Tarun Goyal";
    	// 			$to = "tarun13317@gmail.com";
    	// 			$subject = "VerifyMail";
    	// 			$message = "This is text";
    	// 			$from_name = "Kindsoul93";
    	// 			$from = "kindsoul93@yahoo.com";

    	// 			//
    	// 			$mail = new PHPMailer();
    	// 			$mail->setFrom($from, $from_name);
     //                $mail->addAddress($to, $to_name);
     //                $mail->isHTML(true);                                  // Set email format to HTML
     //                $mail->Subject = 'Here is the subject';
     //                $mail->Body    = 'This is the HTML message body <b>in bold!</b>';

     //                if(!$mail->send()) {
					// 	    echo 'Message could not be sent.';
					// 	    echo 'Mailer Error: ' . $mail->ErrorInfo;
					// } else {
					//     echo 'Message has been sent';
					// }
				
    			}else if(!Creatementor::check_already_verified(strtolower($fac_guid),$token)){
    			//user exists but not verified so update token with new mail with updated token
    			// just focus if statement inside
    				if(Creatementor::update_mentor_verify_token(strtolower($fac_guid),$token)){
    						//send mail with updated token
    						// do stuff here
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
                        //here add email address of mentor taken from mentor table
                        $mail->addAddress($fac_email);   // Add a recipient
                        // $mail->addCC('cc@example.com');
                        // $mail->addBCC('bcc@example.com');

                        $mail->isHTML(true);  // Set email format to HTML

                        $bodyContent = '<h4>This e-mail contains verification link of GU Mentor-Mentee Online</h4>';
                        $bodyContent .= '<a href="'.$verifylink.'">'.$verifylink.'</a>';

                        $mail->Subject = 'Verification';
                        $mail->Body    = $bodyContent;

                        if(!$mail->send()) {
                            $session->message("Something went wrong! try again.");
                            redirect_to($_SERVER['REQUEST_URI']);
                            // echo 'Message could not be sent.';
                            // echo 'Mailer Error: ' . $mail->ErrorInfo;
                        } else {
                            $session->message("Verification link has been sent to your GU email.");
                            redirect_to($_SERVER['REQUEST_URI']);
                        }

                    }
    		    }else{
    				// echo "Mentor is already there.";
    				$session->message("Mentor has already signed up.");
    				redirect_to($_SERVER['REQUEST_URI']);
    			}

    		}else{
    			$session->message("Mentor doesn't exists");
    			redirect_to($_SERVER['REQUEST_URI']);
    		}
    		
    	}else echo "Enter fields properly!";
    }else echo "Enter fields properly!";
 }
}
?>
<div id="content">
<h2><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">Faculty Signup</a></h2>
<label>Only SCSE faculty can register for now:</label>
	<div id="facSignUpForm">
	
		<form id="signup_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
			<input type="hidden" name="lsd" value="AVomVKRH" autocomplete="off"><br />
			<label class="w3-text">Faculty GU ID:</label>
			<input class="w3-input w3-border" type="text" name="signup_guid" value="" autocomplete="off" required />
			 <span class="check_flash"></span><span class="check"></span>
			<br />
			<label class="w3-text">Faculty registered email:</label>
			<input class="w3-input w3-border" type="text" type="email" name="signup_email" value="" placeholder="" autocomplete="off" required />
              <?php if($session->message()!='')echo '<br /><span style="color:red;font-weight:bold;">'.$session->message().'</span><br />'; ?>
			<br />
			<input type="submit" name="facSignup" value="Verify Me" id="facSignup" class="w3-button" style="background-color: #F7B733" />
			<br />
		</form>
			<br />

			<label class="w3-text">When you click Verify Me, verification link will be sent to your entered email, after successfully clicking that link, you will have to choose a desired password to login, offcourse you can change your password anytime.</label>

	</div>

</div>

<?php
include 'footer.php';
?>