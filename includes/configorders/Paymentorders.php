<?php

class Paymentorders extends Dbconnection{
   
    private function generateReceiptNumber($orderId) {
        return 'PAY-' . str_pad($orderId, 8, '0', STR_PAD_LEFT) . '-' . time();
    }
    //insert payment
    protected function InsertPayment($orderid,$amountpaid,$paymentmethod,$paymentstatus) {
        $receiptnumber=$this->generateReceiptNumber($orderid);
        try {
            $conn = parent::connect();
            $query = "INSERT INTO payments (orderid,amountpaid,paymentmethod,receiptnumber,paymentstatus) 
                      VALUES (:orderid,:amountpaid,:paymentmethod,:receiptnumber,:paymentstatus)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":orderid", $orderid);
            $stmt->bindParam(":amountpaid", $amountpaid);
            $stmt->bindParam(":paymentmethod", $paymentmethod);
            $stmt->bindParam(":receiptnumber", $receiptnumber);
            $stmt->bindParam(":paymentstatus", $paymentstatus);
           
           
            if ($stmt->execute()) {
              
                return ["success" => true, "message" => " Payment processed"];
            } else {
                return ["success" => false, "message" => "Failed to process Payment "];
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
            $queryToday = "SELECT SUM(amountpaid) AS todaysales FROM payments WHERE DATE(paymentdate)=:today";
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
    
    protected function getSalesToday() {
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
    protected function GetTodaySales($limit, $offset, $search = '')
    { 
        try {
            $conn = parent::connect();
             // Get today's date, the start of the month, and the start of the year
             $today = date('Y-m-d');
            // $startOfMonth = date('Y-m-01');
             //$startOfYear = date('Y-01-01');
            $sql = "SELECT
                        ord.orderid,
                        ord.customerid,
                        GROUP_CONCAT(fi.fooditem SEPARATOR ', ') AS items,
                        SUM(it.itemnumber) AS totalquantity,
                        SUM(fi.price * it.itemnumber) AS totalcost,
                        pay.paymentstatus,
                        pay.amountpaid
                    FROM 
                        orders AS ord
                     JOIN 
                        orderitems AS it ON ord.orderid = it.orderid
                    JOIN 
                        menusitem AS fi ON it.fooditemid = fi.id
                    LEFT JOIN 
                        payments AS pay ON ord.orderid = pay.orderid";
            
          
            // Add group by, order by and limit/offset clauses
            if (!empty($search)) {
                $sql .= " WHERE  fi.fooditem LIKE :search";
            }
        
            $sql .= " 
            WHERE DATE(paymentdate)=:today GROUP BY ord.orderid ORDER BY ord.orderdate DESC
            LIMIT :limit OFFSET :offset";
        
            $stmt = $conn->prepare($sql);
            if (!empty($search)) {
                $searchParam = '%' . $search . '%';
                $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
            }
        
            // Bind limit and offset parameters
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
           $stmt->bindParam(':today', $today);
            $stmt->execute();
            $todaySales = $stmt->fetch(PDO::FETCH_ASSOC)['todaysales'] ?? 0;
            return [
                'success' => true,
                'todaysales' => $todaySales,
            ];
        } catch (PDOException $e) {
            error_log("Failed to get today sales: " . $e->getMessage());
            return []; // Return an empty array in case of error
        }
    }

    // Method to get the total count of orders for pagination
    protected function getAllSalesTodayCount($search = '')
    {
        try {
            $conn = parent::connect();
            $sql = "SELECT COUNT(DISTINCT ord.orderid) as total
                    FROM 
                        orders AS ord
                    JOIN 
                        ordercustomers AS ordcust ON ord.customerid = ordcust.customerid
                    JOIN 
                        orderitems AS it ON ord.orderid = it.orderid
                    JOIN 
                        menusitem AS fi ON it.fooditemid = fi.id";
            
            // Add search condition if provided
            if (!empty($search)) {
                $sql .= " WHERE ordcust.firstname LIKE :search OR ordcust.lastname LIKE :search OR fi.fooditem LIKE :search";
            }
        
            $stmt = $conn->prepare($sql);
        
            // Bind search parameter if provided
            if (!empty($search)) {
                $searchParam = '%' . $search . '%';
                $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
            }
        
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
            return $result ? $result['total'] : 0;
        } catch (PDOException $e) {
            error_log("Failed to get count: " . $e->getMessage());
            return 0; // Return 0 in case of error
        }
    }

}

