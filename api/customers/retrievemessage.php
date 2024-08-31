<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('../../includes/sessions.php');
require('../../includes/configdb/Dbconnection.php');
require('../../includes/configdbcust/Customers.php');
require('../../includes/customercontroller/Selectcustomer.php');

header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD']=="GET"){
    $limit=$offset=$search="";
    $newmessage= new Selectcustomer($limit,$offset,$search);
    $result=$newmessage->getLatestMessage();

    if($result){
      echo  json_encode(["success"=>true, "message"=>$result['message'],"phone"=>$result['phone'],"date"=>$result['date_reg']]);
    }else{
        echo json_encode(["success"=>false,"message"=>'no message available']);
    }

}else{
    die(json_encode("invalid request"));
}