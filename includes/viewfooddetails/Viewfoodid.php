<?php
class Viewfoodid extends FoodItemDetails{
    private $fooditem;
    public function __construct($fooditem)
    {
        $this->fooditem= filter_var($fooditem,FILTER_SANITIZE_SPECIAL_CHARS);
    }


    public function viewthefooditem(){
        $result=parent::viewfoodItemID($this->fooditem);
        return $result ? $result : [];
    }
}