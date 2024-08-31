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
    $reservecount= new Selectcustomer($limit,$offset,$search);
    $reservecustomer= $reservecount->getrecentcustomer();
    $result=$reservecount->totalCustomerCount();
    if($result || $reservecustomer){
      echo  json_encode(["success"=>true, "reservationcount"=>$result, "recentreservationname"=>$reservecustomer['name'],"contact"=>$reservecustomer['phone']]);
    }else{
        echo json_encode(["success"=>false,"message"=>'no items available']);
    }

}else{
    die(json_encode("invalid request"));
}