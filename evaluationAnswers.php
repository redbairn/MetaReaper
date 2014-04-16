<?php
if(isset($_POST['email'])) {
     
    // EDIT THE 2 LINES BELOW AS REQUIRED
    $email_to = "craigdanielbell@gmail.com";
    $email_subject = "User Survey - MetaReaper";
     
header('Refresh: 6; URL=http://csserver.ucd.ie/~12253387/');

    function died($error) {
        // your error code can go here
        echo "We are very sorry, but there were error(s) found with the form you submitted. ";
        echo "These errors appear below.<br /><br />";
        echo $error."<br /><br />";
        echo "Please go back and fix these errors.<br /><br />";
        die();
    }
     
    // validation expected data exists
    if(!isset($_POST['question1']) ||
        !isset($_POST['question2']) ||
        !isset($_POST['question3']) ||
        !isset($_POST['question4']) ||
		!isset($_POST['question5']) ||
		!isset($_POST['question6']) ||
		!isset($_POST['question7']) ||
		!isset($_POST['question8']) ||
		!isset($_POST['question9']) ||
		!isset($_POST['fullname']) ||
		!isset($_POST['email']) ||
        !isset($_POST['comment'])) {
        died('We are sorry, but there appears to be a problem with the form you submitted.');       
    }
     
    $q_1 = $_POST['question1']; // required
    $q_2 = $_POST['question2']; // required
    $q_3 = $_POST['question3']; // required
    $q_4 = $_POST['question4']; // required
	$q_5 = $_POST['question5']; // required
	$q_6 = $_POST['question6']; // required
	$q_7 = $_POST['question7']; // required
	$q_8 = $_POST['question8']; // required
	$q_9 = $_POST['question9']; // required
	$full_name = $_POST['fullname']; // required
	$email_from = $_POST['email']; // required
    $comment = $_POST['comment']; // required
     
    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
  if(!preg_match($email_exp,$email_from)) {
    $error_message .= 'The Email Address you entered does not appear to be valid.<br />';
  }
    $string_exp = "/^[A-Za-z .'-]+$/";
  if(!preg_match($string_exp,$full_name)) {
    $error_message .= 'The First Name you entered does not appear to be valid.<br />';
  }
  if(strlen($comment) < 2) {
    $error_message .= 'The Comments you entered do not appear to be valid.<br />';
  }
  if(strlen($error_message) > 0) {
    died($error_message);
  }
    $email_message = "Form details below.\n\n";
     
    function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }
     
   
	$email_message .= "Question 1: ".clean_string($q_1)."\n";
	$email_message .= "Question 2: ".clean_string($q_2)."\n";
	$email_message .= "Question 3: ".clean_string($q_3)."\n";
	$email_message .= "Question 4: ".clean_string($q_4)."\n";
	$email_message .= "Question 5: ".clean_string($q_5)."\n";
	$email_message .= "Question 6: ".clean_string($q_6)."\n";
	$email_message .= "Question 7: ".clean_string($q_7)."\n";
	$email_message .= "Question 8: ".clean_string($q_8)."\n";
	$email_message .= "Question 9: ".clean_string($q_9)."\n";
	$email_message .= "Full Name: ".clean_string($full_name)."\n";
    $email_message .= "Email: ".clean_string($email_from)."\n";
    $email_message .= "Comment: ".clean_string($comment)."\n";
     
     
// create email headers
$headers = 'From: '.$email_from."\r\n".
'Reply-To: '.$email_from."\r\n" .
'X-Mailer: PHP/' . phpversion();
@mail($email_to, $email_subject, $email_message, $headers);
?>


<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Evaluation</title>
    <link href="boilerplate.css" rel="stylesheet" type="text/css">
    <link href="fluidlayout.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="gridContainer clearfix">
    <div id="evaluation">
        <h1>User Evaluation Answers</h1>
      	Thanks <?php echo $_POST["fullname"]; ?>!<br />
        Your email is <strong><?php echo $_POST["email"]; ?>.</strong><br />
        Thank you for taking part in the <em>MetaReaper User Survey</em>.<br />
    </div>
    
    



</div>
</body>
</html>

<?php
}
?>
