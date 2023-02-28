<?php
switch($_POST["FormType"]){
    case "Query":

        $inputs = array("fname" => $fname, "lname" => "$lname", "countryCode" => "$ccode", "mob" => "$mobile", "email" => "$email", "country" => "$country", "address" => "$address", "gender" => "$gender");
    
        //Check for null input values
        foreach($inputs as $i => $i_value){
            if ($i_value == "") {
                die("notice: $i");
            }
        }
        
        //Validate inputs
        $domains = array('gmail.com', 'outlook.com', 'yahoo.in', 'yahoo.com', 'hotmail.com');
        $pattern = "/^[a-z0-9._%+-]+@[a-z0-9.-]*(" . implode('|', $domains) . ")$/i";
    
        if(ctype_alpha($fname) === false){
            print_r("failure: Invalid First name");
        } else if (ctype_alpha(str_replace(' ', '', $lname)) === false) {
            print_r("failure: Invalid Last name");
        } else if (strlen($fname) > 80 || strlen($lname) > 120) {
            print_r("failure: Provided names length exceeded");
        } else if (!preg_match('/^[0-9]{10}+$/', $mobile)) {
            print_r("failure: Invalid mobile number");
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            print_r("failure: Invalid email address");
        } else if (!preg_match($pattern, $email)) {
            print_r("failure: Allowed gmail, yahoo, outlook");
        } else if (strlen($additional) > 500) {
            print_r("failure: Additional into length exceeded");
        } else {
        
            //Final checking
            $address = str_replace('"', '``', str_replace("'", "`", $address));
            if ($additional != "") { $additional = str_replace('"', '``', str_replace("'", "`", $additional)); }
            
            //reCAPTCHA
            $recaptcha = $_POST['recaptcha_response'];
            $secret_key = 'secret-key';
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret='. $secret_key . '&response=' . $recaptcha;
            $response = file_get_contents($url);
            $response = json_decode($response);
            if ($response->success == false) {
                print_r("failure: Solve reCAPTCHA"); 
            } else {
            
                //Push mail
                $to = "info@webmonk.solutions";
                $subject = "Successfully submitted";
                $txt = "Your query has been submitted successfully.";
                $headers = "From: noreplay@bboysdreamsfell.tech" . "\r\n" . "CC: noreplay@bboysdreamsfell.tech";
                mail($to,$subject,$txt,$headers) ? print_r("success: Mail sent (check spam)") : print_r("failure: Please try again");
                
            }
        }
        break;
    default:
        die("Not allowed");
    break;
}
?>
