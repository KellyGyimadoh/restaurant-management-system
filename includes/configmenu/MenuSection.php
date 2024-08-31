<?php
class Menusection extends Dbconnection
{

    protected function insertSection($menuid, $sectionName)
    {
        try {
            $sql = "INSERT INTO menusections (menu_id, section) VALUES (:menuid, :section)";
            $stmt = parent::connect()->prepare($sql);
            $stmt->bindParam(":menuid", $menuid);
            $stmt->bindParam(":section", $sectionName);
            
            if($stmt->execute()){
                //$lastid=$this->connect()->lastInsertId();
                return ["success"=>true, "message"=>"section added"];
            }else{
                return false;
            }
            // Introducing a small delay to ensure the insert operation is completed
           // usleep(50000); // 50 milliseconds
            

        } catch (PDOException $e) {
            error_log("Failed to add section: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }


    protected function viewSectionmenu($id)
    {
        try {
            $sql = "SELECT * FROM menusections WHERE id = :id";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Data retrieval error: " . $e->getMessage());
        }
    }

    // Add more methods for update, delete, etc., similarly to the Menu class
    // Add more methods for update, delete, etc., similarly to the Menu class

    //select all from customers
    protected function selectAllMenuSection()
    {
        try {
            $query = "SELECT DISTINCT section FROM menusections";
            $stmt = parent::connect()->prepare($query);
            $stmt->execute();
            $result= $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($result>0){
                return $result;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            error_log("Could not select menu section" . $e->getMessage());
            die();
        }
    }


    //update customer info

    protected function updateMenuSection($id, $section, $menuid)
    {
        try {
            $query = "UPDATE menusections SET section=:section, menu_id=:menuid WHERE id=:id";
            $stmt = parent::connect()->prepare($query);

            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":section", $section);
            $stmt->bindParam(":menuid", $menuid);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Failed to update MenuSection!" . $e->getMessage());
            die();
        }
    }


    protected function deleteMenuSection($id)
    {
        try {
            $query = "DELETE FROM menusections WHERE id=:id";
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
    protected function viewMenuSectionWithLimit($limit, $offset, $search = '')
    {
        try {
            $conn = parent::connect();
            $sql = "SELECT * FROM menusections";

            if (!empty($search)) {
                $sql .= " WHERE section LIKE :search OR menu_id LIKE :search";
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


    protected function getTotalMenuSectionCount()
    {
        try {
            $stmt = parent::connect()->prepare("SELECT COUNT(*) as total FROM menusections");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['total'] : 0;
        } catch (PDOException $e) {
            error_log("Failed to get count" . $e->getMessage());
            die();
        }
    }


    // Retrieve a menu item with its section details
    protected function getMenuSectionWithMenu($menusectionId)
    {
        try {
            $sql = "SELECT menusections.*, menus.section AS section_name
                    FROM menusections
                    INNER JOIN menus ON menusections.menu_id = menus.id
                    WHERE menusections.id = :id";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':id', $menusectionId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Data retrieval error: " . $e->getMessage());
        }
    }
    // Function to update both menu item and its section
    protected function updateMenuSectionWithMainMenu($menusectionId, $section, $menuId, $newMenuName)
    {
        try {
            $this->connect()->beginTransaction();

            // Update menu item
            $sql = "UPDATE menusections SET section = :section, menu_id = :menuid WHERE id = :id";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':id', $menusectionId);
            $stmt->bindParam(':section', $section);

            $stmt->bindParam(':menuid', $menuId);
            $stmt->execute();

            // Update section
            $sql = "UPDATE menus SET section = :section WHERE id = :id";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':id', $menuId);
            $stmt->bindParam(':section', $newMenuName);
            $stmt->execute();

            $this->connect()->commit();
            return true;
        } catch (PDOException $e) {
            $this->connect()->rollBack();
            throw new Exception("Failed to update menu item and section: " . $e->getMessage());
        }
    }
    protected function checkExistingDescription($menuid, $section) {
        try {
            $sql = "SELECT id FROM menusections WHERE menu_id = :menuid AND section = :section";
            $stmt = parent::connect()->prepare($sql);
            $stmt->bindParam(":menuid", $menuid);
            $stmt->bindParam(":section", $section);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Failed to check existing description: " . $e->getMessage());
            return false;
        }
}

  // Select specific ID based on type and description
  protected function selectMenuSectionId($section){
    try {
        $sql = "SELECT id,section FROM menusections WHERE section = :section";
        $stmt = parent::connect()->prepare($sql);
        $stmt->bindParam(":section", $section);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
            return $result;
        } else {
            return [];
        }
    } catch (PDOException $e) {
        die("Could not fetch ID: " . $e->getMessage());
    }
}

protected function checkSectionexist($section){
    try {
        $section=strtolower($section);
        $sql = "SELECT section FROM menusections WHERE section =:section";
        $stmt = parent::connect()->prepare($sql);
        $stmt->bindParam(":section", $section);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result >0 && $section==$result['section']) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        die("Could not fetch ID: " . $e->getMessage());
    }
}

}