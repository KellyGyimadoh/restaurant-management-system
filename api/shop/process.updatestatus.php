<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configshop/Shop.php");
require_once("../../includes/controlshop/Updatestatus.php");

if($_SERVER['REQUEST_METHOD']=="POST"){
//using api and json
header("Content-Type: application/json");
//retrieve raw post data
try{
$input= json_decode(file_get_contents('php://input'),true);
if (json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception('Invalid JSON input');
}
$shopid=filter_var($input['shopid'],FILTER_SANITIZE_NUMBER_INT);
$status=filter_var($input['status'],FILTER_SANITIZE_NUMBER_INT);



$updatestat= new Updatestatus($shopid,$status);
$result= $updatestat->updateTheShopStatus();
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

