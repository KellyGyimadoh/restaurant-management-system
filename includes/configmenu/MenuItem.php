<?php
class MenuItem extends Dbconnection
{

    protected function insertMenuItem($menusectionid, $fooditem, $itemdescription, $price, $image)
    {
        try {
            $sql = "INSERT INTO menusitem (menusection_id, fooditem, itemdescription, price, image) VALUES (:menusectionid, :fooditem, :itemdescription, :price, :image)";
            $stmt = $this->connect()->prepare($sql);
            // Convert the price to a float and format to two decimal places
            $formattedPrice = number_format((float)$price, 2, '.', '');

            $stmt->bindParam(':menusectionid', $menusectionid);
            $stmt->bindParam(':fooditem', $fooditem);
            $stmt->bindParam(':itemdescription', $itemdescription);
            $stmt->bindParam(':price', $formattedPrice);
            $stmt->bindParam(':image', $image);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Failed to add menu item: " . $e->getMessage());
            return false;
        }
    }

    protected function viewMenuItem($id)
    {
        try {
            $sql = "SELECT * FROM menusitem WHERE id = :id";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Data retrieval error: " . $e->getMessage());
        }
    }

    protected function viewMenuItemID($fooditem)
    {
        try {
            $sql = "SELECT id,price FROM menusitem WHERE fooditem = :fooditem";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':fooditem', $fooditem);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Data retrieval error: " . $e->getMessage());
        }
    }

    // Add more methods for update, delete, etc., similarly to the Menu class

    //select all from customers
    protected function selectAllMenuItems()
    {
        try {
            $query = "SELECT * FROM menusitem";
            $stmt = parent::connect()->prepare($query);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Could not select menu items" . $e->getMessage());
            die();
        }
    }


    //update customer info

    protected function updateMenuItem($id, $fooditem, $itemdescription, $price, $image, $menusectionid)
    {
        try {
            $query = "UPDATE menusitem SET fooditem=:fooditem, menusection_id=:menusectionid, itemdescription=:itemdescription, price=:price, image=:image WHERE id=:id";
            $stmt = parent::connect()->prepare($query);

            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":fooditem", $fooditem);
            $stmt->bindParam(":itemdescription", $itemdescription);
            $stmt->bindParam(":price", $price);
            $stmt->bindParam(":image", $image);
            $stmt->bindParam(":menusectionid", $menusectionid);
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


    protected function deleteMenuItem($id)
    {
        try {
            $query = "DELETE FROM menusitem WHERE id=:id";
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
    protected function viewMenuItemWithLimit($limit, $offset, $search = '')
    {
        try {
            $conn = parent::connect();
            $sql = "SELECT * FROM menusitem";

            if (!empty($search)) {
                $sql .= " WHERE fooditem LIKE :search OR itemdescription LIKE :search";
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


    protected function getTotalMenuItemCount()
    {
        try {
            $stmt = parent::connect()->prepare("SELECT COUNT(*) as total FROM menusitem");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['total'] : 0;
        } catch (PDOException $e) {
            error_log("Failed to get count" . $e->getMessage());
            die();
        }
    }


    // Retrieve a menu item with its section details
    protected function getMenuItemWithSection($menuitemId)
    {
        try {
            $sql = "SELECT menusitem.*, menusections.name AS section_name
                    FROM menusitems
                    INNER JOIN menusections ON menusitem.menusection_id = menusections.id
                    WHERE menusitem.id = :id";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':id', $menuitemId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Data retrieval error: " . $e->getMessage());
        }
    }
    // Function to update both menu item and its section


    // Existing methods...

    protected function updateMenuItemWithSectionAndType($fooditemid, $fooditem, $itemdescription, $price, $image, $menusectionId, $newSectionName, $menutype, $menudescription, $menuid)
    {
        try {
            $db = $this->connect();
            $db->beginTransaction();

            // Update menu item
            $sql = "UPDATE menusitem SET fooditem=:fooditem, itemdescription=:itemdescription, price=:price, image=:image, menusection_id=:menusection_id WHERE id=:id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $fooditemid);
            $stmt->bindParam(':fooditem', $fooditem);
            $stmt->bindParam(':itemdescription', $itemdescription);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':menusection_id', $menusectionId);
            $stmt->execute();

            // Update section
            $sql = "UPDATE menusections SET section=:section WHERE id=:id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $menusectionId);
            $stmt->bindParam(':section', $newSectionName);
            $stmt->execute();

            // Update main menu
            $sql = "UPDATE menus SET type=:menutype, description=:menudescription WHERE id=:id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":id", $menuid);
            $stmt->bindParam(":menutype", $menutype);
            $stmt->bindParam(":menudescription", $menudescription);
            $stmt->execute();

            $db->commit();
            return true;
        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            error_log("Failed to update menu item and section: " . $e->getMessage());
            throw new Exception("Failed to update menu item and section: " . $e->getMessage());
        }
    }



    //check if item exist

    protected function checkfoodexist($fooditem)
    {
        try {
            $query = "SELECT fooditem FROM menusitem WHERE fooditem=:fooditem";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":fooditem", $fooditem);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result > 0 && $fooditem === $result['fooditem']) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Could not select menu items" . $e->getMessage());
            die();
        }
    }
}
