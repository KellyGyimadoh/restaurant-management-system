<?php

class Deleteordercustomer extends Ordercustomers{
    private $id;
    public function __construct($id)
    {
        $this->id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    }
   
    public function DelorderCustomer(){
       
        if( parent::deleteorderCustomer($this->id)){
            $_SESSION['deleted']=true;
            header("Location: ../../customers/tableordercustomers.php");
            exit();
        }else{
            $_SESSION['errordelete']=true;
            header("Location: ../../customers/tableordercustomers.php");
           
            die();
        }
    }
}