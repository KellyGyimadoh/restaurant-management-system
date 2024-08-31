<?php
require_once '../../includes/sessions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configmenu/Menuitem.php';
require_once '../../includes/menuitemcontroller/Insertmenuitem.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    header("Content-Type: application/json");
    try {
        $input = json_decode(file_get_contents("php://input"), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid Json input");
        }
        $menusectionid=filter_var($input['menusection_id'],FILTER_SANITIZE_NUMBER_INT);
        $fooditem=filter_var($input['food_item'],FILTER_SANITIZE_SPECIAL_CHARS);
        $itemdescription=filter_var($input['itemdescription'],FILTER_SANITIZE_SPECIAL_CHARS);
        $price=filter_var($input['price'],FILTER_SANITIZE_NUMBER_FLOAT);
        $image=filter_var($input['image'],FILTER_SANITIZE_SPECIAL_CHARS);
        $fooditemselect=filter_var($input['fooditemselect'],FILTER_SANITIZE_SPECIAL_CHARS);
        if(empty($fooditem) && empty($fooditemselect)){
            echo json_encode(["success" => false, "message" =>"No food item to add"]);
        }elseif($fooditem && $fooditemselect){
            echo json_encode(["success" => false, "message" =>"please either select or enter an item"]);
        }
        elseif($fooditem){
        $insertnewfooditem= new Insertmenuitem($menusectionid,$fooditem,$itemdescription,$price,$image);
          $result=$insertnewfooditem->insertTheFoodItem();  
          echo json_encode($result);
    }elseif($fooditemselect){
            $insertnewfooditem= new Insertmenuitem($menusectionid,$fooditemselect,$itemdescription,$price,$image);
           $result=$insertnewfooditem->insertTheFoodItem();
           echo json_encode($result);
          
        }
        
        
      exit(); 
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, "message" => "invalid request"]);
    die();
}
