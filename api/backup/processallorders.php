<?php
require_once 'Dbconnection.php';

class OrderProcessing extends Dbconnection {
    public function processOrder($data) {
        try {
            $conn = $this->connect();
            $conn->beginTransaction();
           
            // Insert order
            $query = "INSERT INTO orders (customerid, orderstatus, totalcost, amountowed, amountpaid, balance) 
                      VALUES (:customerid, :orderstatus, :totalcost, :amountowed, :amountpaid, :balance)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':customerid', $data['customerid']);
            $stmt->bindParam(':orderstatus', $data['orderstatus']);
            $stmt->bindParam(':totalcost', $data['totalcost']);
            $stmt->bindParam(':amountowed', $data['amountowed']);
            $stmt->bindParam(':amountpaid', $data['amountpaid']);
            $stmt->bindParam(':balance', $data['balance']);
            $stmt->execute();
            $orderid = $conn->lastInsertId();

            // Insert order items
            $items = $data['items'];
            $quantities = $data['quantities'];
            $prices = $data['prices'];
            foreach ($items as $index => $item) {
                $query = "INSERT INTO orderitems (orderid, item, quantity, price) 
                          VALUES (:orderid, :item, :quantity, :price)";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':orderid', $orderid);
                $stmt->bindParam(':item', $item);
                $stmt->bindParam(':quantity', $quantities[$index]);
                $stmt->bindParam(':price', $prices[$index]);
                $stmt->execute();
            }

            // Insert payment
            $receiptnumber = uniqid('receipt_');
            $query = "INSERT INTO payments (orderid, amount_paid, payment_date, receiptnumber) 
                      VALUES (:orderid, :amount_paid, NOW(), :receiptnumber)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':orderid', $orderid);
            $stmt->bindParam(':amount_paid', $data['amountpaid']);
            $stmt->bindParam(':receiptnumber', $receiptnumber);
            $stmt->execute();

            // Update order with receipt number
            $query = "UPDATE orders SET receiptnumber=:receiptnumber WHERE orderid=:orderid";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':receiptnumber', $receiptnumber);
            $stmt->bindParam(':orderid', $orderid);
            $stmt->execute();

            $conn->commit();
            return ['success' => true, 'message' => 'Order processed successfully', 'orderid' => $orderid];
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Failed to process order! " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to process order'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'customerid' => $_POST['customerid'],
        'orderstatus' => 'Pending', // Example status
        'totalcost' => $_POST['totalcost'],
        'amountowed' => $_POST['amountowed'],
        'amountpaid' => $_POST['amountpaid'],
        'balance' => $_POST['balance'],
        'items' => $_POST['items'],
        'quantities' => $_POST['quantities'],
        'prices' => $_POST['prices']
    ];

    $orderProcessing = new OrderProcessing();
    $result = $orderProcessing->processOrder($data);
    echo json_encode($result);
}
?>
