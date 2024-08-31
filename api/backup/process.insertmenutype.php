<?php
require_once '../../includes/sessions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../includes/configdb/Dbconnection.php';

require_once '../../includes/connection.php';
header("Content-Type: application/json");

$response = ["success" => false, "messages" => []];

try {
    $input = json_decode(file_get_contents("php://input"), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON input");
    }

    $menuType = filter_var($input['menu_type'], FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($menuType)) {
        throw new Exception("Menu type is required");
    }

  
try{
    $query = "INSERT INTO menus (type) VALUES (:menuType)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":menuType", $menuType);

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["messages"] = "Menu type added successfully";
    } else {
        throw new Exception("Failed to add menu type");
    }
}catch(PDOException $e){
    die("faile to add menu type".$e->getMessage());
}
} catch (Exception $e) {
    $response["success"] = false;
    $response["messages"] = $e->getMessage();
}

echo json_encode($response);
?>
