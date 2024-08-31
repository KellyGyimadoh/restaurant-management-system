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
         $menutype =filter_var($input['menutype'], FILTER_SANITIZE_SPECIAL_CHARS);
        $menudescription ="";
        if (empty($menutype)) {
            throw new Exception("Menu type is required");
        }
    
         $insertmenu = new Insertmainmenu($menutype, $menudescription);
         
        $result = $insertmenu->insertMenuTypes(); 
      
        //send json
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, "message" => "invalid request"]);
    die();
}

/*
require_once '../../includes/sessions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configmenu/Menu.php';
require_once '../../includes/configmenu/MenuSection.php';
require_once '../../includes/configmenu/Menuitem.php';
require_once '../../includes/mainmenucontroller/Insertmainmenu.php';
require_once '../../includes/menusectioncontroller/Insertmenusection.php';
require_once '../../includes/menuitemcontroller/Insertmenuitem.php';

header("Content-Type: application/json");


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $input = json_decode(file_get_contents("php://input"), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON input");
        }
        $menuid = "";
        $menusectionid = "";
        $menutype = filter_var($input['menutype'], FILTER_SANITIZE_SPECIAL_CHARS);
        $itemdescription = filter_var($input['itemdescription'], FILTER_SANITIZE_SPECIAL_CHARS);
        $menusection = filter_var($input['section_name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $fooditem = filter_var($input['food_item'], FILTER_SANITIZE_SPECIAL_CHARS);
        $fooditemselect = filter_var($input['fooditemselect'], FILTER_SANITIZE_SPECIAL_CHARS);
        $price = filter_var($input['price'], FILTER_SANITIZE_NUMBER_FLOAT);
        $image = filter_var($input['image'], FILTER_SANITIZE_SPECIAL_CHARS);
        $menudescription="";
        //main menu

        if(empty($menutype)||empty($menusection)||empty($fooditem)||empty($price)){
            die(json_encode(["success"=>false, "message"=>"please fill all items"]));
        }
        $hostname="localhost";
        $dbname="foodshop";
        $username="root";
       $password="";

      
           try {
               $conn= new PDO("mysql:hostname=$hostname;dbname=$dbname",$username,$password);
               $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
           } catch (PDOException $e) {
               die("connection failed".$e->getMessage());
           }
       
        

        try {
            // Insert a new record with an auto-increment ID
            $sql = "INSERT INTO menus (type, description) VALUES (:menutype, :menudescription)";
            $stmt = $conn->prepare($sql);
    
            $stmt->bindParam(":menutype", $menutype);
            $stmt->bindParam(":menudescription", $menudescription);
    
          
            if ($stmt->execute()) {
                $menuid= $conn->lastInsertId();
                echo json_encode(["success" => true, "message" => "Type added"]);
            } else {
                echo json_encode(["success" => false, "message" => "failed to add"]);
                throw new Exception("Failed to execute statement.");
                
            }
        } catch (PDOException $e) {
          
            error_log("Failed to add menu: " . $e->getMessage());
          
        }
       
        //$insertmenu = new Insertmainmenu($menutype, $menudescription);
        //$result = $insertmenu->insertMenuTypes();
        //echo json_encode($result);
        //retrieve menuid
         //$menuid= $result['menuid'];
        //menu section
        /*$insertmenusection = new Insertmenusection($menuid, $menusection);
        $insertResult = $insertmenusection->insertMenuSection();
        echo json_encode($insertResult);
        //retrieve menusection id
        $menusectionid= $insertResult['menusectionid'];




try {
    $sql = "INSERT INTO menusections (menu_id, section) VALUES (:menuid, :section)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":menuid", $menuid);
    $stmt->bindParam(":section", $menusection);
    
    if($stmt->execute()){
        $menusectionid=$conn->lastInsertId();
        echo json_encode(["success" => true, "message" => "Section added"]);
    }else{
        echo json_encode(["success" => false, "message" => " failed to add to Section "]);
    }
    // Introducing a small delay to ensure the insert operation is completed
   // usleep(50000); // 50 milliseconds
    

} catch (PDOException $e) {
    error_log("Failed to add section: " . $e->getMessage());
    return false;
} catch (Exception $e) {
    error_log($e->getMessage());
    return false;
}
        //insert food item

        if (empty($fooditem) && empty($fooditemselect)) {
            echo json_encode(["success" => false, "message" => "No food item to add"]);
        } elseif ($fooditem && $fooditemselect) {
            echo json_encode(["success" => false, "message" => "please either select or enter an item"]);
        } elseif ($fooditem) {
            $insertnewfooditem = new Insertmenuitem($menusectionid, $fooditem, $itemdescription, $price, $image);
            $foodresult = $insertnewfooditem->insertTheFoodItem();
            echo json_encode($foodresult);
        } elseif ($fooditemselect) {
            $insertnewfooditem = new Insertmenuitem($menusectionid, $fooditemselect, $itemdescription, $price, $image);
            $foodresult = $insertnewfooditem->insertTheFoodItem();
            echo json_encode($foodresult);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    die();
}

*/


   
