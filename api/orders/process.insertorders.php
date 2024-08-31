<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configorders/Orders.php");
require_once("../../includes/orderscontrol/Insertorders.php");

//using api and json
header("Content-Type: application/json");
//retrieve raw post data
try{
$input= json_decode(file_get_contents('php://input'),true);
if (json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception('Invalid JSON input');
}
$customerid=filter_var($input['customerid'],FILTER_SANITIZE_NUMBER_INT);
$orderstatus=filter_var($input['orderstatus'],FILTER_SANITIZE_SPECIAL_CHARS);


$addorder=new Insertorders($customerid,$orderstatus);
$result= $addorder->insertTheOrder();

    //send json result login
 if($result){
       
echo json_encode($result);
    }
}catch(Exception $e){
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}


