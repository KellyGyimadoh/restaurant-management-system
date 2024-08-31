<?php
require_once '../../includes/connection.php';
header("Content-Type: application/json");

$response = ["success" => false, "messages" => []];

try {
    $input = json_decode(file_get_contents("php://input"), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON input");
    }

    $menuSection = filter_var($input['menu_section'], FILTER_SANITIZE_SPECIAL_CHARS);
    $menuId = filter_var($input['menu_id'], FILTER_SANITIZE_NUMBER_INT);

    if (empty($menuSection) || empty($menuId)) {
        throw new Exception("Menu section and menu ID are required");
    }

    $conn = new PDO("mysql:hostname=localhost;dbname=foodshop", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "INSERT INTO menu_sections (section, menuid) VALUES (:menuSection, :menuId)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":menuSection", $menuSection);
    $stmt->bindParam(":menuId", $menuId);

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["messages"] = "Menu section added successfully";
    } else {
        throw new Exception("Failed to add menu section");
    }
} catch (Exception $e) {
    $response["success"] = false;
    $response["messages"] = $e->getMessage();
}

echo json_encode($response);
?>
