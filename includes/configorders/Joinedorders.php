<?php
class Joinedorders extends Dbconnection
{
    protected function joinAllOrders($limit, $offset, $search = '')
    { 
        try {
            $conn = parent::connect();
            $sql = "SELECT
                        ord.orderid,
                        ord.customerid,
                        ord.orderdate,
                        ord.orderstatus,
                        ord.amountowed,
                        ord.amountpaid,
                        ord.receiptnumber,
                        ordcust.firstname AS customerfirstname,
                        ordcust.lastname AS customerlastname,
                        GROUP_CONCAT(fi.fooditem SEPARATOR ', ') AS items,
                        SUM(it.itemnumber) AS totalquantity,
                        SUM(fi.price * it.itemnumber) AS totalcost,
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
                        payments AS pay ON ord.orderid = pay.orderid";
            
            // Add search condition if provided
            if (!empty($search)) {
                $sql .= " WHERE ordcust.firstname LIKE :search OR ordcust.lastname LIKE :search OR fi.fooditem LIKE :search";
            }
        
            // Add group by, order by and limit/offset clauses
            $sql .= " GROUP BY ord.orderid
                      ORDER BY ord.orderdate DESC
                      LIMIT :limit OFFSET :offset";
        
            $stmt = $conn->prepare($sql);
        
            // Bind search parameter if provided
            if (!empty($search)) {
                $searchParam = '%' . $search . '%';
                $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
            }
        
            // Bind limit and offset parameters
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        } catch (PDOException $e) {
            error_log("Failed to join: " . $e->getMessage());
            return []; // Return an empty array in case of error
        }
    }

    // Method to get the total count of orders for pagination
    protected function getAllOrdersCount($search = '')
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


/*"SELECT
ord.orderid,
ord.customerid,
ord.orderdate,
ord.orderstatus,
ord.amountowed,
ord.amountpaid,
ord.receiptnumber,
ordcust.firstname AS customerfirstname,
ordcust.lastname AS customerlastname,
GROUP_CONCAT(fi.fooditem SEPARATOR ', ') AS items,
SUM(it.itemnumber) AS totalquantity,
SUM(fi.price * it.itemnumber) AS totalcost,
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
payments AS pay ON ord.orderid = pay.orderid";*/