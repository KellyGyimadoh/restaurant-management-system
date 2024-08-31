<?php
require("../../includes/sessions.php");

 
 if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    if ($id) {
        require_once("../../includes/configdb/Dbconnection.php");
        require_once("../../includes/configdb/Deleteuser.php");
        require_once("../../includes/controller/deleteusercontro.php");
        $deleteuser= new Deletecontrol($id);
        $deleteuser->performDelete();
       
        exit();
    } else {
        die("Invalid request");
    }
} else {
    die("Invalid request");
}