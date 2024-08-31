<?php
require("../../includes/sessions.php");

 
 if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    if ($id) {
        require_once("../../includes/configdb/Dbconnection.php");
        require_once("../../includes/configdb/Selectuser.php");
        $viewtheuser = new Selectuser($id);
        $viewtheuser->viewtheUser();
      
        exit();
    } else {
        //add errror page
        header("Location:HTTP/1.0 500 Internal Server Error");
        die("Invalid request");
    }
} else {
    die("Invalid request");
}