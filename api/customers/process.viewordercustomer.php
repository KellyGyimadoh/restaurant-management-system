<?php
require("../../includes/sessions.php");

 
 if (isset($_GET['customerid'])) {
    $customerid = filter_var($_GET['customerid'], FILTER_SANITIZE_NUMBER_INT);
    if ($customerid) {
        require_once("../../includes/configdb/Dbconnection.php");
        require_once("../../includes/configdbcust/Ordercustomers.php");
        require_once("../../includes/ordercustomercontroller/Selectoneordercustomer.php");
        $viewtheuser = new Selectoneordercustomer($customerid);
        $viewtheuser->vieworderCustomerInfo();
      
        exit();
    } else {
        die("Invalid request");
    }
} else {
    die("Invalid request");
}