<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configorders/Orders.php");
require_once("../../includes/orderscontrol/Selectjoinedorders.php");
// getOrderDetails.php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $receiptnumber = $input['receiptnumber'] ?? null;

    if ($receiptnumber) {
        // Assuming you have a method in your class to get order details
        $getorder= new Selectjoinedorders($receiptnumber);

        $response = $getorder->printordersummary();
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'message' => 'No receipt number provided']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
