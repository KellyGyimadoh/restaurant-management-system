<?php
class Deleteshop extends Shop{
    private $shopid;
    public function __construct($shopid)
    {
        $this->shopid = filter_var($shopid, FILTER_SANITIZE_NUMBER_INT);
    }
   
    public function deleteTheShop(){
       
        if( parent::deleteShop($this->shopid)){
           return true;
        }else{
           return false;
        }
    }
}