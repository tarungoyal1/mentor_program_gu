<?php
include 'header.php';
?>
<?php
if ($session->is_logged_in()) {
    if (strtolower($session->user_id)==strtolower($crguid)) {
        redirect_to("crdash.php");        
    }else{
        redirect_to("dashboard.php");
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 if(isset($_POST['flogin'])){
 	$fac_guid = isset($_POST['login_guid']) ? test_input($_POST["login_guid"]) : null;	
	$fac_pass = isset($_POST['login_pass']) ? test_input($_POST["login_pass"]) : null;
	   if (!is_null($fac_guid)&&!is_null($fac_pass)) {
    	  if (!$fac_guid==0&&!$fac_pass==0) {
    	  	 $mentor = Mentor::authenticate_mentor_login(strtolower($fac_guid),$fac_pass);
    	  	if($mentor!=null){
    	  		// $session->message("".$mentor['guid']);
                if (strtolower($mentor['guid'])!=strtolower($crguid)) {
                    $session->login($mentor);
                    redirect_to("dashboard.php");
                }else{
                    $session->login($mentor);
                    $crlogin=true;
                    redirect_to("crdash.php");
                }
    	  	}else{
    	  		$session->message("Incorrect details entered, try again!");
    		    redirect_to($_SERVER['REQUEST_URI']);
    	  	}
    	  }
       }
 	}
 }
 ?>
<div class="clear"></div>
<div id="homeContent">
    <div id="aboutContent" class="w3-border">
        <br /><br /><br /><b>
    		This is the online platform for Mentor-Mentee Program of Galgotias University.
            <p>
                Here, students and faculties of the university can communicate online and exchange ideas.
            </p>
        </b>
    </div>
	<div id="loginForm" class="w3-border">
<h5 id="flTitle" class="w3-text"><b>Faculty Login</b></h5>

	    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
			<label class="w3-text">Employee ID</label>
			<input class="w3-input w3-border" type="text" name="login_guid" value="" required>
			 
			<label class="w3-text">Password</label>
			<input class="w3-input w3-border" type="password" name="login_pass" value="" required>
            <?php if($session->message()!='')echo '<br /><span style="color:red;font-weight:bold;">'.$session->message().'</span><br />'; ?>
            <br />
			<input type="submit" name="flogin" value="Login" id="loginbtn" class="w3-button" style="background-color: #F7B733" />
			<br />
		</form>
    </div>
    <div id="signupBtn">
     
     <a href="/mentor/public/signup.php" class="w3-button" style="background-color: #FC4A1A;width: 100%;">Signup</a>	
    </div>
</div>
<?php
include 'footer.php';
?>