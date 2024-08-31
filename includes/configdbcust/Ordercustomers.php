<?php

class Ordercustomers extends Dbconnection
{

    private function formatTimeWithAmPm($time)
    {
        if (!$time) {
            return false; // Handle the invalid input appropriately
        }
        $dateTime = DateTime::createFromFormat('g:i A', $time);
        if ($dateTime === false) {
            return false; // Handle the invalid input appropriately
        }
        return $dateTime->format('h:i A');
    }

    //insert customers
    protected function InsertCustomers($firstname, $lastname, $email, $phone, $loyaltypoint) {
        try {
            $conn = parent::connect();
            $query = "INSERT INTO ordercustomers (firstname, lastname, email, phone, loyaltypoint) 
                      VALUES (:firstname, :lastname, :email, :phone, :loyaltypoint)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":firstname", $firstname);
            $stmt->bindParam(":lastname", $lastname);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":loyaltypoint", $loyaltypoint);
            if ($stmt->execute()) {
                $lastid = $conn->lastInsertId();
                return ["success" => true, "message" => "Customer successfully added", "customerid" => $lastid, "orderstatus"=>"pending"];
            } else {
                return ["success" => false, "message" => "Failed to add customer"];
            }
        } catch (PDOException $e) {
            error_log("Failed to add customer! " . $e->getMessage());
            die();
        }
    }
    
    protected function InsertCustomerstransaction($firstname, $lastname, $email, $phone, $loyaltypoint)
    {
        try {
            parent::connect()->beginTransaction();
            $query = "INSERT INTO ordercustomers (firstname,lastname,email,phone,loyaltypoint)
                 VALUES(:firstname,:lastname,:email,:phone,:loyaltypoint)";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":firstname", $firstname);
            $stmt->bindParam(":lastname", $lastname);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":loyaltypoint", $loyaltypoint);
            $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Failed to add customer!" . $e->getMessage());
            parent::connect()->rollBack();
            die();
        }
        try{
            $query = "SELECT LAST_INSERT_ID()";
            $stmt = parent::connect()->query($query);
            $result=$stmt->execute();
            if($stmt->execute()){
                return (["success"=>true,"message"=>"customer successfully added","customerid"=>$result]);
            }else{
                return false;
            }
        }
        catch(PDOException $e){
        if (parent::connect()->inTransaction()) {
            parent::connect()->rollBack(); // Rollback transaction on any exception
        }
        throw new Exception("data cannot be retrieved" . $e->getMessage());
    }
}

    //select one ccustomer
    protected function viewUser($customerid)
    {

        try {
            $query = "SELECT * FROM ordercustomers WHERE customerid= :customerid";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":customerid", $customerid);
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
    protected function selectAllOrderCustomers()
    {
        try {
            $query = "SELECT * FROM ordercustomers";
            $stmt = parent::connect()->prepare($query);
            $stmt->execute();
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                
                return $result;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            error_log("Could not select customers" . $e->getMessage());
            die();
        }
    }
    protected function retrieveOrderCustomers()
    {
        try {
            $query = "SELECT customerid,firstname,lastname FROM ordercustomers";
            $stmt = parent::connect()->prepare($query);
            $stmt->execute();
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                
                return $result;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            error_log("Could not select customers" . $e->getMessage());
            die();
        }
    }


    //update customer info

    protected function updateOrderCustomers($customerid, $firstname, $lastname, $email, $phone, $loyaltypoint)
    {
        try {
            $query = "UPDATE ordercustomers SET firstname=:firstname,lastname=:lastname, email=:email, phone=:phone, regdate=:regdate, loyaltypoint=:loyaltypoint WHERE customerid=:customerid";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":customerid", $customerid);
            $stmt->bindParam(":firstname", $firstname);
            $stmt->bindParam(":lastname", $lastname);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":regdate", $formattedDate);
            $stmt->bindParam(":loyaltypoint", $loyaltypoint);


            if ($stmt->execute()) {
                $lastid= parent::connect()->lastInsertId();
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Failed to update customers!" . $e->getMessage());
            die();
        }
    }


    protected function deleteOrderCustomer($customerid)
    {
        try {
            $query = "DELETE FROM ordercustomers WHERE customerid=:customerid";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":customerid", $customerid);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die("could not delete user" . $e->getMessage());
        }
    }

    //limit and pagination
    protected function viewOrderCustomersWithLimit($limit, $offset, $search = '')
    {
        try {
            $conn = parent::connect();
            $sql = "SELECT * FROM ordercustomers";

            if (!empty($search)) {
                $sql .= " WHERE firstname LIKE :search OR email LIKE :search";
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


    protected function getTotalorderCustomersCount()
    {
        try {
            $stmt = parent::connect()->prepare("SELECT COUNT(*) as total FROM ordercustomers");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['total'] : 0;
        } catch (PDOException $e) {
            error_log("Failed to get count" . $e->getMessage());
            die();
        }
    }
}
