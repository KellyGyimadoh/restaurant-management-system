<?php
class Mail extends Dbconnection{
    protected function InsertMail($subject,$recipient,$message){
        try {
            $conn=parent::connect();
            $query='INSERT INTO maillog (subject,recipient,message) VALUES(:subject,:recipient,:message)';
            $stmt=$conn->prepare($query);
            $stmt->bindParam(":subject",$subject);
            $stmt->bindParam(":recipient",$recipient);
            $stmt->bindParam(":message",$message);
            if($stmt->execute()){
                return ['success'=>true,'message'=>'Mail logged successfully'];
            }else{
                return ['success'=>false,'message'=>'Failed to log Mail!']; 
            }
        } catch (PDOException $e) {
            error_log('failed'.$e->getMessage());
            return ['success'=>false,'message'=>$e->getMessage()]; 
        }
    }

    protected function DeleteMail($id){
       try{ $query='DELETE FROM maillog WHERE id=:id';
        $stmt= parent::connect()->prepare($query);
        $stmt->bindParam(":id",$id);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }catch(PDOException $e){
        error_log('failed'.$e->getMessage());
        return false;
    }
    }

    protected function viewMailinfo($id)
    {

        try {
            $query = "SELECT * FROM maillog WHERE id= :id";
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

    protected function viewMailWithLimit($limit, $offset, $search = '')
    {
        try {
            $conn = parent::connect();
            $sql = "SELECT *  FROM maillog";

            if (!empty($search)) {
                $sql .= " WHERE subject LIKE :search OR recipient LIKE :search";
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


    protected function getTotalMailCount()
    {
        try {
            $stmt = parent::connect()->prepare("SELECT COUNT(*) as total FROM maillog");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['total'] : 0;
        } catch (PDOException $e) {
            error_log("Failed to get count" . $e->getMessage());
            die();
        }
    }

}