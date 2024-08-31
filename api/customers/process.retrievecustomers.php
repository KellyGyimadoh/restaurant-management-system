<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('../../includes/sessions.php');
require('../../includes/configdb/Dbconnection.php');
require_once("../../includes/configdbcust/Ordercustomers.php");
require_once("../../includes/ordercustomercontroller/Selectordercustomer.php");
header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD']=="GET"){
    $limit=$offset=$search="";
    $customers= new Selectordercustomer($limit,$offset,$search);
    $result=$customers->retrieveTheOrderCustomers();

    if($result){
      echo  json_encode(["success"=>true, "result"=> $result, 'message'=>'Customers Retrieved']);
    }else{
        echo json_encode(["success"=>false,"message"=>'no customer available']);
    }

}else{
    die(json_encode("invalid request"));
}