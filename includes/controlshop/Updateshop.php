<?php

class Updateshop extends Shop
{
    private $shopid;
    private $name;
    private $address;
    private $description;
    private $city;
    private $email;
    private $phone;
    private $errors;
    private $country;
    private $state;
    private $postalcode;
    private $openinghours;
    private $website;
    private $status;
    public function __construct(
        $shopid,
        $name,
        $email,
        $phone,
        $address,
        $website,
        $description,
        $city,
        $country,
        $state,
        $postalcode,
        $openinghours,
        $status
    ) {
        $shopid = $this->sanitizePhone($shopid);
        $this->shopid = $this->SanitizeData($shopid);

        $status = $this->sanitizePhone($status);
        $this->status = $this->SanitizeData($status);

        $name = $this->validateData($name);
        $this->name = $this->SanitizeData($name);

        $address = $this->validateData($address);
        $this->address = $this->SanitizeData($address);

        $website = $this->validateData($website);
        $this->website = $this->SanitizeData($website);

        $description = $this->validateData($description);
        $this->description = $this->SanitizeData($description);

        $email = $this->validateData($email);
        $this->email = $this->sanitizeEmail($email);

        $phone = $this->validateData($phone);
        $this->phone = $this->sanitizePhone($phone);

        $country = $this->validateData($country);
        $this->country = $this->SanitizeData($country);

        $city = $this->validateData($city);
        $this->city = $this->SanitizeData($city);

        $state = $this->validateData($state);
        $this->state = $this->SanitizeData($state);

        $postalcode = $this->validateData($postalcode);
        $this->postalcode = $this->SanitizeData($postalcode);

        $openinghours = $this->validateData($openinghours);
        $this->openinghours = $this->SanitizeData($openinghours);
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






    //error checking
    private function isEmpty()
    {
        if (empty($this->name) || empty($this->email) || empty($this->phone)) {
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

   

    private function digitOnly()
    {
        $phoneNumber = preg_replace("/[^0-9]/", "", $this->phone);
        return strlen($phoneNumber) !== 10;
    }


    //confirm errors else signup

    public function updateTheShop()
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
            $result = parent::updateShopInfo(
                $this->shopid,
                $this->name,
                $this->email,
                $this->phone,
                $this->address,
                $this->website,
                $this->description,
                $this->city,
                $this->country,
                $this->state,
                $this->postalcode,
                $this->openinghours,
                $this->status
            );

            return $result;

            $shopinfo = [
                "name" => $this->name,
                "description" => $this->description,
                "email" => $this->email,
                "phone" => $this->phone,
                "address"=>$this->address,
                
            
            ];
        } else {
            $_SESSION['errors'] = $this->errors;
            return ['success' => false, 'message' => $this->errors];
        }
    }
}
