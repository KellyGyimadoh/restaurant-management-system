<?php
require_once '../../includes/sessions.php';
require_once '../../includes/connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configmenu/Menu.php';
require_once '../../includes/configmenu/MenuSection.php';
require_once '../../includes/configmenu/Menuitem.php';
require_once '../../includes/mainmenucontroller/Deletemenu.php';
require_once '../../includes/menusectioncontroller/Deletemenusection.php';
require_once '../../includes/menuitemcontroller/Deletemenuitem.php';

header("Content-Type: application/json");

//$response = ["success" => true, "messages" => []];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try{

        $input = json_decode(file_get_contents("php://input"), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON input");
        }

       
        $menuid = filter_var($input['typeid'], FILTER_SANITIZE_NUMBER_INT);
       
  $deleteall= new DeleteMenu($menuid); 
        $result=$deleteall->deleteAllTheMenu();
        echo json_encode($result);

    }catch(Exception $e){
        die(json_encode("could not perform operation".$e->getMessage()));
    } 
}else{
    die(json_encode("invalid request"));
}