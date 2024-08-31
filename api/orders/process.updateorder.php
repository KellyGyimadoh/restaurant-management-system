<?php
// process.updateorder.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configorders/Orders.php");
require_once("../../includes/controlorderitems/Deleteorderitem.php");
require_once("../../includes/orderscontrol/Updateallorders.php");
require_once '../../includes/configmenu/FoodItemDetails.php';
require_once '../../includes/viewfooddetails/Viewfooditem.php';
require_once '../../includes/viewfooddetails/Viewfoodid.php';

header("Content-Type: application/json");

try {
    $data = json_decode(file_get_contents('php://input'), true);

    // Log the entire incoming request data for debugging
    error_log("Incoming Request: " . print_r($data, true));

    if (!isset($data['orderid']) || !isset($data['totalcost']) || !isset($data['amountowed']) || !isset($data['items'])) {
        throw new Exception("Invalid data received");
    }

    $orderid = htmlspecialchars(strip_tags($data['orderid']));
    $totalcost = htmlspecialchars(strip_tags($data['totalcost']));
    $amountowed = htmlspecialchars(strip_tags($data['amountowed']));
    $items = $data['items'];
   

    $fooditemids = [];
    foreach ($items as $item) {
        $itemValue = isset($item['fooditem']) ? $item['fooditem'] : null;
        $quantityValue = isset($item['itemnumber']) ? $item['itemnumber'] : null;
        $priceValue = isset($item['price']) ? $item['price'] : null;

        if ($itemValue && $quantityValue && $priceValue) {
            $newfooditemid = new Viewfoodid($itemValue);
            $response = $newfooditemid->viewthefooditem();

            if (!empty($response)) {
                $fooditemids[] = [
                    'fooditemid' => $response['id'],
                    'price' => $priceValue,
                    'itemnumber' => $quantityValue
                ];
            } else {
                throw new Exception("Food item '{$itemValue}' doesn't exist");
            }
        } else {
            throw new Exception("Invalid item data");
        }
    }


    $updateallorders = new Updateallorders($orderid, $totalcost, $amountowed, $fooditemids);

    $result= $updateallorders->updateAllTheOrders();
        echo json_encode($result);
    
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    error_log("Error updating order: " . $e->getMessage());
}
?>


