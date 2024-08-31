<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configdbcust/Ordercustomers.php");
require_once("../../includes/ordercustomercontroller/Insertordercustomer.php");

//using api and json
header("Content-Type: application/json");
//retrieve raw post data
try{
$input= json_decode(file_get_contents('php://input'),true);
if (json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception('Invalid JSON input');
}
$firstname=filter_var($input['firstname'],FILTER_SANITIZE_SPECIAL_CHARS);
$lastname=filter_var($input['lastname'],FILTER_SANITIZE_SPECIAL_CHARS);
$email=filter_var($input['email'],FILTER_SANITIZE_EMAIL);
$loyaltypoint=filter_var($input['loyaltypoint'],FILTER_SANITIZE_SPECIAL_CHARS);
$phone=filter_var($input['phone'],FILTER_SANITIZE_SPECIAL_CHARS);


$loginuser=new Insertordercustomers($firstname,$lastname,$email,$phone,$loyaltypoint);
$result= $loginuser->registerOrderCustomer();

    //send json result login
 if($result){
       
echo json_encode($result);
    }
}catch(Exception $e){
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}


