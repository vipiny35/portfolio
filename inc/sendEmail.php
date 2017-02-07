<?php

	//Email address to receive email
	$siteOwnersEmail = 'vipiny35@gmail.com';

	$secret = "6LfAkhQUAAAAANHjbDttdaC0MJxU_ol7dtCLgbRF";
	$user_ip = $_SERVER['REMOTE_ADDR'];

	if(isset($_POST['g-recaptcha-response'])){
		$response = $_POST['g-recaptcha-response'];
		// echo "GOT response.<br/>";
	}
	else{
		// echo "No reCAPTCHA response.<br/>";
	}

    //Verify response data
    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$user_ip");
    
    $responseData = json_decode($verifyResponse);
    if(!$responseData->success){
   		$error['captcha'] = "Please prove that you're not a robot."; 	
    }
   

	if($_POST) {

		$name = trim(stripslashes($_POST['contactName']));
		$email = trim(stripslashes($_POST['contactEmail']));
		$subject = trim(stripslashes($_POST['contactSubject']));
		$contact_message = trim(stripslashes($_POST['contactMessage']));


		// Check Name
		if (strlen($name) < 2) {
			$error['name'] = "Please enter your name.";
		}
		// Check Email
		if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
			$error['email'] = "Please enter a valid email address.";
		}
		// Check Message
		if (strlen($contact_message) < 15) {
			$error['message'] = "Your message should have at least 15 characters.";
		}

	   	// Subject
		if ($subject == '') { $subject = "Contact Form Submission"; }

	   	// Set Message
	   	$message .= "Email from: " . $name . "<br />";
		$message .= "Email address: " . $email . "<br /><br />";
	   	$message .= "Message: <br />";
	   	$message .= $contact_message;
	   	$message .= "<br /><br /><br /><br /><br /><br /> This email was sent from vipiny.com's contact form. <br />";

		// Set From: header
		$from =  $name . " <" . $email . ">";

		// Email Headers
		$headers = "From: " . $from . "\r\n";
		$headers .= "Reply-To: ". $email . "\r\n";
	 	$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


	   if (!$error) {
		    ini_set("sendmail_from", $siteOwnersEmail); // for windows server
		    $mail = mail($siteOwnersEmail, $subject, $message, $headers);

			if ($mail) { echo "OK. Email sent!"; }
		    else { echo "Something went wrong. Please try again."; }
		} # end if - no validation error

		else {

			$response .= (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
			$response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
			$response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;
			$response .= (isset($error['captcha'])) ? $error['captcha'] . "<br />" : null;
			
			echo $response;

		} # end if - there was a validation error

	}

?>
