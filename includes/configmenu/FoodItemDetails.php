<?php
class FoodItemDetails extends Dbconnection
{
    protected function getFoodItemDetails($id)
    {
        try {
            $sql = "SELECT 
                        mt.type AS menu_type,
                        ms.menu_id AS typeid,
                        mi.menusection_id AS sectionid,
                        ms.section AS section_name,
                        mi.id,
                        mi.fooditem,
                        mi.itemdescription,
                        mi.price,
                        mi.image
                    FROM 
                        menusitem AS mi
                    JOIN 
                        menusections AS ms ON mi.menusection_id = ms.id
                    JOIN 
                        menus mt ON ms.menu_id = mt.id
                    WHERE mi.id = :id";

            $stmt = parent::connect()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Failed to fetch food item details: " . $e->getMessage());
            return [];
        }
    }

   /* protected function deletefooditem(){
                try {
                   $sql="DELETE FROM menus"
                } catch (PDOException $e) {
                    die("failed to delete".$e->getMessage());
                }
        
    }*/

    protected function viewfoodItemID($fooditem)
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
    protected function viewfoodItemAndPrice()
    {
        try {
            $sql = "SELECT id,price,fooditem FROM menusitem ";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Data retrieval error: " . $e->getMessage());
        }
    }

}
