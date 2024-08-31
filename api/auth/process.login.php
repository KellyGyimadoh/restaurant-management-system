<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../../includes/sessions.php");

/*
if(isset($_POST['submit'])&& $_SERVER['REQUEST_METHOD']=="POST"){
    $email=filter_input(INPUT_POST,"email",FILTER_SANITIZE_EMAIL);
    $password=filter_input(INPUT_POST,"password",FILTER_SANITIZE_SPECIAL_CHARS);
    require_once("../../includes/configdb/Dbconnection.php");
    require_once("../../includes/configdb/Login.php");
    $loginuser=new Login($email,$password);
    $loginuser->allowUserLogin();
}else{
    header("Location:../../auth/index.php");
   
    die();
}
*/

//using api and json
header("Content-Type: application/json");
//retrieve raw post data
try{
$input= json_decode(file_get_contents('php://input'),true);
if (json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception('Invalid JSON input');
}
$email=filter_var($input['email'],FILTER_SANITIZE_EMAIL);
$password=filter_var($input['password'],FILTER_SANITIZE_SPECIAL_CHARS);

if(empty($email)|| empty($password)){
    echo json_encode(['success'=>false,'message'=>'fill all input']);
    exit();
} 
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configdb/Login.php");
$loginuser=new Login($email,$password);
$result= $loginuser->allowUserLogin();

    //send json result login
    if($result){
       
echo json_encode($result);
    }
}catch(Exception $e){
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}


/*function jsResponse($type,$message){
    $account= htmlspecialchars(isset($_SESSION['accounttype']) ? $_SESSION['accounttype'] : "staff");
return json_encode([$type=>true,
                    "message"=> $message,
                    "account"=>$account
]);
}*/