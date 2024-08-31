<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('../../includes/sessions.php');
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configdb/Selectusers.php");
header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD']=="GET"){
    $selectworkers= new Selectusers();
    $result= $selectworkers->SelectRecipientDetails();
    if($result){
      echo json_encode( $result);
    }

}else{
    die(json_encode("invalid request"));
}