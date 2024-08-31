<?php

class Updateuser extends Dbconnection{
    private $id;
    private $fname;
    private $lname;
    private $email;
    private $phone;
    private $errors=[];
    private $accounttype;
    private $image;

    public function __construct($id,$fname,$lname,$email,$phone,$accounttype,$image){
        $id=$this->validateId($id);
        $this->id=$this->SanitizeData($id);
        $fname= $this->validateData($fname);
        $this->fname= $this->SanitizeData($fname);
        $lname= $this->validateData($lname);
        $this->lname= $this->SanitizeData($lname);
        $accounttype= $this->validateData($accounttype);
        $this->accounttype= $this->SanitizeData($accounttype);
        $email= $this->validateData($email);
        $this->email= $this->sanitizeEmail($email);
        $phone= $this->validateData($phone);
        $this->phone= $this->sanitizePhone($phone);
        $this->image=$this->SanitizeData($image);
        

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
    private function validateId($id){
        $id= filter_var($id,FILTER_VALIDATE_INT);
        return $id;
    }
// update user in database

private function updateworker(){
    try { //select from db
       
        $query= "UPDATE workers SET firstname=:fname, lastname=:lname, email=:email, phone=:phone, image=:image, account_type=:accounttype WHERE id=:id";
        $stmt=parent::connect()->prepare($query);
        $stmt->bindParam(":id",$this->id);
        $stmt->bindParam(":fname",$this->fname);
        $stmt->bindParam(":lname",$this->lname);
        $stmt->bindParam(":email",$this->email);
        $stmt->bindParam(":phone",$this->phone);
        $stmt->bindParam(":image",$this->image);
       
        $stmt->bindParam(":accounttype",$this->accounttype);
        //error_log("Updating worker with ID: {$this->id}, Firstname: {$this->fname}, Lastname: {$this->lname}, Email: {$this->email}, Phone: {$this->phone}, Account Type: {$this->accounttype}");
         // Print SQL query for debugging
          // Log SQL query
       // $logFile = 'query_log.txt';
       // $logMessage = date('Y-m-d H:i:s') . " - SQL Query: " . $stmt->queryString . PHP_EOL;
       // file_put_contents($logFile, $logMessage, FILE_APPEND);

        if ($stmt->execute()) {
            return true; // Update was successful
        } else {
            return false; // Update failed
        } 
    
    
    } catch (PDOException $e) {
        die("Could not update user: " . $e->getMessage());
         // Update failed
    }
}






//erro checking
private function isEmpty(){
    if(empty($this->fname)||empty($this->lname)||empty($this->email)||empty($this->accounttype)||empty($this->phone)){
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



private function digitOnly(){
    $phoneNumber = preg_replace("/[^0-9]/", "", $this->phone);
    return strlen($phoneNumber) !== 10;
}


public function makeChanges($redirecturl,$defaulturl){
    if($this->invalidEmail()){
        $this->errors['invalidemail']="Email is invalid";
    }
    if($this->digitOnly()){
        $this->errors['wrongphone']="Enter valid phone Number";
    }
    if($this->isEmpty()){
        $this->errors['emptyinput']="Please fill all input";
    }

    if (empty($this->errors)) {
        if ($this->updateworker()) {
            $_SESSION['updated'] = true;
            $userinfo=[
                "fname"=>$this->fname,
                "lname"=>$this->lname,
                "email"=>$this->email,
                "id"=>$this->id,
                "phone"=>$this->phone,
                "accounttype"=>$this->accounttype,
                "image"=>$this->image
                
            ];
            $_SESSION['userinfo']=$userinfo;
           header("Location: $redirecturl");
            exit(); // Prevent further execution
        } else {
            $this->errors['cannotupdate'] = "Could not update user";
        }
    } else {
        $_SESSION['errors'] = $this->errors;
    }

    header("Location: $defaulturl");
   exit(); // Prevent further execution
}





}

