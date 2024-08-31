<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configorders/Joinedorders.php");
require_once("../../includes/orderscontrol/Joinorders.php");

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $orderManager = new Joinorders($limit, $offset, $search);
    $orders = $orderManager->viewallorders();
    $total_orders = $orderManager->totalAllordersCount($search);

    if ($orders) {
        echo json_encode(["success" => true, "result" => $orders, "total" => $total_orders]);
    } else {
        echo json_encode(["success" => false, "message" => 'No orders available']);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>
