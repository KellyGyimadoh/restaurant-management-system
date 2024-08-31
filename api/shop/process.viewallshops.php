<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../../includes/sessions.php");
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configshop/Shop.php';
require_once '../../includes/controlshop/Selectallshops.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $shopManager = new Selectallshops($limit, $offset, $search);
    $shops = $shopManager->viewallShops();
    $total_shops = $shopManager->totalAllShopCount();

    if ($shops) {
        echo json_encode(["success" => true, "result" => $shops, "total" => $total_shops]);
    } else {
        echo json_encode(["success" => false, "message" => 'No shops available']);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>



