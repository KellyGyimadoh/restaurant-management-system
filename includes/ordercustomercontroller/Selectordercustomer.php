<?php

class Selectordercustomer extends Ordercustomers{
    private $errors=[];
    private $limit;
    private $offset;
    private $search;

    public function __construct($limit,$offset,$search)
    {
        $this->limit=$limit;
        $this->offset=$offset;
        $this->search=$search;
    }
    public function selectTheOrderCustomers(){
        $result=parent::selectAllOrderCustomers();
      return $result ? $result : [];
    }
    public function retrieveTheOrderCustomers(){
        $result=parent::retrieveOrderCustomers();
      return $result ? $result : [];
    }
     
    public function selectTableWithLimit(){
        $result=parent::viewOrderCustomersWithLimit($this->limit,$this->offset,$this->search);
        return $result ? $result : [];
    }

    public function totalCustomerCount(){
        $result=parent::getTotalorderCustomersCount();
        return $result ? $result : 0;
    }

   

}