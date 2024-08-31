<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configorders/Orders.php");
require_once("../../includes/controlorderitems/Deleteorderitem.php");
require_once("../../includes/configmenu/FooditemDetails.php");
require_once("../../includes/viewfooddetails/Viewfooditem.php");
require_once("../../includes/viewfooddetails/Viewfoodid.php");

header("Content-Type: application/json");

try {
    $data = json_decode(file_get_contents('php://input'), true);

    // Log the entire incoming request data for debugging
    error_log("Incoming Request: " . print_r($data, true));

    $orderid = htmlspecialchars(strip_tags($data['orderid']));
    $item = htmlspecialchars($data['item']);
    $newfooditemid = new Viewfoodid($item);
    $response = $newfooditemid->viewthefooditem();

    $removeitem = new Deleteorderitem($orderid, $response['id']);
    $result = $removeitem->deleteTheOrderItem();

    if ($result['success']) {
        echo json_encode($result);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove']);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    error_log("Error updating order: " . $e->getMessage());
}
?>
