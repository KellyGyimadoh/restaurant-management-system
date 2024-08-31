<?php
require_once '../../includes/sessions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configmenu/Menu.php';
require_once '../../includes/configmenu/MenuSection.php';
require_once '../../includes/mainmenucontroller/Insertmainmenu.php';
require_once '../../includes/menusectioncontroller/Insertmenusection.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    header("Content-Type: application/json");
    try {
        $input = json_decode(file_get_contents("php://input"), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid Json input");
        }
        $menuid = filter_var($input['menuid'], FILTER_SANITIZE_SPECIAL_CHARS);
        $section = filter_var($input['menusection'], FILTER_SANITIZE_SPECIAL_CHARS);
        if (empty($section)) {
            throw new Exception("Menu section is required");
        }

        $insertmenusection = new Insertmenusection($menuid, $section);
        $result = $insertmenusection->insertMenuSection();
        //send json
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, "message" => "invalid request"]);
    die();
}
