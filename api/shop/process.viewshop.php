<?php
require("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configshop/Shop.php");
require_once("../../includes/controlshop/Selectshop.php");
 if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    if ($id) {
        
        $viewshop = new Selectshop($id);
       $result= $viewshop->viewTheShopInfo();
       echo json_encode($result);
      
        exit();
    } else {
        die("Invalid request");
    }
} else {
    die("Invalid request");
}