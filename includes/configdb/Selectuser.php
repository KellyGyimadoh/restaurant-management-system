<?php
class Selectuser extends Dbconnection{
       private $id;
       
       
        private $errors=[];
        

       
    public function __construct($id)
    {
        $this->id= $id;
    }
            private function viewUser(){
            
                try {
                   $query="SELECT * FROM workers WHERE id= :id";
                   $stmt=parent::connect()->prepare($query);
                   $stmt->bindParam(":id",$this->id);
                   $stmt->execute();
                   $result=$stmt->fetch(PDO::FETCH_ASSOC);
                   if($result){
                    return $result;
                   }else{
                    $this->errors['emptyfield']="field empty";
                    return [];
                   }
    
                } catch (PDOException $e) {
                    throw new Exception("data cannot be retrieved".$e->getMessage());
                }
            }
           /* public function viewtheUser(){
                if(empty($this->errors)){
                    
                    $_SESSION['updatesignupinfo']=$this->viewUser();
                    header("Location: ../../users/profile.php");  
                }else{
                    $_SESSION['errors']=$this->errors;
                    die();
                }   
            } */


          /*  public function viewtheUser() {
                $userData = $this->viewUser();
        
                if (empty($this->errors)) {
                    $_SESSION['updatesignupinfo'] = $userData;
                    header("Location: ../../users/profile.php");
                    exit();
                } else {
                    $_SESSION['errors'] = $this->errors;
                    header("Location: ../../users/profile.php"); // Redirect to an error page or handle appropriately
                    exit();
                }
            }*/
            public function viewtheUser() {
                if (empty($this->errors)) {
                    $_SESSION['updatesignupinfo'] = $this->viewUser();
                    if (!empty($_SESSION['updatesignupinfo'])) {
                        header("Location: ../../users/profile.php");
                       // header("Location: ../../manager/home.php");
                    } else {
                        $_SESSION['updateerrors'] = $this->errors;
                        header("Location: ../../manager/home.php"); // Redirect back to the team page on error
                    }
                } else {
                    $_SESSION['updateerrors'] = $this->errors;
                    header("Location: ../../manager/home.php"); // Redirect back to the team page on error
                }
                exit(); // Ensure no further code is executed after redirection
            }

}