<?php
switch($_POST["FormType"]){
    case "Query":
            PrintShastra::Query(''.$_POST['fname'].'', ''.$_POST['lname'].'', ''.$_POST['mob'].'', ''.$_POST['email'].'', ''.$_POST['countryCode'].'', ''.$_POST['country'].'', ''.$_POST['address'].'',''.$_POST['gender'].'',''.$_POST['additional'].'');
        break;
    default:
        die("Not allowed");
        break;
}

class PrintShastra{
    public static function Query($fname, $lname, $mobile, $email, $ccode, $country, $address, $gender, $additional){
        try {
            if (! @include_once("query.php")){
                throw new Exception ('Contact site admin');
            } else {
                @include_once('query.php');
            }
        } catch (Exception $e){
            print($e->getMessage());
        }
    }
}
?>
