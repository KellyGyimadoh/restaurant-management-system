<?php
require("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configdbcust/Ordercustomers.php");
require_once("../../includes/ordercustomercontroller/Updateordercustomer.php");

if(isset($_POST['save']) && $_SERVER['REQUEST_METHOD']==="POST"){


    $customerid= filter_input(INPUT_POST,"customerid",FILTER_SANITIZE_NUMBER_INT);
    $firstname=filter_input(INPUT_POST,"firstname",FILTER_SANITIZE_SPECIAL_CHARS);
    $lastname=filter_input(INPUT_POST,"lastname",FILTER_SANITIZE_SPECIAL_CHARS);
    $email=filter_input(INPUT_POST,"email",FILTER_SANITIZE_EMAIL);
    $phone=filter_input(INPUT_POST,"phone",FILTER_SANITIZE_SPECIAL_CHARS);
    $loyaltypoint=filter_input(INPUT_POST,"loyaltypoint",FILTER_SANITIZE_SPECIAL_CHARS);
   
    //put into constructor connection class first 
    

    $editcustomer= new Updateordercustomer($customerid,$firstname,$lastname,$email,$phone,$loyaltypoint);
   $editcustomer->updateTheCustomer();
   
}

else{
        header("Location:../../auth/index.php");
    die();
}