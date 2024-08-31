<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configdbcust/Ordercustomers.php");
require_once("../../includes/ordercustomercontroller/Insertnull.php");

//using api and json
header("Content-Type: application/json");
//retrieve raw post data
try{
$input= json_decode(file_get_contents('php://input'),true);
if (json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception('Invalid JSON input');
}

$customerid=filter_var($input['customerid'],FILTER_SANITIZE_SPECIAL_CHARS);

if($customerid=='noname'){
    $firstname='null';
    $lastname=$email=$phone='';
    $loyaltypoint=1;
   
    
    
    $loginuser=new Insertnull($firstname,$lastname,$email,$phone,$loyaltypoint);
    $result= $loginuser->registerOrderCustomer();
    if($result){
       
        echo json_encode($result);
            }
}


    //send json result login
 
}catch(Exception $e){
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}


