<?php
class Shop extends Dbconnection
{      

    protected function insertShopInfo($name,$email,$phone,$address,$website,$description,$city,$country,
    $state,$postalcode,$openinghours,$status)
    {
        try {
            // Insert a new record with an auto-increment ID
            $sql = "INSERT INTO shop (name,email,phone,address,website,description,city,country,
    state,postal_code,opening_hours,status) VALUES (:name,:email,:phone,:address,:website,:description,:city,:country,
    :state,:postal_code,:opening_hours,:status)";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":address", $address);
            $stmt->bindParam(":website", $website);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":city", $city);
            $stmt->bindParam(":country", $country);
            $stmt->bindParam(":state", $state);
            $stmt->bindParam(":postal_code", $postalcode);
            $stmt->bindParam(":opening_hours", $openinghours);
            $stmt->bindParam(":status",$status);

          
            if ($stmt->execute()) {
                return ["success"=> true, "message"=>"Info added successfully"];
            } else {

                throw new Exception("Failed to execute statement.");
                return ["success"=>"false","message"=>"failed to add info"];
            }
        } catch (PDOException $e) {
          
            error_log("Failed to add info: " . $e->getMessage());
            exit();
        }
    }

    //select one ccustomer
    protected function viewShopinfo($id)
    {

        try {
            $query = "SELECT * FROM shop WHERE id= :id";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":id", $id);
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

    //select all from MENUS
   


    //update customer info

    protected function updateShopinfo($id,$name,$email,$phone,$address,$website,$description,$city,$country,
    $state,$postalcode,$openinghours,$status)
    {
        try {
            $query = "UPDATE shop SET name=:name,email=:email,phone=:phone,
            address=:address,website=:website,description=:description,city=:city,country=:country,
    state=:state,postal_code=:postal_code,opening_hours=:opening_hours,status=:status WHERE id=:id";
            $stmt = parent::connect()->prepare($query);

            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":address", $address);
            $stmt->bindParam(":website", $website);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":city", $city);
            $stmt->bindParam(":country", $country);
            $stmt->bindParam(":state", $state);
            $stmt->bindParam(":postal_code", $postalcode);
            $stmt->bindParam(":opening_hours", $openinghours);
            $stmt->bindParam(":status", $status);

          
            if ($stmt->execute()) {
                return ['success'=>true, "message"=>'Shop info successfully updated'];
            } else {
                return ['success'=>false, "message"=>'Shop info Failed to update'];
            }
        } catch (PDOException $e) {
            error_log("Failed to update shop!" . $e->getMessage());
            return ['success'=>false, "message"=> $e->getMessage()];
            die();
        }
    }

    protected function updateShopStatus($id,$status)
    {
        try {
            $query = "UPDATE shop SET status=:status WHERE id=:id";
            $stmt = parent::connect()->prepare($query);

            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":status", $status);

          
            if ($stmt->execute()) {
                return ['success'=>true, "message"=>'Shop Status successfully updated'];
            } else {
                return ['success'=>false, "message"=>'Shop Status Failed to update'];
            }
        } catch (PDOException $e) {
            error_log("Failed to update shop status!" . $e->getMessage());
            return ['success'=>false, "message"=> $e->getMessage()];
            die();
        }
    }

    protected function deleteShop($id)
    {
        try {
            $query = "DELETE FROM shop WHERE id=:id";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":id", $id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die("could not delete shop" . $e->getMessage());
        }
    }

    //limit and pagination
    protected function viewShopWithLimit($limit, $offset, $search = '')
    {
        try {
            $conn = parent::connect();
            $sql = "SELECT *  FROM shop";

            if (!empty($search)) {
                $sql .= " WHERE name LIKE :search OR description LIKE :search";
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


    protected function getTotalShopCount()
    {
        try {
            $stmt = parent::connect()->prepare("SELECT COUNT(*) as total FROM shop");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['total'] : 0;
        } catch (PDOException $e) {
            error_log("Failed to get count" . $e->getMessage());
            die();
        }
    }


   
}
