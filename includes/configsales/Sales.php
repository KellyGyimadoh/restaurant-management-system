<?php
class Sales extends Dbconnection {
    protected function getTodaySales($limit, $offset, $search = '') { 
        try {
            $conn = parent::connect();
            $today = date('Y-m-d');

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
                        payments AS pay ON ord.orderid = pay.orderid
                    WHERE DATE(paymentdate) = :today 
                    GROUP BY ord.orderid 
                    ORDER BY ord.orderdate DESC
                    LIMIT :limit OFFSET :offset";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':today', $today);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();
            $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'result' => $sales,
            ];
        } catch (PDOException $e) {
            error_log("Failed to get today sales: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error retrieving sales data: ' . $e->getMessage(),
            ];
        }
    }

    protected function getAllSalesTodayCount($search = '') {
        try {
            $conn = parent::connect();
            $today = date('Y-m-d');
            $sql = "SELECT COUNT(DISTINCT ord.orderid) as total
                    FROM 
                        orders AS ord
                    JOIN 
                        orderitems AS it ON ord.orderid = it.orderid
                    JOIN 
                        menusitem AS fi ON it.fooditemid = fi.id
                    LEFT JOIN 
                        payments AS pay ON ord.orderid = pay.orderid
                    WHERE DATE(paymentdate) = :today";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':today', $today);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
            return $result ? $result['total'] : 0;
        } catch (PDOException $e) {
            error_log("Failed to get count: " . $e->getMessage());
            return 0; // Return 0 in case of error
        }
    }
}
?>
