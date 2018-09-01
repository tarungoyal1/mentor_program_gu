<?php
include 'header.php';
if ($session->is_logged_in()) {
    redirect_to("index.php");
    exit();
}
$guid = isset($_GET['i']) ? test_input($_GET['i']) : null;
$token = isset($_GET['tid']) ? test_input($_GET['tid']) : null;

if (is_null($guid)||is_null($token))redirect_to("index.php");
if ($guid==0||$token==0)redirect_to("index.php");
if(strlen($guid)<10||strlen($guid)>14||strlen($token)<15||strlen($token)>18)redirect_to("index.php");
if(Creatementor::check_already_verified("GU".$guid))redirect_to("index.php");
if(!Creatementor::validate_mentor_token("GU".$guid,$token))redirect_to("index.php");

if(isset($_POST['pass_submit'])){
    $pass1 = isset($_POST['verify_pass']) ? test_input($_POST["verify_pass"]) : null;    
    $pass2 = isset($_POST['v_renter_pass']) ? test_input($_POST["v_renter_pass"]) : null;
     if($pass1==$pass2){
            if(strlen($pass1)>=6&&strlen($pass1)<=15){
                    if(Creatementor::update_choosen_password("GU".$guid,$token,$pass1)){
                   $session->message("Password saved successfuly.<br />");
                    redirect_to("index.php");
                     exit();
                }
            }else{
                $session->message("Password must contain atleast 6 and atmost 15 characters.<br />");
                redirect_to("verify.php"."?i=$guid"."&tid=".$token);
            }
    }else{
        $session->message("Password don't match.<br />");
        redirect_to("verify.php"."?i=$guid"."&tid=".$token);
    } 
}
?>
<div id="content">
<h2>Choose a password:</h2>
	<div id="facSignUpForm">
	<?php echo 	$session->message(); ?>
     <?php $url = htmlspecialchars($_SERVER['PHP_SELF']."?i=$guid"."&tid=".$token); ?>

		<form id="password_form" action="<?php echo $url; ?>" method="POST">
			<input type="hidden" name="lsd" value="AVomVKRH" autocomplete="off"><br />
			<label class="w3-text">Enter new password:</label>
			<input class="w3-input w3-border" type="password" name="verify_pass" value="" required />
			 <span class="check_flash"></span><span class="check"></span>
			<br />
			<label class="w3-text">Re-enter password:</label>
			<input class="w3-input w3-border" type="password" type="email" name="v_renter_pass" value="" placeholder="" required />
			<br />
			<input type="submit" name="pass_submit" value="Submit" id="facSignup" class="w3-button" style="background-color: #F7B733" />
			<br />
		</form>
			<br />

			<label class="w3-text">When you click Submit, You will be redirected to the homepage where you could login using your newly setup credentials.</label>

	</div>

</div>

<?php
include 'footer.php';
?>