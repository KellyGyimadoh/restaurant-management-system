<?php
require_once '../../includes/sessions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configmenu/Menu.php';
require_once '../../includes/configmenu/MenuSection.php';
require_once '../../includes/configmenu/Menuitem.php';
require_once '../../includes/mainmenucontroller/Updatemenu.php';
require_once '../../includes/menusectioncontroller/Updatemenusection.php';
require_once '../../includes/menuitemcontroller/Updatemenuitem.php';

header("Content-Type: application/json");

//$response = ["success" => true, "messages" => []];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try{

        $input = json_decode(file_get_contents("php://input"), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON input");
        }

       
        $menuid = filter_var($input['typeid'], FILTER_SANITIZE_NUMBER_INT);
        $menusectionid =filter_var($input['sectionid'], FILTER_SANITIZE_NUMBER_INT);
        $fooditemid =filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        $menutype = filter_var($input['menu_type'], FILTER_SANITIZE_SPECIAL_CHARS);
        $itemdescription = filter_var($input['itemdescription'], FILTER_SANITIZE_SPECIAL_CHARS);
        $menusection = filter_var($input['section_name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $fooditem = filter_var($input['fooditem'], FILTER_SANITIZE_SPECIAL_CHARS);
        $price = filter_var($input['price'], FILTER_SANITIZE_NUMBER_FLOAT);
        $image = "";
        $menudescription = "";
//$fooditemarr = array([$fooditem, $fooditemselect]);

       /* if (empty($menutype) || empty($menusection) || empty($price) || empty($fooditem)||empty($fooditemid)) {
            $response['success'] = false;
            $response['message'][] = "Please fill all items";
            echo json_encode($response);
            exit;
        } */
              // $newfooditem= new Updatemenuitem($fooditemid,$fooditem,$itemdescription,$price,$image,$menusectionid);
               $newfooditem= new Updatemenuitem($fooditemid,$fooditem,$itemdescription,$price,$image,
               $menusectionid,$menusection,$menutype,$menudescription,$menuid);
        $result=$newfooditem->updateTheMenuItem();
        echo json_encode($result);

    }catch(Exception $e){
        die(json_encode("could not perform operation".$e->getMessage()));
    } 
}else{
    die(json_encode("invalid request"));
}