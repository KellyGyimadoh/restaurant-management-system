<?php

class Orderitems extends Dbconnection{
   
   
    //insert orderitems
    protected function InsertOrderitems($orderid,$fooditemid,$itemnumber,$price,$tax,$totalcost) {
        try {
            $conn = parent::connect();
            $query = "INSERT INTO orderitems (orderid,fooditemid,itemnumber,price,tax,totalcost) 
                      VALUES (:orderid,:fooditemid,:itemnumber,:price,:tax,:totalcost)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":orderid", $orderid);
            $stmt->bindParam(":fooditemid", $fooditemid);
            $stmt->bindParam(":itemnumber", $itemnumber);
            $stmt->bindParam(":price", $price);
            $stmt->bindParam(":tax", $tax);
            $stmt->bindParam(":totalcost", $totalcost);
           
            if ($stmt->execute()) {
              
                return ["success" => true, "message" => "New Item processed"];
            } else {
                return ["success" => false, "message" => "Failed to add item"];
            }
        } catch (PDOException $e) {
            error_log("Failed to add customer! " . $e->getMessage());
            die();
        }
    }
    
  
    //select one ccustomer
    protected function viewOrderitem($orderitemid)
    {

        try {
            $query = "SELECT * FROM orderitems WHERE orderitemid= :orderitemid";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":orderitemid", $orderitemid);
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
    protected function viewOrderitemAndId($orderid)
    {

        try {
            $query = "SELECT it.fooditemid,  GROUP_CONCAT(fi.fooditem SEPARATOR ', ') 
            AS items,  SUM(it.itemnumber) AS totalquantity  FROM orderitems AS it JOIN menusitem AS fi ON it.fooditemid= fi.id WHERE orderid=:orderid";
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


    //select all from customers
    protected function selectAllOrderitems()
    {
        try {
            $query = "SELECT * FROM orderitems";
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

    protected function updateOrders($orderid,$fooditemid,$itemnumber,$price,$tax,$totalcost)
    {
        try {
            $query = "UPDATE orderitems SET orderid=:orderid,totalcost=:totalcost,fooditemid=:fooditemid
            ,itemnumber=:itemnumber,price=:price, tax=:tax WHERE orderitemid=:orderitemid";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":orderid", $orderid);
            $stmt->bindParam(":totalcost", $totalcost);
            $stmt->bindParam(":fooditemid", $fooditemid);
            $stmt->bindParam(":itemnumber", $itemnumber);
            $stmt->bindParam(":price", $price);
            $stmt->bindParam(":tax", $tax);
            $stmt->bindParam(":orderitemid", $orderitemid);
            if ($stmt->execute()) {
               
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Failed to update orders!" . $e->getMessage());
            die();
        }
    }

  

   


    protected function deleteOrderitem($orderitemid)
    {
        try {
            $query = "DELETE FROM orderitems WHERE orderitemid=:orderitemid";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":orderitemid", $orderitemid);
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
    protected function viewOrderItemsWithLimit($limit, $offset, $search = '')
    {
        try {
            $conn = parent::connect();
            $sql = "SELECT * FROM orderitems";

            if (!empty($search)) {
                $sql .= " WHERE fooditemid LIKE :search OR price LIKE :search";
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


    protected function getTotalOrderItemsCount()
    {
        try {
            $stmt = parent::connect()->prepare("SELECT COUNT(*) as total FROM orderitems");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['total'] : 0;
        } catch (PDOException $e) {
            error_log("Failed to get count" . $e->getMessage());
            die();
        }
    }
}

