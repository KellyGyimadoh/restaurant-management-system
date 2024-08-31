<?php
require_once '../../includes/sessions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configmenu/Menu.php';
require_once '../../includes/mainmenucontroller/Selectonemenu.php';
require_once '../../includes/mainmenucontroller/Insertmainmenu.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON input");
        }

        $menutype = filter_var($input['menutype'], FILTER_SANITIZE_SPECIAL_CHARS);
        //$menudescription = filter_var($input['menudescription'], FILTER_SANITIZE_SPECIAL_CHARS);
        $menudescription="";
        $retrieveid = new Selectonemenu();
        $menutype=strtolower($menutype);
        $result = $retrieveid->retrieveId($menutype,$menudescription);
        if ($result) {
            echo json_encode(["id" => $result['id'],"type"=>$result['type']]);
           // echo json_encode($result);
        } else {
            echo json_encode(["id" => "newid","type"=>"new type found"]);
            // Insert new type if not found
           /* $insertmenu = new Insertmainmenu($menutype, $menudescription);
            $insertResult = $insertmenu->insertMenuTypes();

            if ($insertResult['success']) {
                $newretrieve = new Selectonemenu();
                $newresult = $newretrieve->retrieveId($menutype,$menudescription);
                echo json_encode(["id" => $newresult['id'],"type"=>$newresult['type']]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to add new menu type."]);
            }*/
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
