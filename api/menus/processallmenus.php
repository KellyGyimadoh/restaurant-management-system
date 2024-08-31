<?php
require_once '../../includes/sessions.php';
require_once '../../includes/connection.php';
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

$response = ["success" => true, "messages" => []];

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
        $menudescription = "";
        $fooditemarr = array([$fooditem, $fooditemselect]);

        if (empty($menutype) || empty($menusection) || empty($price) || empty($fooditemarr)) {
            $response['success'] = false;
            $response['messages'][] = "Please fill all items";
            echo json_encode($response);
            exit;
        }

        // Database connection
        try {
            
            $conn->beginTransaction(); // Start transaction
        } catch (PDOException $e) {
            $response['success'] = false;
            $response['messages'][] = "Connection failed: " . $e->getMessage();
            echo json_encode($response);
            exit;
        }

        // Insert main menu type
        try {
            $sql = "INSERT INTO menus (type, description) VALUES (:menutype, :menudescription)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":menutype", $menutype);
            $stmt->bindParam(":menudescription", $menudescription);

            if ($stmt->execute()) {
                $menuid = $conn->lastInsertId();
                $response['messages'][] = "Menu type added successfully";
            } else {
                throw new Exception("Failed to add menu type");
            }
        } catch (PDOException $e) {
            $conn->rollBack(); // Rollback transaction on failure
            error_log("Failed to add menu: " . $e->getMessage());
            throw new Exception("Failed to add menu type");
        }

        // Insert menu section
        try {
            $sql = "INSERT INTO menusections (menu_id, section) VALUES (:menuid, :section)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":menuid", $menuid);
            $stmt->bindParam(":section", $menusection);

            if ($stmt->execute()) {
                $menusectionid = $conn->lastInsertId();
                $response['messages'][] = "Menu section added successfully";
            } else {
                throw new Exception("Failed to add menu section");
            }
        } catch (PDOException $e) {
            $conn->rollBack(); // Rollback transaction on failure
            error_log("Failed to add section: " . $e->getMessage());
            throw new Exception("Failed to add menu section");
        }

        // Insert food item
        if (empty($fooditem) && empty($fooditemselect)) {
            $response['success'] = false;
            $response['messages'][] = "No food item to add";
            $conn->rollBack(); // Rollback transaction if no food item is added
        } elseif ($fooditem && $fooditemselect) {
            $response['success'] = false;
            $response['messages'][] = "Please either select or enter an item";
            $conn->rollBack(); // Rollback transaction if both food item and select are provided
        } else {
            $foodItemToInsert = $fooditem ?: $fooditemselect;   

            if (checkfoodexist($foodItemToInsert, $conn)) {
                $response['success'] = false;
                $response['messages'][] = "Food item already exists";
                $conn->rollBack();
            } else {
                try {
                    $sql = "INSERT INTO menusitem (menusection_id, fooditem, itemdescription, price, image) VALUES (:menusectionid, :fooditem, :itemdescription, :price, :image)";
                    $stmt = $conn->prepare($sql);
                    $formattedPrice = number_format((float)$price, 2, '.', '');
                    $stmt->bindParam(':menusectionid', $menusectionid);
                    $stmt->bindParam(':fooditem', $foodItemToInsert);
                    $stmt->bindParam(':itemdescription', $itemdescription);
                    $stmt->bindParam(':price', $formattedPrice);
                    $stmt->bindParam(':image', $image);
                    
                    if ($stmt->execute()) {
                        $response['messages'][] = "Food item added successfully";
                        $conn->commit(); // Commit transaction if all operations are successful
                    } else {
                        throw new Exception("Failed to add items");
                    }
                } catch (PDOException $e) {
                    $conn->rollBack();
                    error_log("Failed to add menu item: " . $e->getMessage());
                    throw new Exception("Failed to add menu item");
                }
            }
        }
    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack(); // Rollback transaction on any exception
        }
        $response['success'] = false;
        $response['messages'][] = $e->getMessage();
    }
    echo json_encode($response);
} else {
    $response['success'] = false;
    $response['messages'][] = "Invalid request";
    echo json_encode($response);
}

/*function checkfoodexist($fooditem, $conn) {
    try {
        $query = "SELECT fooditem FROM menusitem WHERE fooditem = :fooditem";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":fooditem", $fooditem);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $fooditem === $result['fooditem'];
    } catch (PDOException $e) {
        error_log("Could not select menu items: " . $e->getMessage());
        throw new Exception("Could not select menu items");
    }
}*/
?>
