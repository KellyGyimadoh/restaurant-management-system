<?php

class Updatecustomer extends Customers{
    
    private $id;
    private $name;
    private $email;
    private $phone;
    private $errors=[];
    private $date;
    private $message;
    private $time;

    public function __construct($id,$name,$email,$phone,$date,$time,$message){
        $id=$this->validateId($id);
        $this->id=$this->SanitizeData($id);
       
        $name= $this->validateData($name);
        $this->name= $this->SanitizeData($name);
        $message= $this->validateData($message);
        $this->message= $this->SanitizeData($message);
        $email= $this->validateData($email);
        $this->email= $this->sanitizeEmail($email);
        $phone= $this->validateData($phone);
        $this->phone= $this->sanitizePhone($phone);
        $date= $this->validateData($date);
        $this->date= $this->SanitizeData($date);
        $time= $this->validateData($time);
        $this->time= $this->SanitizeData($time);

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









//erro checking
private function isEmpty(){
    if(empty($this->name)||empty($this->email)||empty($this->message)||empty($this->phone)||empty($this->date)||empty($this->time)){
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


public function updateTheCustomer(){
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
        $result=parent::updateCustomers($this->id,$this->name,$this->email,$this->phone,$this->date,$this->time,$this->message);
        if ($result) {
            $_SESSION['updatedcustomer'] = true;
           header("Location: ../../customers/tablecust.php");
            exit(); // Prevent further execution
        } else {
            $this->errors['cannotupdate'] = "Could not update user";
            
        }
    } else {
        $_SESSION['updateerrors'] = $this->errors;
        header("Location: ../../customers/tablecust.php");
       
    }

   // header("Location: ../../customers/tablecust.php");
   error_log("Errors: " . print_r($this->errors, true));
   exit(); // Prevent further execution
}

}











