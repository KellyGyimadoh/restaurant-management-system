<?php

class Insertordercustomers extends Ordercustomers
{
    private $firstname;
    private $lastname;
    private $email;
    private $phone;
    private $loyaltypoint;
    private $errors;
    public function __construct($firstname, $lastname, $email, $phone, $loyaltypoint)
    {
        $firstname = $this->validateData($firstname);
        $this->firstname = $this->SanitizeData($firstname);
        $lastname = $this->validateData($lastname);
        $this->lastname = $this->SanitizeData($lastname);
        $loyaltypoint = $this->validateData($loyaltypoint);
        $this->loyaltypoint = $this->SanitizeData($loyaltypoint);
        $email = $this->validateData($email);
        $this->email = $this->sanitizeEmail($email);
        $phone = $this->validateData($phone);
        $this->phone = $this->sanitizePhone($phone);
    }



    private function SanitizeData($data)
    {
        $data = trim($data);

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        return $data;
    }
    private function sanitizeEmail($email)
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        return $email;
    }
    private function validateData($data)
    {
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        return $data;
    }
    private function sanitizePhone($phone)
    {
        $phone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
        return $phone;
    }

    //insert into database




    //error checking
    private function isEmpty()
    {
        if (empty($this->firstname) || empty($this->lastname) || empty($this->email) || empty($this->loyaltypoint) || empty($this->phone)) {
            return true;
        } else {
            return false;
        }
    }

    private function invalidEmail()
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    /* private function emailExist(){
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
            }*/

    private function digitOnly()
    {
        $phoneNumber = preg_replace("/[^0-9]/", "", $this->phone);
        return strlen($phoneNumber) !== 10;
    }


    //confirm errors else signup

    public function registerOrderCustomer()
    {

        if ($this->invalidEmail()) {
            $this->errors[] = "Email is invalid";
        }
        if ($this->digitOnly()) {
            $this->errors[] = "Enter valid phone Number";
        }
        if ($this->isEmpty()) {
            $this->errors[] = "Please fill all input";
        }


        if (empty($this->errors)) {
          
            $result = parent::InsertCustomers($this->firstname, $this->lastname, $this->email, $this->phone, $this->loyaltypoint);
            
            if ($result) {
                $ordercustomerinfo = [
                    "firstname" => $this->firstname,
                    "lastname" => $this->lastname,
                    "email" => $this->email,
                    "phone" => $this->phone,
                    "loyaltypoint" => $this->loyaltypoint
                ];
                $_SESSION['ordercustomerinfo'] = $ordercustomerinfo;
                return $result;
            } else {
                return (["success" => false, "message" => "failed to add customer info"]);
            }
        } else {
            return (["success" => false, "message" => $this->errors]);
        }
    }
}
