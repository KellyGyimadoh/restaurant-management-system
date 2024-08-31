<?php
require_once '../../includes/sessions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configorders/Paymentorders.php';
require_once '../../includes/configorders/Orders.php';
require_once '../../includes/configorders/Receipt.php';
require_once '../../includes/orderscontrol/Updateoneorder.php';
require_once("../../includes/paymentcontroller/Updatepayment.php");
require_once("../../includes/paymentcontroller/Insertpayment.php");
require_once("../../includes/controlreceipt/Insertreceipt.php");

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $input = json_decode(file_get_contents("php://input"), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON input");
        }

        // Sanitize and validate inputs
        $orderid = filter_var($input['orderid'], FILTER_SANITIZE_NUMBER_INT);
        $paymentid = filter_var($input['paymentid'], FILTER_SANITIZE_NUMBER_INT);
        $orderreceipt = filter_var($input['orderreceipt'], FILTER_SANITIZE_NUMBER_INT);
        $paymentmethod = filter_var($input['paymentmethod'], FILTER_SANITIZE_SPECIAL_CHARS);
        $paymentstatus = filter_var($input['paymentstatus'], FILTER_SANITIZE_SPECIAL_CHARS);
        $amountpaid = number_format(filter_var($input['amountpaid'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION), 2, '.', '');
        $amountowed = number_format(filter_var($input['amountowed'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION), 2, '.', '');
        $balance = number_format(filter_var($input['balance'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION), 2, '.', '');
        $newamountowed=$balance;
         // Update payment and order information
        $makepayment = new Updatepayment($orderid, $paymentmethod, $paymentstatus, $amountpaid);
        $result1 = $makepayment->updateThePayment();

        $updateorder = new Updateoneorder($orderid, $amountpaid, $balance, $newamountowed);
        $result2 = $updateorder->updateTheOneOrder();
        
        $addnewreceipt= new Insertreceipt($paymentid,$orderid,$orderreceipt,$amountpaid,$balance);
        $result3= $addnewreceipt->AddTheReceipt();
       
        // Prepare response
        $response = ["payment" => $result1, "order" => $result2, "receipt"=>$result3];

        if (!empty($response)) {
            echo json_encode($response);
        } else {
            echo json_encode(["success" => false, "message" => "No payments available"]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>
