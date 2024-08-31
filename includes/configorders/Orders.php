<?php

class Orders extends Dbconnection
{
    private $thereceiptnumber;
    // Insert orders
    protected function InsertOrders($customerid, $orderstatus)
    {
        try {
            $conn = parent::connect();
            $query = "INSERT INTO orders (customerid, orderstatus) VALUES (:customerid, :orderstatus)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":customerid", $customerid);
            $stmt->bindParam(":orderstatus", $orderstatus);
            if ($stmt->execute()) {
                $lastid = $conn->lastInsertId();
                return ["success" => true, "message" => "New Order added", "orderid" => $lastid];
            } else {
                return ["success" => false, "message" => "Failed to add new Order"];
            }
        } catch (PDOException $e) {
            error_log("Failed to add new order! " . $e->getMessage());
            return ["success" => false, "message" => "Failed to add new Order"];
        }
    }

    // View one order
    protected function viewOrder($orderid)
    {
        try {
            $query = "SELECT * FROM orders WHERE orderid = :orderid";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":orderid", $orderid);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: [];
        } catch (PDOException $e) {
            throw new Exception("Data cannot be retrieved: " . $e->getMessage());
        }
    }

    // Select all orders
    protected function selectAllOrders()
    {
        try {
            $query = "SELECT * FROM orders";
            $stmt = parent::connect()->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Could not select orders: " . $e->getMessage());
            return false;
        }
    }

    // Update order info
    protected function updateOrders($orderid, $receiptnumber, $orderstatus, $totalcost, $amountpaid, $balance, $amountowed)
    {
        try {
            $query = "UPDATE orders SET receiptnumber = :receiptnumber, totalcost = :totalcost, orderstatus = :orderstatus, 
                      amountowed = :amountowed, amountpaid = :amountpaid, balance = :balance WHERE orderid = :orderid";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":receiptnumber", $receiptnumber);
            $stmt->bindParam(":totalcost", $totalcost);
            $stmt->bindParam(":orderstatus", $orderstatus);
            $stmt->bindParam(":amountpaid", $amountpaid);
            $stmt->bindParam(":amountowed", $amountowed);
            $stmt->bindParam(":balance", $balance);
            $stmt->bindParam(":orderid", $orderid);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Failed to update orders! " . $e->getMessage());
            return false;
        }
    }
    protected function updateOneOrder($orderid, $amountpaid, $balance, $amountowed)
    {

        try {
            $query = "UPDATE orders SET amountpaid=:amountpaid, balance=:balance, amountowed=:amountowed WHERE orderid = :orderid";
            $stmt = parent::connect()->prepare($query);

            // Bind parameters
            $stmt->bindParam(":amountpaid", $amountpaid);
            $stmt->bindParam(":balance", $balance);
            $stmt->bindParam(":amountowed", $amountowed);
            $stmt->bindParam(":orderid", $orderid);

            // Execute the statement
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Failed to update orders! " . $e->getMessage());
            return false;
        }
    }

    // Update amount owed
    protected function updateOrderAmountOwed($orderId, $amountOwed)
    {
        try {
            $query = "UPDATE orders SET amountowed = :amountowed WHERE orderid = :orderid";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam(":amountowed", $amountOwed);
            $stmt->bindParam(":orderid", $orderId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Failed to update amount owed! " . $e->getMessage());
            return false;
        }
    }

    // Process payment
    protected function processPayment($orderId, $amountpaid, $newbalance, $receiptnumber)
    {
        try {
            $query = "UPDATE orders SET amountpaid = :amountpaid, balance = :balance, receiptnumber = :receiptnumber WHERE orderid = :orderid";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam(":amountpaid", $amountpaid);
            $stmt->bindParam(":balance", $newbalance);
            $stmt->bindParam(":orderid", $orderId);
            $stmt->bindParam(":receiptnumber", $receiptnumber);
            if ($stmt->execute()) {
                return ["success" => true, "message" => "Payment processed"];
            } else {
                return ["success" => false, "message" => "Failed to process payment"];
            }
        } catch (PDOException $e) {
            error_log("Failed to process amount paid! " . $e->getMessage());
            return ["success" => false, "message" => "Failed to process payment"];
        }
    }

    // Delete order
    protected function deleteOrder($orderId)
    {
        try {
            $query = "DELETE FROM orders WHERE orderid = :orderid";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":orderid", $orderId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Failed to delete order! " . $e->getMessage());
            return false;
        }
    }

    // View orders with limit and pagination
    protected function viewOrdersWithLimit($limit, $offset, $search = '')
    {
        try {
            $conn = parent::connect();
            $sql = "SELECT * FROM orders";
            if (!empty($search)) {
                $sql .= " WHERE receiptnumber LIKE :search OR orderstatus LIKE :search";
            }
            $sql .= " LIMIT :limit OFFSET :offset";
            $stmt = $conn->prepare($sql);
            if (!empty($search)) {
                $search = '%' . $search . '%';
                $stmt->bindParam(':search', $search, PDO::PARAM_STR);
            }
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Search operation failure! " . $e->getMessage());
            return [];
        }
    }

    // Get total orders count
    protected function getTotalOrdersCount()
    {
        try {
            $stmt = parent::connect()->prepare("SELECT COUNT(*) as total FROM orders");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['total'] : 0;
        } catch (PDOException $e) {
            error_log("Failed to get count: " . $e->getMessage());
            return 0;
        }
    }

    // Generate receipt number
    private function generateReceiptNumber($orderId)
    {
        return 'RCPT-' . str_pad($orderId, 8, '0', STR_PAD_LEFT) . '-' . time();
    }

    // Process all orders and update appropriately
    protected function processOrder($customerid, $orderstatus, $totalcost, $amountowed, $tax, $paymentstatus, $items)
    {
        try {
            $conn = $this->connect();
            $conn->beginTransaction();

            // Insert order
            $query = "INSERT INTO orders (customerid, orderstatus, totalcost, amountowed) 
                      VALUES (:customerid, :orderstatus, :totalcost, :amountowed)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':customerid', $customerid);
            $stmt->bindParam(':orderstatus', $orderstatus);
            $stmt->bindParam(':totalcost', $totalcost);
            $stmt->bindParam(':amountowed', $amountowed);
            $stmt->execute();
            $orderid = $conn->lastInsertId();

            // Insert order items
            foreach ($items as $item) {
                $fooditemid = $item['fooditemid'];
                $price = number_format((float)$item['price'], 2, '.', '');
                $itemnumber = filter_var($item['itemnumber'], FILTER_SANITIZE_NUMBER_INT);

                $query = "INSERT INTO orderitems (orderid, fooditemid, itemnumber, price, tax, totalcost) 
                          VALUES (:orderid, :fooditemid, :itemnumber, :price, :tax, :totalcost)";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':orderid', $orderid);
                $stmt->bindParam(':fooditemid', $fooditemid);
                $stmt->bindParam(':itemnumber', $itemnumber);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':tax', $tax);
                $stmt->bindParam(':totalcost', $totalcost);
                $stmt->execute();
            }

            // Insert payment
            $receiptnumber = $this->generateReceiptNumber($orderid);

            $query = "INSERT INTO payments (orderid, paymentstatus,receiptnumber) 
                      VALUES (:orderid, :paymentstatus,:receiptnumber)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':orderid', $orderid);
            $stmt->bindParam(":receiptnumber", $receiptnumber);
            $stmt->bindParam(':paymentstatus', $paymentstatus);
            $stmt->execute();

            // Update order with receipt number and status



            $orderstatus = "complete";
            $query = "UPDATE orders SET receiptnumber = :receiptnumber, orderstatus = :orderstatus WHERE orderid = :orderid";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':receiptnumber', $receiptnumber);
            $stmt->bindParam(':orderid', $orderid);
            $stmt->bindParam(':orderstatus', $orderstatus);
            $stmt->execute();

            // Commit transaction
            $conn->commit();

            return ['success' => true, 'message' => 'Order processed successfully', 'receiptnumber' => $receiptnumber];
        } catch (Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack(); // Rollback transaction on any exception
            }
            error_log("Failed to process order! " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to process order'];
        }
    }


    // Get order details by receipt number
    protected function getOrderDetails($receiptnumber)
    {
        try {
            $conn = $this->connect();
            $query = "SELECT o.orderid, o.customerid, o.totalcost, o.amountowed, o.amountpaid, o.balance, o.orderstatus, 
                             p.receiptnumber, p.paymentdate, p.paymentmethod, oi.fooditemid, oi.itemnumber, oi.price, oi.tax, 
                             oi.totalcost AS itemtotal, rec.paymentreceipt
                      FROM orders o
                      JOIN payments p ON o.orderid = p.orderid
                      JOIN orderitems oi ON o.orderid = oi.orderid
                      JOIN receipt rec on p.paymentid= rec.paymentid
                      WHERE p.receiptnumber = :receiptnumber";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':receiptnumber', $receiptnumber);
            $stmt->execute();
            $orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ['success' => true, 'orderDetails' => $orderDetails];
        } catch (PDOException $e) {
            error_log("Failed to retrieve order details! " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to retrieve order details'];
        }
    }


    /*protected function viewOneorder($orderid) {
        try {
            $conn = $this->connect();
            $sql = "SELECT
                        ord.orderid,
                        ord.customerid,
                        ord.orderdate,
                        ord.orderstatus,
                        ord.amountowed,
                        ord.totalcost,
                        GROUP_CONCAT(fi.fooditem SEPARATOR ', ') AS items,
                        SUM(it.itemnumber) AS totalquantity,
                        pay.paymentstatus
                    FROM 
                        orders AS ord
                    JOIN 
                        ordercustomers AS ordcust ON ord.customerid = ordcust.customerid
                    JOIN 
                        orderitems AS it ON ord.orderid = it.orderid
                    JOIN 
                        menusitem AS fi ON it.fooditemid = fi.id
                    LEFT JOIN 
                        payments AS pay ON ord.orderid = pay.orderid 
                    WHERE ord.orderid = :orderid";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":orderid", $orderid);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Failed to join: " . $e->getMessage());
            return [];
        }
    }*/
    protected function viewOneorder($orderid)
    {
        try {
            $conn = $this->connect();
            $sql = "SELECT
                        ord.orderid,
                        ord.customerid,
                        ord.orderdate,
                        ord.orderstatus,
                        ord.amountowed,
                        ord.totalcost,
                        GROUP_CONCAT(CONCAT(fi.fooditem, ' - ', it.itemnumber, ' - ', it.price) SEPARATOR ', ') AS items,
                        SUM(it.itemnumber) AS totalquantity,
                        pay.paymentstatus
                    FROM 
                        orders AS ord
                    JOIN 
                        ordercustomers AS ordcust ON ord.customerid = ordcust.customerid
                    JOIN 
                        orderitems AS it ON ord.orderid = it.orderid
                    JOIN 
                        menusitem AS fi ON it.fooditemid = fi.id
                    LEFT JOIN 
                        payments AS pay ON ord.orderid = pay.orderid 
                    WHERE ord.orderid = :orderid
                    GROUP BY ord.orderid, ord.customerid, ord.orderdate, ord.orderstatus, ord.amountowed, ord.totalcost, pay.paymentstatus";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":orderid", $orderid);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Failed to join: " . $e->getMessage());
            return [];
        }
    }

    protected function updateAllOrder($orderid, $totalcost, $amountowed, $items)
    {
        try {
            $conn = $this->connect();
            $conn->beginTransaction();

            // Log the inputs
            $updateOrderQuery = "UPDATE orders SET totalcost=:totalcost,amountowed=:amountowed WHERE orderid=:orderid";
            $stmt = $conn->prepare($updateOrderQuery);
            $stmt->bindparam(":totalcost", $totalcost);
            $stmt->bindparam(":amountowed", $amountowed);
            $stmt->bindparam("orderid", $orderid);
            $stmt->execute();

           /* $clearItemsQuery = "DELETE FROM orderitems WHERE orderid=:orderid";
            $stmt = $conn->prepare($clearItemsQuery);
            $stmt->bindparam(":orderid", $orderid);
            $stmt->execute(); */

            $insertItemQuery = "INSERT INTO orderitems (orderid, fooditemid, itemnumber, price) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertItemQuery);

            foreach ($items as $item) {
                $fooditemid = $item['fooditemid'];
                $itemnumber = $item['itemnumber'];
                $price = $item['price'];
                $stmt->execute([$orderid, $fooditemid, $itemnumber, $price]);
            }

            $conn->commit();
            return (["success" => true, "message" => "Order updated successfully"]);
        } catch (Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log("Failed to update order: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to update order'];
        }
    }

    /* protected function updateAllOrder($orderid, $totalcost, $amountowed, $items)
    {
        try {
            $conn = $this->connect();
            $conn->beginTransaction();
    
            // Update the order
            $query = "UPDATE orders SET totalcost = :totalcost, amountowed = :amountowed WHERE orderid = :orderid";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':orderid', $orderid);
            $stmt->bindParam(':totalcost', $totalcost);
            $stmt->bindParam(':amountowed', $amountowed);
            $stmt->execute();
    
            // Delete existing items
            $deleteQuery = "DELETE FROM orderitems WHERE orderid = :orderid";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bindParam(':orderid', $orderid);
            $deleteStmt->execute();
    
            // Insert updated items
            $insertQuery = "INSERT INTO orderitems (orderid, fooditemid, itemnumber, price) VALUES (:orderid, :fooditemid, :itemnumber, :price)";
            $insertStmt = $conn->prepare($insertQuery);
            foreach ($items as $item) {
                $fooditemid = $item['fooditemid'];
                $itemnumber = $item['itemnumber'];
                $price = $item['price'];
    
                $insertStmt->bindParam(':orderid', $orderid);
                $insertStmt->bindParam(':fooditemid', $fooditemid);
                $insertStmt->bindParam(':itemnumber', $itemnumber);
                $insertStmt->bindParam(':price', $price);
                $insertStmt->execute();
            }
    
            $conn->commit();
            return ['success' => true, 'message' => 'Order updated successfully'];
        } catch (Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log("Failed to update order: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to update order'];
        }
    }*/
    /*protected function removeOrderItem($orderid, $fooditemid)
    {
        try {
            $conn = $this->connect();
            $conn->beginTransaction();

            // Remove the order item
            $query = "DELETE FROM orderitems WHERE orderid = ? AND fooditemid = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$orderid, $fooditemid]);

            // Check if there are any remaining items in the order
            $selectQuery = "SELECT fooditemid FROM orderitems WHERE orderid = ?";
            $stmt = $conn->prepare($selectQuery);
            $stmt->execute([$orderid]);

            // If no items remain, delete the order
            if ($stmt->rowCount() == 0) {
                $deleteQuery = "DELETE FROM orders WHERE orderid = ?";
                $stmt = $conn->prepare($deleteQuery);
                $stmt->execute([$orderid]);
            }

            $conn->commit();
            return ['success' => true, 'message' => 'Order item removed successfully'];
        } catch (Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log("Failed to update order: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to update order'];
        }
    }*/
    protected function removeOrderItem($orderid, $fooditemid)
{
    try {
        $conn = $this->connect();
        $conn->beginTransaction();

        // Remove the order item
        $query = "DELETE FROM orderitems WHERE orderid = ? AND fooditemid = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$orderid, $fooditemid]);

        // Recalculate total cost and tax
        $selectQuery = "SELECT SUM(price * itemnumber) AS totalcost FROM orderitems WHERE orderid = ?";
        $stmt = $conn->prepare($selectQuery);
        $stmt->execute([$orderid]);
        $totalcost = $stmt->fetchColumn();

        // Calculate tax (assuming 10%)
        $tax = $totalcost * 0.1;
        $amountowed = $totalcost + $tax;

        // Update orders table with new totalcost and amountowed
        $updateQuery = "UPDATE orders SET totalcost = ?, amountowed = ? WHERE orderid = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute([$totalcost, $amountowed, $orderid]);

        $conn->commit();
        return ['success' => true, 'message' => 'Order item removed successfully', 'totalcost' => $totalcost, 'amountowed' => $amountowed];
    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log("Failed to update order: " . $e->getMessage());
        return ['success' => false, 'message' => 'Failed to update order'];
    }
}

}
