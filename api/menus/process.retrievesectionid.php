<?php
require_once '../../includes/sessions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configmenu/MenuSection.php';
require_once '../../includes/menusectioncontroller/Selectonemenusection.php';
require_once '../../includes/menusectioncontroller/Insertmenusection.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON input");
        }

        $menusection = filter_var($input['menusection'], FILTER_SANITIZE_SPECIAL_CHARS);
        $menuid=filter_var($input['menuid'],FILTER_SANITIZE_NUMBER_INT);
       
        $retrievemenusectionid = new Selectonemenusection();
        $result = $retrievemenusectionid->retrievemenusectionId($menusection);
        if ($result) {
            echo json_encode(["id" => $result['id'],"section"=>$result['section']]);
        } else {
            // Insert with new category if not found
            $insertmenusection = new Insertmenusection($menuid,$menusection);
            $insertResult = $insertmenusection->insertMenuSection();

            if ($insertResult['success']) {
                $newretrieve = new Selectonemenusection();
                $newresult = $newretrieve->retrievemenusectionId($menusection);
                echo json_encode(["id" => $newresult['id'],"section"=>$newresult['section']]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to add new menu section/category."]);
            }
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
