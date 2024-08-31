<?php

class Deletecust extends Customers{
    private $id;
    public function __construct($id)
    {
        $this->id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    }
   
    public function selectTheDelCustomers(){
       
        if( parent::deleteCustomer($this->id)){
            $_SESSION['deleted']=true;
            header("Location: ../../customers/tablecust.php");
            exit();
        }else{
            $_SESSION['errordelete']=true;
            header("Location: ../../customers/tablecust.php");
           
            die();
        }
    }
}