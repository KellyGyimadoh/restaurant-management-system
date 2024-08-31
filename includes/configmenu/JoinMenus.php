<?php
class JoinMenus extends Dbconnection
{
    protected function joinAllMenus($limit, $offset, $search = '')
    {
        try {
            $conn = parent::connect();
            $sql = "SELECT
                        mi.id AS fooditem_id,
                        mt.type AS menu_type,
                        ms.section AS section_name,
                        mi.fooditem,
                        mi.itemdescription,
                        mi.price
                    FROM 
                        menus AS mt
                    JOIN 
                        menusections AS ms ON mt.id = ms.menu_id
                    JOIN 
                        menusitem AS mi ON ms.id = mi.menusection_id";
            
            // Add search condition if provided
            if (!empty($search)) {
                $sql .= " WHERE mt.type LIKE :search OR mi.itemdescription LIKE :search OR mi.fooditem LIKE :search";
            }
        
            // Add order by and limit/offset clauses
            $sql .= " ORDER BY mt.type, ms.section, mi.fooditem LIMIT :limit OFFSET :offset";
        
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


     //limit and pagination
   
 
 
    
         protected function getAllMenuCount($search = '')
         {
             try {
                 $conn = parent::connect();
                 $sql = "SELECT COUNT(*) as total
                         FROM 
                             menus mt
                         JOIN 
                             menusections ms ON mt.id = ms.menu_id
                         JOIN 
                             menusitem mi ON ms.id = mi.menusection_id";
                 
                 // Add search condition if provided
                 if (!empty($search)) {
                     $sql .= " WHERE mt.type LIKE :search OR mi.itemdescription LIKE :search";
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
