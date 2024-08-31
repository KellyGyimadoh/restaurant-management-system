<?php
class Viewitemprice extends FoodItemDetails{

    public function viewthefooditemAndPrice(){
        $result=parent::viewfoodItemAndPrice();
        return $result ? $result : [];
    }


}