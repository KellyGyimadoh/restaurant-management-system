<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../../includes/sessions.php");
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configorders/Orders.php';
require_once '../../includes/orderscontrol/Selectonejoinedorder.php';
require_once '../../includes/configorders/Orderitems.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $orderid = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    try {
        $vieworder = new Selectonejoinedorder($orderid);
        $result = $vieworder->viewOrderinfo();

        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
