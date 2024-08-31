<?php
class Insertorders extends Orders{
    private $customerid;
    private $orderstatus;
   
    private $errors=[];

    public function __construct($customerid, $orderstatus)
    {
        $this->orderstatus= $this->SanitizeData($orderstatus);
        $this->customerid=$this->sanitizeID($customerid);

    }

    private function SanitizeData($data)
    {
        $data = trim($data);

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        return $data;
    }
    private function sanitizeprice($price){
        $price= filter_var($price,FILTER_SANITIZE_NUMBER_FLOAT);
        return $price;
    }
 private function sanitizeID($menusectionid){
        $menusectionid= filter_var($menusectionid,FILTER_SANITIZE_NUMBER_FLOAT);
        return $menusectionid;
    }

    //error checking
    private function isEmpty()
    {
        if (empty($this->customerid)||empty($this->orderstatus)) {
            return true;
        } else {
            return false;
        }
    }

   
  /*  private function invalidPrice() {
        // Remove any non-numeric characters except for the decimal point
        $this->price = preg_replace("/[^0-9.]/", "", $this->price);
    
        // Ensure there's only one decimal point
        if (substr_count($this->price, '.') > 1) {
            return true; // Invalid format
        }
    
        // Check if there are more than two decimal places
        if (strpos($this->price, '.') !== false) {
            $parts = explode('.', $this->price);
            if (strlen($parts[1]) > 2) {
                return true; // More than two decimal places
            }
        }
      
    } */
    
    // Example usage
   
    public function insertTheOrder(){
        if($this->isEmpty()){
            $this->errors[]="Please enter all fields";
        }
        
        if(empty($this->errors)){
        $result=parent::InsertOrders($this->customerid,$this->orderstatus);
        if($result){
          
        return $result;
        }
        }else{
            
            return ["success"=>false, "message"=>$this->errors];
        }
        exit();
    }
}