<?php

class Receipt extends Dbconnection{
   
    private function generateReceiptNumber($orderId) {
        return 'PAY-' . str_pad($orderId, 8, '0', STR_PAD_LEFT) . '-' . time();
    }
    //insert payment
    protected function InsertReceipt($paymentid,$orderid,$orderreceipt,$amountpaid,$balance) {
        $paymentreceipt=$this->generateReceiptNumber($orderid);
        try {
            $conn = parent::connect();
            $query = "INSERT INTO receipt (paymentid,orderid,orderreceipt,paymentreceipt,amountpaid,balance) 
                      VALUES (:paymentid,:orderid,:orderreceipt,:paymentreceipt,:amountpaid,:balance)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":orderid", $orderid);
            $stmt->bindParam(":paymentid", $paymentid);
            $stmt->bindParam(":orderreceipt", $orderreceipt);
            $stmt->bindParam(":paymentreceipt", $paymentreceipt);
            $stmt->bindParam(":amountpaid", $amountpaid);
            $stmt->bindParam(":balance", $balance);
           
           
           
            if ($stmt->execute()) {
              
                return ["success" => true, "message" => " Receipt processed"];
            } else {
                return ["success" => false, "message" => "Failed to process receipt "];
            }
        } catch (PDOException $e) {
            error_log("Failed to process payment! " . $e->getMessage());
            die();
        }
    }
    //select payment with orderid
    protected function viewPaymentStatus($orderid)
    {

        try {
            $query = "SELECT * FROM payments WHERE orderid= :orderid";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":orderid", $orderid);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return $result;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            throw new Exception("data cannot be retrieved" . $e->getMessage());
        }
    }  
  
    //select one ccustomer
    protected function viewPayment($paymentid)
    {

        try {
            $query = "SELECT * FROM payments WHERE paymentid= :paymentid";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":paymentid", $paymentid);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return $result;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            throw new Exception("data cannot be retrieved" . $e->getMessage());
        }
    }

    //select all from customers
    protected function selectAllPayments()
    {
        try {
            $query = "SELECT * FROM payments";
            $stmt = parent::connect()->prepare($query);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Could not select orders items" . $e->getMessage());
            die();
        }
    }


    //update customer info

    protected function updateOrders($orderid,$amountpaid,$paymentmethod,$paymentid)
    {
        try {
            $query = "UPDATE payments SET orderid=:orderid,amountpaid=:amountpaid,paymentmethod=:paymentmethod
             WHERE paymentid=:paymentid";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":orderid", $orderid);
            $stmt->bindParam(":amountpaid", $amountpaid);
            $stmt->bindParam(":paymentmethod", $paymentmethod);
            $stmt->bindParam(":paymentid", $paymentid);
           
            if ($stmt->execute()) {
               
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Failed to update Payment!" . $e->getMessage());
            die();
        }
    }

    protected function updatePayment($orderid, $amountpaid, $paymentmethod, $paymentstatus) {
        try {
            $query = "UPDATE payments SET amountpaid=:amountpaid, paymentmethod=:paymentmethod, paymentstatus=:paymentstatus WHERE orderid=:orderid";
            $stmt = parent::connect()->prepare($query);
    
            // Bind parameters
            $stmt->bindParam(":orderid", $orderid);
            $stmt->bindParam(":amountpaid", $amountpaid);
            $stmt->bindParam(":paymentmethod", $paymentmethod);
            $stmt->bindParam(":paymentstatus", $paymentstatus);
    
            // Execute the statement
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Failed to update Payment! " . $e->getMessage());
            return false;
        }
    }
    


   


    protected function deleteOrderitem($paymentid)
    {
        try {
            $query = "DELETE FROM payments WHERE paymentid=:paymentid";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":paymentid", $paymentid);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die("could not delete order item" . $e->getMessage());
        }
    }

    //limit and pagination
    protected function viewOrderCustomersWithLimit($limit, $offset, $search = '')
    {
        try {
            $conn = parent::connect();
            $sql = "SELECT * FROM payments";

            if (!empty($search)) {
                $sql .= " WHERE paymentmethod LIKE :search OR paymentdate LIKE :search";
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
            error_log("search operation failure!" . $e->getMessage());
            die();
        }
    }


    protected function getTotalPaymentordersCount()
    {
        try {
            $stmt = parent::connect()->prepare("SELECT COUNT(*) as total FROM payments");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['total'] : 0;
        } catch (PDOException $e) {
            error_log("Failed to get count" . $e->getMessage());
            die();
        }
    }

    protected function getSalesData() {
        try {
            $conn = parent::connect();
            
            // Get today's date, the start of the month, and the start of the year
            $today = date('Y-m-d');
            $startOfMonth = date('Y-m-01');
            $startOfYear = date('Y-01-01');
    
            // Query to get today's sales (considering only the date part)
            $queryToday = "SELECT SUM(amountpaid) AS todaysales FROM payments WHERE DATE(paymentdate) = :today";
            $stmtToday = $conn->prepare($queryToday);
            $stmtToday->bindParam(':today', $today);
            $stmtToday->execute();
            $todaySales = $stmtToday->fetch(PDO::FETCH_ASSOC)['todaysales'] ?? 0;
    
            // Debug: Print the query and result
            error_log("Today's sales query: $queryToday, Result: $todaySales");
    
            // Query to get this month's sales
            $queryMonth = "SELECT SUM(amountpaid) AS monthsales FROM payments WHERE DATE(paymentdate) >= :startOfMonth";
            $stmtMonth = $conn->prepare($queryMonth);
            $stmtMonth->bindParam(':startOfMonth', $startOfMonth);
            $stmtMonth->execute();
            $monthSales = $stmtMonth->fetch(PDO::FETCH_ASSOC)['monthsales'] ?? 0;
    
            // Debug: Print the query and result
            error_log("This month's sales query: $queryMonth, Result: $monthSales");
    
            // Query to get this year's sales
            $queryYear = "SELECT SUM(amountpaid) AS yearsales FROM payments WHERE DATE(paymentdate) >= :startOfYear";
            $stmtYear = $conn->prepare($queryYear);
            $stmtYear->bindParam(':startOfYear', $startOfYear);
            $stmtYear->execute();
            $yearSales = $stmtYear->fetch(PDO::FETCH_ASSOC)['yearsales'] ?? 0;
    
            // Debug: Print the query and result
            error_log("This year's sales query: $queryYear, Result: $yearSales");
    
            // Return the sales data
            return [
                'success' => true,
                'todaysales' => $todaySales,
                'monthsales' => $monthSales,
                'yearsales' => $yearSales,
            ];
    
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error retrieving sales data: ' . $e->getMessage(),
            ];
        }
    }
    



}

