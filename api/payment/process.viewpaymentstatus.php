<?php
require_once '../../includes/sessions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configorders/Paymentorders.php';
require_once '../../includes/configorders/Orders.php';
require_once '../../includes/configorders/Orderitems.php';
require_once '../../includes/orderscontrol/Selectorder.php';
require_once '../../includes/controlorderitems/Selectoneorderitem.php';
require_once("../../includes/paymentcontroller/Selectpaymentstatus.php");


header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try {

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON input");
        }
        $input = json_decode(file_get_contents("php://input"), true);
        $orderid = filter_var($input['orderid'], FILTER_SANITIZE_NUMBER_INT);
        $viewpayment = new Selectpaymentstatus($orderid);
        $selectorder = new Selectorder($orderid);
        $selectorderitem= new Selectoneorderitem($orderid);
        $orderdetails = $selectorder->getOrder();
        $result = $viewpayment->viewThePaymentStatus();
        $orderitemdetails= $selectorderitem->viewTheOrderItem();
        if ($result) {
            echo json_encode(["success" => true, "message" => "Payment details retrieved", "result" => $result, "orderdetails" => $orderdetails ,"orderitemdetails"=>$orderitemdetails]);
            // echo json_encode($result);
        } else {
            echo json_encode(["success" => false, "message" => "no payments available"]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
