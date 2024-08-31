<?php
class Updatemenuitem extends MenuItem
{

    private $id;
    private $menusectionid;
    private $description;
    private $price;
    private $image;
    private $fooditem;
    private $menusection;
    private $menutype;
    private $menudescription;
    private $menuid;
    private $errors = [];

   
    public function __construct($id,$fooditem, $description, $price, $image,$menusectionid,
     $menusection,$menutype,$menudescription,$menuid)
    {
        $id = $this->validateId($id);
        $this->id = $this->SanitizeData( $id );
        $menuid = $this->validateId($menuid);
        $this->menuid = $this->SanitizeData( $menuid );
        
        $menusectionid = $this->validateId($menusectionid);
        $this->menusectionid  = $this->SanitizeData( $menusectionid );
        $this->description = $this->SanitizeData($description);
        $this->fooditem= $this->SanitizeData($fooditem);
       // $price= $this->validateprice($price);
       $price=$this->validatePrice($price);
        $this->price = $this->SanitizeData($price);
        $this->image = $this->SanitizeData($image);
        $this->menusection=$this->SanitizeData($menusection);
        $this->menutype=$this->SanitizeData($menutype);
        $this->menudescription=$this->SanitizeData($menudescription);
    }

  /*  
  update menu item only
  public function __construct($id,$fooditem, $description, $price, $image,$menusectionid)
    {
        $id = $this->validateId($id);
        $this->id = $this->SanitizeData( $id );
        $menusectionid = $this->validateId($menusectionid);
        $this->menusectionid  = $this->SanitizeData( $menusectionid );
        $this->description = $this->SanitizeData($description);
        $this->fooditem= $this->SanitizeData($fooditem);
       // $price= $this->validateprice($price);
       $price=$this->validatePrice($price);
        $this->price = $this->SanitizeData($price);
        $this->image = $this->SanitizeData($image);
    }

*/
    private function SanitizeData($data)
    {
        $data = trim($data);

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        return $data;
    }

    /*private function validateData($data)
    {
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        return $data;
    }*/

    private function validateId($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        return $id;
    }

    private function validatePrice($price)
    {
        // Remove non-numeric characters except the decimal point
        $price = preg_replace("/[^0-9.]/", "", $price);

        // Ensure there is only one decimal point
        if (substr_count($price, '.') > 1) {
            throw new Exception("Invalid price format");
        }

        // Limit to two decimal places
        $parts = explode('.', $price);
        if (count($parts) > 1) {
            $parts[1] = substr($parts[1], 0, 2);
            $price = implode('.', $parts);
        }

        return $price;
    }

    private function validateimage($image)
    {
        $image = filter_var($image, IMG_FILTER_SMOOTH);
        return $image;
    }









    //erro checking
    private function isEmpty()
    {
        if (empty($this->fooditem)||empty($this->price)||empty($this->menusectionid)) {
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


    public function updateTheMenuItem()
    {

        if ($this->isEmpty()) {
            $this->errors[] = "Please fill all input";
        }
        if($this->invalidPrice()){
            $this->errors[]="Invalid price entered";
        }
       
        if (empty($this->errors)) {
           //update with type section and item
           $result= parent::updateMenuItemWithSectionAndType($this->id,$this->fooditem, $this->description,$this->price,
           $this->image,$this->menusectionid,$this->menusection,$this->menutype,$this->menudescription,$this->menuid);
     /* $result = parent::updateMenuItem($this->id, $this->fooditem, $this->description,
            $this->price,$this->image,$this->menusectionid); 
          */  if ($result) {
                //$_SESSION['updatedmenuitem'] = true;
                //header("Location: ../../menus/tablemenuitems.php");
                $foodinfo=[
                    "typeid"=>$this->menuid,
                    "sectionid"=>$this->menusectionid,
                    "fooditem"=>$this->fooditem,
                    "id"=>$this->id,
                    "menu_type"=>$this->menutype,
                    "section_name"=>$this->menusection,
                    "itemdescription"=>$this->menudescription,
                    "price"=>$this->price,
                    "image"=>$this->image
                ];
                $_SESSION['fooddetails']=$foodinfo;
                return (["success"=>true, "message"=>"Food Item Updated Successfully","redirecturl"=>"../menus/editmenuprofile.php"]);
                 // Prevent further execution
            } else {
                return (["success"=>false, "message"=>" Failed to Update Food Item"]);
            }
        } else {
            return (["success"=>false, "message"=>$this->errors]);

        }

        // header("Location: ../../customers/tablecust.php");
        error_log("Errors: " . print_r($this->errors, true));
        exit(); // Prevent further execution
    }
}
