<?php
class Menu extends Dbconnection
{      

    protected function insertMenuType($menutype, $menudescription)
    {
        try {
            // Insert a new record with an auto-increment ID
            $sql = "INSERT INTO menus (type, description) VALUES (:menutype, :menudescription)";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(":menutype", $menutype);
            $stmt->bindParam(":menudescription", $menudescription);

          
            if ($stmt->execute()) {
                return ["success"=> true, "message"=>"Menu type added","menuid"=>parent::connect()->lastInsertId()];
            } else {

                throw new Exception("Failed to execute statement.");
                return ["success"=>"false","message"=>"failed to add and retrieve id"];
            }
        } catch (PDOException $e) {
          
            error_log("Failed to add menu: " . $e->getMessage());
            exit();
        }
    }

    //select one ccustomer
    protected function viewMenutype($id)
    {

        try {
            $query = "SELECT * FROM menus WHERE id= :id";
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
    protected function selectAllMenuType()
    {
        try {
            $query = "SELECT DISTINCT type FROM menus";
            $stmt = parent::connect()->prepare($query);
            $stmt->execute();
            $result= $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($result>0){
                return $result;
            }else {
                return [];
            }
        } catch (PDOException $e) {
            error_log("Could not select menu" . $e->getMessage());
            die();
        }
    }


    //update customer info

    protected function updateMenuType($id, $menutype, $menudescription)
    {
        try {
            $query = "UPDATE menus SET type=:menutype, description=:menudescription WHERE id=:id";
            $stmt = parent::connect()->prepare($query);

            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":menutype", $menutype);
            $stmt->bindParam(":menudescription", $menudescription);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Failed to update Menu!" . $e->getMessage());
            die();
        }
    }


    protected function deleteMenuType($id)
    {
        try {
            $query = "DELETE FROM menus WHERE id=:id";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":id", $id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die("could not delete menutype" . $e->getMessage());
        }
    }

    //limit and pagination
    protected function viewMenuWithLimit($limit, $offset, $search = '')
    {
        try {
            $conn = parent::connect();
            $sql = "SELECT *  FROM menus";

            if (!empty($search)) {
                $sql .= " WHERE type LIKE :search OR description LIKE :search";
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


    protected function getTotalMenuCount()
    {
        try {
            $stmt = parent::connect()->prepare("SELECT COUNT(*) as total FROM menus");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['total'] : 0;
        } catch (PDOException $e) {
            error_log("Failed to get count" . $e->getMessage());
            die();
        }
    }


     // Select specific ID based on type and description
     protected function selectTypeId($type, $description){
        try {
            $sql = "SELECT id,type FROM menus WHERE type = :type";
            $stmt = parent::connect()->prepare($sql);
            $type=strtolower($type);
            $stmt->bindParam(":type", $type);
           // $stmt->bindParam(":description", $description);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result>0) {
                return $result;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            die("Could not fetch ID: " . $e->getMessage());
        }
    }
     // Select specific ID based on type and description
     protected function checktypeexist($type){
        try {
            $type=strtolower($type);
            $sql = "SELECT type FROM menus WHERE type =:type";
            $stmt = parent::connect()->prepare($sql);
            $stmt->bindParam(":type", $type);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result >0 && $type==$result['type']) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die("Could not fetch ID: " . $e->getMessage());
        }
    }
}
