<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configshop/Shop.php");
require_once("../../includes/controlshop/Deleteshop.php");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
    $shopid=$_GET['id'];
    $shopid=filter_var($shopid,FILTER_SANITIZE_NUMBER_INT);
    try {
       

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON input");
        }

        $deleteshop=new Deleteshop($shopid);
        $result= $deleteshop->deleteTheShop();
        if ($result) {
            echo json_encode(["success"=>true, "message"=>"Shop Successfully Removed"]);
           // echo json_encode($result);
        } else {
            echo json_encode(["success"=>false,"message"=>"Failed to remove Shop"]);
            
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
