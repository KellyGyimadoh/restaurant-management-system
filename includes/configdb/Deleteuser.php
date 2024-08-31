<?php
class Deleteuser extends Dbconnection{


    protected function delete($id){
        try {
            $query="DELETE FROM workers WHERE id=:id";
            $stmt=parent::connect()->prepare($query);
            $stmt->bindParam(":id",$id);
           if($stmt->execute()){
            return true;
           }else{
            return false;
           }
        } catch (PDOException $e) {
            die("could not delete user".$e->getMessage());
        }
    }

}