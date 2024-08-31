<?php
require("../../includes/sessions.php");
if(isset($_POST['submit'])&& $_SERVER['REQUEST_METHOD']==="POST"){
    
    $fname=filter_input(INPUT_POST,"fname",FILTER_SANITIZE_SPECIAL_CHARS);
    $lname=filter_input(INPUT_POST,"lname",FILTER_SANITIZE_SPECIAL_CHARS);
    $password=filter_input(INPUT_POST,"password",FILTER_SANITIZE_SPECIAL_CHARS);
    $rptpassword=filter_input(INPUT_POST,"rptpassword",FILTER_SANITIZE_SPECIAL_CHARS);
    $email=filter_input(INPUT_POST,"email",FILTER_SANITIZE_EMAIL);
    $phone=filter_input(INPUT_POST,"phone",FILTER_SANITIZE_SPECIAL_CHARS);
    
    //put into constructor
    require_once("../../includes/configdb/Dbconnection.php");
    require_once("../../includes/configdb/Signup.php");
    $newUser= new Signup($fname,$lname,$password,$rptpassword,$email,$phone);
    $newUser->registerUser();
       
    

}else{
    header("Location:../../auth/index.php");
    die();
}