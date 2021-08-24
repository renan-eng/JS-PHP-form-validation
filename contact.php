<?php

//variables
$to = 'exemple@exemple.com';
$subject = 'Menssage from your website domain.com';

$error_open = "<label class='error'>";
$error_close = "</label>";
$valid_form = TRUE;
$redirect = "success.php";

$form_elements = array('name', 'phone', 'fax', 'email', 'comments');
$required = array('name', 'phone', 'email');

if (isset($_POST['submit'])) {
    //proccess form
    //get form data
    foreach ($form_elements as $element){
        $form[$element] = htmlspecialchars($_POST[$element]);       
    }

    //check form elements
    //chek required fields are not empty
    if ($form['name'] == ''){
        $error['name'] = $error_open.'Please fill in all requiered fields'.$error_close;
        $valid_form = FALSE;
    }
    if ($form['phone'] == ''){
        $error['phone'] = $error_open.'Please fill in all requiered fields'.$error_close;
        $valid_form = FALSE;
    }
    if ($form['email'] == ''){
        $error['email'] = $error_open.'Please fill in all requiered fields'.$error_close;
        $valid_form = FALSE;
    }
    //check formatting for phone and email fields
    if ($error['phone'] == '' && !preg_match('/^(\+?1-?)?(\([2-9]([02-9]\d|1[02-9])\)|[2-9]([02-9]\d|1[02-9]))-?[2-9]\d{2}-?\d{4}$/',$form['phone'])){
        $error['phone'] = $error_open.'Please enter a valid phone number'.$error_close;
        $valid_form = FALSE;
    }
    if ($error['email'] == '' && !preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',$form['email'])){
        $error['email'] = $error_open.'Please enter a valid email'.$error_close;
        $valid_form = FALSE;
    }

    //check for bad data
    if(contains_bad_str($form['name']) || contains_bad_str($form['phone']) || contains_bad_str($form['email']) || contains_bad_str($form['fax']) || contains_bad_str($form['comments'])){
        $valid_form = FALSE;
    }
    if(contains_newlines($form['name']) || contains_newlines($form['phone']) || contains_newlines($form['email']) || contains_newlines($form['fax'])){
        $valid_form = FALSE;
    }

    //check if form is valid
    if ($valid_form){
        //creating email message
        $message = 'Name: '.$form['name'].'\n';
        $message .= 'Phone: '.$form['phone'].'\n';
        $message .= 'Email: '.$form['email'].'\n';
        $message .= 'Fax: '.$form['fax'].'\n';
        $message .= 'Message: '.$form['comments'].'\n';

        $headers = "From: www.exemple.com <admin@example.com>\r\n";
        $headers .= 'X-Sender: <admin@exemple.com>\r\n';
        $headers .= 'X-Mailer: PHP/'.phpversion().'\r\n';
        $headers .= 'Replay-To: '. $form['email'];
        
        //send email
        mail($to, $subject, $message, $headers);
        header('Location: '.$redirect);

        //redirect
        header('Location: '.$redirect);
    } else {
        include('form.php');
    }
} else {
    include('form.php');
}

function contains_bad_str($str_to_test) {
	$bad_strings = array(
		"content-type:",
		"mime-version:",
        "multipart/mixed",
		"Content-Transfer-Encoding:",
        "bcc:",
		"cc:",
		"to:");
  
	foreach($bad_strings as $bad_string) {
		if(stristr(strtolower($str_to_test), $bad_string)) {
      		return true;
    	}
  	}
  	return false;
}

function contains_newlines($str_to_test) {
   if(preg_match("/(%0A|%0D|\\n+|\\r+)/i", $str_to_test) != 0) {
     return true;
   }
   return false;
}

?>