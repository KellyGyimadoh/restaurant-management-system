<?php
class Selectpaymentstatus extends Paymentorders{

    private $orderid;
    public function __construct($orderid)
    {
        $this->orderid= filter_var($orderid,FILTER_SANITIZE_NUMBER_INT);
    }
    public function viewThePaymentStatus(){
        $result= parent::viewPaymentStatus($this->orderid);
        return $result ? $result : [];
    }
}