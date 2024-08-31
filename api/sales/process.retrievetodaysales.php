<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configsales/Sales.php");
require_once("../../includes/controlsales/Selecttodaysales.php");

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $todaySaleManager = new Selecttodaysales($limit, $offset, $search);
    $todaySales = $todaySaleManager->viewTodaySales();
    $total_sales = $todaySaleManager->todaySalestotalCount($search);

    if ($todaySales['success']) {
        echo json_encode(["success" => true, "result" => $todaySales['result'], "total" => $total_sales]);
    } else {
        echo json_encode(["success" => false, "message" => 'No Sales Made Today']);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>
