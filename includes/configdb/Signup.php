<?php

class Signup extends Dbconnection{
            private $fname;
            private $lname;
            private $password;
            private $rptpassword;
            private $email;
            private $phone;
            private $errors;
            private $accounttype;
            public function __construct($fname,$lname,$password,$rptpassword,$email,$phone)
            {   
                        $fname= $this->validateData($fname);
                        $this->fname= $this->SanitizeData($fname);
                        $lname= $this->validateData($lname);
                        $this->lname= $this->SanitizeData($lname);
                        $password= $this->validateData($password);
                        $this->password= $this->SanitizeData($password);
                        $rptpassword= $this->validateData($rptpassword);
                        $this->rptpassword= $this->SanitizeData($rptpassword);
                        $email= $this->validateData($email);
                        $this->email= $this->sanitizeEmail($email);
                        $phone= $this->validateData($phone);
                        $this->phone= $this->sanitizePhone($phone);
                        
                
            }

           

            private function SanitizeData($data){
                $data=trim($data);
                
                $data= htmlspecialchars($data);
                $data= stripslashes($data);
                return $data;
            }
            private function sanitizeEmail($email){
                $email= filter_var($email,FILTER_SANITIZE_EMAIL);
                return $email;
            }
            private function validateData($data){
                $data= filter_var($data,FILTER_SANITIZE_SPECIAL_CHARS);
                return $data;
            }
            private function sanitizePhone($phone){
                $phone= filter_var($phone,FILTER_SANITIZE_NUMBER_INT);
                return $phone;
            }

            //insert into database
                private function insertUser(){
                    try {
                        $query="INSERT INTO workers (firstname,lastname,email,password,phone,account_type) VALUES(:fname,:lname,:email,:password,:phone,:accounttype)";
                        $stmt=parent::connect()->prepare($query);
                        $options=[
                            "cost"=>12
                        ];
                        //set account type after registering manager
                        $this->accounttype="staff";
                        $hashedpass= password_hash($this->password.SALT,PASSWORD_BCRYPT,$options);
                        $stmt->bindParam(":fname",$this->fname);
                        $stmt->bindParam(":lname",$this->lname);
                        $stmt->bindParam(":email",$this->email);
                        $stmt->bindParam(":password",$hashedpass);
                        $stmt->bindParam(":phone",$this->phone);
                        $stmt->bindParam(":accounttype",$this->accounttype);
                        $stmt->execute();
                    } catch (PDOException $e) {
                        die("could not add user".$e->getMessage());
                    }
                }



            //error checking
            private function isEmpty(){
                if(empty($this->fname)||empty($this->lname)||empty($this->email)||empty($this->password)||empty($this->rptpassword)||empty($this->phone)){
                    return true;
                }else{
                    return false;
                }
            }
            private function unmatchedPassword(){
                if($this->password!==$this->rptpassword){
                    return true;
                }else{
                    return false;
                }
            }

            private function invalidEmail(){
                if(!filter_var($this->email,FILTER_VALIDATE_EMAIL)){
                    return true;
                }else{
                    return false;
                }
            }

            private function emailExist(){
                try {
                    $query="SELECT email FROM workers where email=:email";
                    $stmt=parent::connect()->prepare($query);
                    $stmt->bindParam(":email",$this->email);
                    $stmt->execute();
                    $result= $stmt->fetch(PDO::FETCH_ASSOC);
                    if($result>0 && $this->email===$result['email']){
                        return true;
                    }else{
                        return false;
                    }
                } catch (PDOException $e) {
                    die("search failed".$e->getMessage());
                }
            }

            private function digitOnly(){
                $phoneNumber = preg_replace("/[^0-9]/", "", $this->phone);
                return strlen($phoneNumber) !== 10;
            }


            //confirm errors else signup

            public function registerUser(){
                if($this->emailExist()){
                    $this->errors['emailexist']="Email already exist";
                }
                if($this->invalidEmail()){
                    $this->errors['invalidemail']="Email is invalid";
                }
                if($this->digitOnly()){
                    $this->errors['wrongphone']="Enter valid phone Number";
                }
                if($this->isEmpty()){
                    $this->errors['emptyinput']="Please fill all input";
                }
                if($this->unmatchedPassword()){
                    $this->errors['unmatchedpassword']="Passwords donot match!";
                }

                if(empty($this->errors)){
                    $this->insertUser();
                    if(isloggedin() && isset($_SESSION['accounttype'])&& $_SESSION['accounttype']=="director"){
                        $_SESSION['useradded']=true;
                        header("Location:../../manager/home.php");
                    }else{
                    $_SESSION['success']=true;
                    header("Location: ../../auth/index.php");
                    }
                }else{
                    $_SESSION['errors']=$this->errors;
                    $signupdata=[
                        "fname"=>$this->fname,
                        "lname"=>$this->lname,
                        "email"=>$this->email,
                        "phone"=>$this->phone
                    ];
                    $_SESSION['signupdata']=$signupdata;
                    header("Location: ../../auth/signup.php");
                    die();
                }

 }
            
            
            
            
            
            
            
            
            
            
            


}