<?php

class Selectcustomer extends Customers{
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
    public function selectTheCustomers(){
        $result=parent::selectAllCustomers();
        if(!empty($result)){
            return $result;
           
        }else{
            $this->errors['emptyinput']="Empty fields";
            $_SESSION['customerselecterror']=$this->errors;
            return [];
            
        }
    }
     
    public function selectTableWithLimit(){
        $result=parent::viewTableWithLimit($this->limit,$this->offset,$this->search);
        return $result ? $result : [];
    }

    public function totalCustomerCount(){
        $result=parent::getTotalUsersCount();
        return $result ? $result : 0;
    }

   public function getrecentcustomer(){
    $result= parent::getTheRecentcustomer();

    return $result ?$result : [];
   }

   public function getLatestMessage(){
    $result=parent::getTheRecentCustomerMessage();
    return $result? $result:[];
   }
}