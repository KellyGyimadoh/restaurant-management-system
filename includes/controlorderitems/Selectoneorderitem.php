<?php
class Selectoneorderitem extends Orderitems{

    private $orderid;
    public function __construct($orderid)
    {
        $this->orderid= filter_var($orderid,FILTER_SANITIZE_NUMBER_INT);
    }
    public function viewTheOrderItem(){
        $result= parent::viewOrderitemAndId($this->orderid);
        return $result ? $result : [];
    }
}