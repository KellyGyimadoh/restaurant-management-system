<?php
require_once '../../includes/sessions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configmenu/Menu.php';
require_once '../../includes/configmenu/MenuSection.php';
require_once '../../includes/mainmenucontroller/Selectmenu.php';
require_once '../../includes/menusectioncontroller/Insertmenusection.php';

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    header("Content-Type: application/json");
    try {
        // You may need to adjust these based on your actual implementation
        $limit = "";
        $offset = "";
        $search = "";

        $selectallmenu = new Selectmenu($limit, $offset, $search);
        $result = $selectallmenu->selectTheMenuType();

        if ($result) {
            echo json_encode(["success" => true, "types" => $result, "message" => "menus retrieved"]);
        } else {
            echo json_encode(["success" => false, "message" => "no result found"]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, "message" => "invalid request"]);
    die();
}
