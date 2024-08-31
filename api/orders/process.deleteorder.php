<?php
require_once '../../includes/sessions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../includes/configdb/Dbconnection.php';


require_once("../../includes/configorders/Orders.php");
require_once("../../includes/orderscontrol/Deleteorder.php");

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
    $orderid=$_GET['id'];
    $orderid=filter_var($orderid,FILTER_SANITIZE_NUMBER_INT);
    try {
       

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON input");
        }

        $deleteorder=new Deleteorder($orderid);
        $result= $deleteorder->deleteTheOrder();
        if ($result) {
            echo json_encode(["success"=>true, "message"=>"Order Successfully Removed"]);
           // echo json_encode($result);
        } else {
            echo json_encode(["success"=>false,"message"=>"Failed to remove order"]);
            
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
