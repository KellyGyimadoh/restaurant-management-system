<?php
require_once '../../includes/sessions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configmenu/FoodItemDetails.php';
require_once '../../includes/viewfooddetails/Viewitemprice.php';
require_once("../../includes/configorders/Orders.php");
require_once("../../includes/orderscontrol/Addallorders.php");

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try {
       

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON input");
        }

        $retriveitemandprice=new Viewitemprice();
        $result= $retriveitemandprice->viewthefooditemAndPrice();
        if ($result) {
            echo json_encode(["success"=>true, "items"=>$result,"message"=>"items retrieved successfully"]);
           // echo json_encode($result);
        } else {
            echo json_encode(["success"=>false,"message"=>"no food items available"]);
            
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
