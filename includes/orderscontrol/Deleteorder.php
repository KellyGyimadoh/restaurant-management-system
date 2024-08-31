<?php
class Deleteorder extends Orders{
    private $orderid;
    public function __construct($orderid)
    {
        $this->orderid = filter_var($orderid, FILTER_SANITIZE_NUMBER_INT);
    }
   
    public function deleteTheOrder(){
       
        if( parent::deleteOrder($this->orderid)){
           return true;
        }else{
           return false;
        }
    }
}