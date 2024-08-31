<?php

class Insertshop extends Shop{
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
            public function __construct($name,$email,$phone,$address,$website,$description,$city,$country,
            $state,$postalcode,$openinghours,$status)
            {   
                        $name= $this->validateData($name);
                        $this->name= $this->SanitizeData($name);

                        $address= $this->validateData($address);
                        $this->address= $this->SanitizeData($address);

                        $website= $this->validateData($website);
                        $this->website= $this->SanitizeData($website);

                        $description= $this->validateData($description);
                        $this->description= $this->SanitizeData($description);

                        $email= $this->validateData($email);
                        $this->email= $this->sanitizeEmail($email);

                        $phone= $this->validateData($phone);
                        $this->phone= $this->sanitizePhone($phone);

                        $country= $this->validateData($country);
                        $this->country= $this->SanitizeData($country);

                        $city= $this->validateData($city);
                        $this->city= $this->SanitizeData($city);

                        $state= $this->validateData($state);
                        $this->state= $this->SanitizeData($state);

                        $postalcode= $this->validateData($postalcode);
                        $this->postalcode= $this->SanitizeData($postalcode);

                        $openinghours= $this->validateData($openinghours);
                        $this->openinghours= $this->SanitizeData($openinghours);

                        $status= $this->SanitizeData($status);
                        $this->status= $this->sanitizePhone($status);
                        
                
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
            private function sanitizePhone($num){
                $num= filter_var($num,FILTER_SANITIZE_NUMBER_INT);
                return $num;
            }

            
                



            //error checking
            private function isEmpty(){
                if(empty($this->name)||empty($this->email)||empty($this->phone)){
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
                    $query="SELECT email FROM shop where email=:email";
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

            public function AddShop(){
                if($this->emailExist()){
                    $this->errors[]="Email already exist";
                }
                if($this->invalidEmail()){
                    $this->errors[]="Email is invalid";
                }
                if($this->digitOnly()){
                    $this->errors[]="Enter valid phone Number";
                }
                if($this->isEmpty()){
                    $this->errors[]="Please fill all input";
                }
               

                if(empty($this->errors)){
                    $result= parent::insertShopInfo($this->name,$this->email,$this->phone,$this->address,$this->website
                    ,$this->description,$this->city,$this->country,$this->state,$this->postalcode,
                    $this->openinghours,$this->status);
                
                   return $result; 
                    
                   
                }else{
                    $shopinfo=[
                        "name"=>$this->name,
                        "description"=>$this->description,
                        "email"=>$this->email,
                        "phone"=>$this->phone,
                        "description"=>$this->description,
                        "website"=>$this->website,
                        "address"=>$this->address,
                        "country"=>$this->country,
                        "postalcode"=>$this->postalcode,
                        "city"=>$this->city,
                        "openinghours"=>$this->openinghours,
                        "state"=>$this->state

                    ];
                    $_SESSION['signupshop']=$shopinfo;
                    $_SESSION['errors']=$this->errors;
                    return['success'=>false,'message'=>$this->errors];
                }

 }
            
            
            
            
            
            
            
            
            
            
            


}