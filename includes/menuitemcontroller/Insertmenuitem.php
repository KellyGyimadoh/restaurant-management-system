<?php
class Insertmenuitem extends MenuItem{
    private $menusectionid;
    private $fooditem;
    private $itemdescription;
    private $price;
    private $image;

    private $errors=[];

    public function __construct($menusectionid, $fooditem, $itemdescription, $price, $image)
    {
        $this->fooditem= $this->SanitizeData($fooditem);
        $this->itemdescription= $this->SanitizeData($itemdescription);
        $this->price=$this->sanitizeprice($price);
        $this->image=$this->SanitizeData($image);
        $this->menusectionid=$this->sanitizeID($menusectionid);

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
        if (empty($this->fooditem)||empty($this->price)) {
            return true;
        } else {
            return false;
        }
    }


   
    private function foodexist()
    {
        return $this->checkfoodexist($this->fooditem);
    }
    
   
    private function invalidPrice() {
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
      
    }
    
    // Example usage
   
    public function insertTheFoodItem(){
        if($this->isEmpty()){
            $this->errors[]="Please enter all fields";
        }
        if($this->invalidPrice()){
            $this->errors[]="Invalid price entered";
        }
        if($this->foodexist()){
            $this->errors[]="food item already exist";
        }
        if(empty($this->errors)){
        $result=parent::insertMenuItem($this->menusectionid,$this->fooditem,$this->itemdescription,$this->price,$this->image);
        if($result){
          
        return ["success"=>true, "message"=> $this->fooditem." Food item added successfully"];
            }else{
            return ["success"=>false, "message"=>"Failed to insert items"];
        }
        }else{
            
            return ["success"=>false, "message"=>$this->errors];
        }
        exit();
    }
}