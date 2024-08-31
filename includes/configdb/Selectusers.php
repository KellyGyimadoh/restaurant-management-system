<?php

class Selectusers extends Dbconnection{
            private $id;
        private $result=[];
        private $errors=[];
        private $userinfo=[];

        public function SelectRecipientDetails(){
           try{ 
            $query = "SELECT id, firstname,lastname, email FROM workers";
            $stmt = parent::connect()->prepare($query);
            $stmt->execute();
            $result= $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($result){
                return ['success'=>true,'message'=>'workers retrieved','result'=>$result];
            }else{
                return ['success'=>true,'message'=>'no data found'];
            }
            
           }catch(PDOException $e){
            error_log($e->getMessage());
            return['success'=>false, 'message'=>'operation failed'.$e->getMessage()];
           }
        }
        public function selectAllEmail(){
            
            try {
               $query="SELECT email FROM workers ";
               $stmt=parent::connect()->prepare($query);
               $stmt->execute();
               $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
               if(!empty($result)){
                return $result;
               }else{
                
                return [];
               }

            } catch (PDOException $e) {
                throw new Exception("data cannot be retrieved".$e->getMessage());
            }
        }
        public function selectAllStaffOrAdminEmail($accounttype){
            
            try {
               $query="SELECT email FROM workers WHERE account_type=:accounttype";
               $stmt=parent::connect()->prepare($query);
               $stmt->bindParam(':accounttype',$accounttype);
               $stmt->execute();
               $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
               if(!empty($result)){
                return $result;
               }else{
                
                return [];
               }

            } catch (PDOException $e) {
                throw new Exception("data cannot be retrieved".$e->getMessage());
            }
        }
       
        private function selectTable(){
            
            try {
               $query="SELECT * FROM workers ";
               $stmt=parent::connect()->prepare($query);
               $stmt->execute();
               $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
               if(!empty($result)){
                return $result;
               }else{
                $this->errors="field empty";
                return [];
               }

            } catch (PDOException $e) {
                throw new Exception("data cannor be retrieved".$e->getMessage());
            }
        }

            public function viewTable(){
                if(empty($this->errors)){
                    $this->result= $this->selectTable();
                    return $this->result;
                }else{
                    $_SESSION['errors']=$this->errors;
                    die();
                }   
            }

            //limit and pagination
            public function viewTableWithLimit($limit, $offset, $search = '') {
                $conn = parent::connect();
                $sql = "SELECT * FROM workers";
                
                if (!empty($search)) {
                    $sql .= " WHERE firstname LIKE :search OR lastname LIKE :search";
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
            }
        
        
            public function getTotalUsersCount() {
                $stmt = parent::connect()->prepare("SELECT COUNT(*) as total FROM workers");
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['total'];
            }
        

            private function viewUser(){
            
                try {
                   $query="SELECT * FROM workers WHERE id= :id";
                   $stmt=parent::connect()->prepare($query);
                   $stmt->bindParam(":id",$this->id);
                   $stmt->execute();
                   $result=$stmt->fetch(PDO::FETCH_ASSOC);
                   if(!empty($result)){
                    return $result;
                   }else{
                    $this->errors="field empty";
                    return [];
                   }
    
                } catch (PDOException $e) {
                    throw new Exception("data cannot be retrieved".$e->getMessage());
                }
            }
            public function viewtheUser(){
                if(empty($this->errors)){
                    $this->userinfo= $this->viewUser();
                    return $this->userinfo;
                }else{
                    $_SESSION['errors']=$this->errors;
                    die();
                }   
            }
            public function viewWorkerTypeWithLimit($limit, $offset, $search = '',$accounttype) {
                $conn = parent::connect();
                $sql = "SELECT * FROM workers WHERE account_type=:accounttype";
                
                if (!empty($search)) {
                    $sql .= " AND firstname LIKE :search OR lastname LIKE :search";
                }
                
                $sql .= " LIMIT :limit OFFSET :offset";
                
                $stmt = $conn->prepare($sql);
                
                if (!empty($search)) {
                    $search = '%' . $search . '%';
                    $stmt->bindParam(':search', $search, PDO::PARAM_STR);
                }
                
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                $stmt->bindParam(':accounttype', $accounttype, PDO::PARAM_STR);
                
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            public function getTotalWorkerTypeCount($accounttype) {
                $stmt = parent::connect()->prepare("SELECT COUNT(*) as total FROM workers WHERE account_type=:accounttype");
                $stmt->bindParam(":accounttype",$accounttype);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['total'];
            }
}