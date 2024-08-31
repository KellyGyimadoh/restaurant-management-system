<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configshop/Shop.php");
require_once("../../includes/controlshop/Updateshop.php");

if($_SERVER['REQUEST_METHOD']=="POST"){
//using api and json
header("Content-Type: application/json");
//retrieve raw post data
try{
$input= json_decode(file_get_contents('php://input'),true);
if (json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception('Invalid JSON input');
}
$shopid=filter_var($input['id'],FILTER_SANITIZE_NUMBER_INT);
$name=filter_var($input['shopname'],FILTER_SANITIZE_SPECIAL_CHARS);
$email=filter_var($input['email'],FILTER_SANITIZE_EMAIL);
$phone=filter_var($input['phone'],FILTER_SANITIZE_SPECIAL_CHARS);
$website=filter_var($input['website'],FILTER_SANITIZE_SPECIAL_CHARS);
$address=filter_var($input['address'],FILTER_SANITIZE_SPECIAL_CHARS);
$description=filter_var($input['description'],FILTER_SANITIZE_SPECIAL_CHARS);
$city=filter_var($input['city'],FILTER_SANITIZE_SPECIAL_CHARS);
$country=filter_var($input['country'],FILTER_SANITIZE_SPECIAL_CHARS);
$state=filter_var($input['state'],FILTER_SANITIZE_SPECIAL_CHARS);
$postalcode=filter_var($input['postalcode'],FILTER_SANITIZE_SPECIAL_CHARS);
$openinghours=filter_var($input['openinghours'],FILTER_SANITIZE_SPECIAL_CHARS);
$status=filter_var($input['status'],FILTER_SANITIZE_NUMBER_INT);




$updateshop= new Updateshop($shopid,
$name,$email,$phone,$address,$website,$description,$city,
$country,$state,$postalcode,$openinghours,$status);
$result= $updateshop->updateTheShop();
    //send json result login
    if($result){
       
echo json_encode($result);
    }
}catch(Exception $e){
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
}else{
    echo die(json_encode('invalid request'));
}

