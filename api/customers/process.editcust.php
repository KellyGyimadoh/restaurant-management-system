<?php
require("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configdbcust/Customers.php");
require_once("../../includes/customercontroller/Updatecustomer.php");

if(isset($_POST['save']) && $_SERVER['REQUEST_METHOD']==="POST"){


    $id= filter_input(INPUT_POST,"id",FILTER_SANITIZE_NUMBER_INT);
    $name=filter_input(INPUT_POST,"name",FILTER_SANITIZE_SPECIAL_CHARS);
    $email=filter_input(INPUT_POST,"email",FILTER_SANITIZE_EMAIL);
    $phone=filter_input(INPUT_POST,"phone",FILTER_SANITIZE_SPECIAL_CHARS);
    $date=filter_input(INPUT_POST,"date",FILTER_SANITIZE_SPECIAL_CHARS);
    $time=filter_input(INPUT_POST,"time",FILTER_SANITIZE_SPECIAL_CHARS);
    $message=filter_input(INPUT_POST,"message",FILTER_SANITIZE_SPECIAL_CHARS);
    //put into constructor connection class first 
    

    $editcustomer= new Updatecustomer($id,$name,$email,$phone,$date,$time,$message);
   $editcustomer->updateTheCustomer();
   
}

else{
        header("Location:../../auth/index.php");
    die();
}