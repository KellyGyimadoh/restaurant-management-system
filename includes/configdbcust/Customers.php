<?php

class Customers extends Dbconnection
{

    private function formatTimeWithAmPm($time) {
        if (!$time) {
        return false; // Handle the invalid input appropriately
        }
        $dateTime = DateTime::createFromFormat('g:i A', $time);
        if ($dateTime === false) {
        return false; // Handle the invalid input appropriately
        }
        return $dateTime->format('h:i A');
        }


        //select one ccustomer
        protected function viewUser($id){
            
            try {
               $query="SELECT * FROM customers WHERE id= :id";
               $stmt=parent::connect()->prepare($query);
               $stmt->bindParam(":id",$id);
               $stmt->execute();
               $result=$stmt->fetch(PDO::FETCH_ASSOC);
               if($result){
                return $result;
               }else{
                return [];
               }

            } catch (PDOException $e) {
                throw new Exception("data cannot be retrieved".$e->getMessage());
            }
        }

    //select all from customers
    public function selectAllCustomers()
    {
        try {
            $query = "SELECT * FROM customers";
            $stmt = parent::connect()->prepare($query);
            $stmt->execute();
                $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($result)){
                 return $result;
                }else{
                 
                 return [];
                }
        } catch (PDOException $e) {
            error_log("Could not select customers" . $e->getMessage());
            die();
        }
    } 
    public function selectAllCustomersEmail()
    {
        try {
            $query = "SELECT email FROM customers";
            $stmt = parent::connect()->prepare($query);
            $stmt->execute();
                $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($result)){
                 return $result;
                }else{
                 
                 return [];
                }
        } catch (PDOException $e) {
            error_log("Could not select customers" . $e->getMessage());
            die();
        }
    }

    protected function getTheRecentCustomer() {
        try {
            $conn = parent::connect();
            $query = "SELECT name, phone FROM customers ORDER BY  date_reg DESC  LIMIT 1";
            $stmt = $conn->prepare($query);
    
            if ($stmt->execute()) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Could not select customers: " . $e->getMessage());
            die();
        }
    }
    //retrieve lastest message
    protected function getTheRecentCustomerMessage() {
        try {
            $conn = parent::connect();
            $query = "SELECT message, phone,date_reg FROM customers ORDER BY  date_reg DESC  LIMIT 1";
            $stmt = $conn->prepare($query);
    
            if ($stmt->execute()) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Could not select customers: " . $e->getMessage());
            die();
        }
    }
    //update customer info

    protected function updateCustomers($id, $name, $email, $phone, $date, $time, $message)
    {
        try {
            $query = "UPDATE customers SET name=:name, email=:email, phone=:phone, date=:date, time=:time, message=:message WHERE id=:id";
            $stmt = parent::connect()->prepare($query);
            $formattedDate = date('Y-m-d', strtotime($date));
            $formattedtime=$this->formatTimeWithAmPm($time);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":date", $formattedDate);
            $stmt->bindParam(":time", $formattedtime);
            $stmt->bindParam(":message", $message);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Failed to update customers!" . $e->getMessage());
            die();
        }
    }


    protected function deleteCustomer($id)
    {
        try {
            $query = "DELETE FROM customers WHERE id=:id";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":id", $id);
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
     protected function viewTableWithLimit($limit, $offset, $search = '') {
        try{
        $conn = parent::connect();
        $sql = "SELECT * FROM customers";
        
        if (!empty($search)) {
            $sql .= " WHERE name LIKE :search OR email LIKE :search";
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
    }catch(PDOException $e){
        error_log("search operation failure!".$e->getMessage());
        die();
    }
    }


    protected function getTotalUsersCount() {
        try{
        $stmt = parent::connect()->prepare("SELECT COUNT(*) as total FROM customers");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total'] : 0;
        }catch(PDOException $e){
            error_log("Failed to get count".$e->getMessage());
            die();
        }
    }


}
