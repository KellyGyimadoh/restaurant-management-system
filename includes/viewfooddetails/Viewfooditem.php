<?php
class Viewfooditem extends FoodItemDetails{
    private $id;
    public function __construct($id)
    {
        $this->id= filter_var($id,FILTER_SANITIZE_NUMBER_INT);
    }


    public function viewthefooditem(){
        $result=parent::getFoodItemDetails($this->id);
        return $result ? $result : [];
    }
    public function viewthefooditemAndPrice(){
        $result=parent::viewfoodItemAndPrice();
        return $result ? $result : [];
    }


}