<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once '../../includes/configmenu/FoodItemDetails.php';
require_once '../../includes/viewfooddetails/Viewfoodid.php';
require_once("../../includes/configorders/Orders.php");
require_once("../../includes/orderscontrol/Addallorders.php");
require_once("../../includes/configdbcust/Ordercustomers.php");
require_once("../../includes/ordercustomercontroller/Insertordercustomer.php");


header("Content-Type: application/json");

try {
    $input = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON input');
    }

    $items = $input['items'];
    $quantities = $input['quantities'];
    $prices = $input['prices'];
    $fooditemids = [];

    foreach ($items as $index => $item) {
        $newfooditemid = new Viewfoodid($item);
        $response = $newfooditemid->viewthefooditem();
        if (!empty($response)) {
            $fooditemids[] = [
                'fooditemid' => $response['id'],
                'price' => $prices[$index],
                'itemnumber' => $quantities[$index]
            ];
        } else {
            echo json_encode(['success' => false, 'message' => "Food item '{$item}' doesn't exist"]);
            exit;
        }
    }

    $orderstatus = filter_var($input['orderstatus'], FILTER_SANITIZE_SPECIAL_CHARS);
    $customerid = filter_var($input['customerid'], FILTER_SANITIZE_NUMBER_INT);
    $totalcost = number_format(filter_var($input['totalcost'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION), 2, '.', '');
    $amountowed = number_format(filter_var($input['amountowed'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION), 2, '.', '');
    $tax = number_format(filter_var($input['tax'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION), 2, '.', '');
    $paymentstatus = filter_var($input['paymentstatus'], FILTER_SANITIZE_SPECIAL_CHARS);
    
    $addorder = new Addallorders($customerid, $orderstatus, $totalcost, $amountowed, $tax, $paymentstatus, $fooditemids);
    $result = $addorder->AddTheOrder();

    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
