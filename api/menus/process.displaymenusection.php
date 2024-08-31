<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../../includes/sessions.php");
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configmenu/MenuSection.php';
require_once '../../includes/menusectioncontroller/Selectonemenusection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $newmenusection = new Selectonemenusection();
    $newmenusection->viewMenuSectionInfo($id);
        //header("Location:../../menus/detailspage.php");
       
}  
   
 else {
    die("Invalid request.");
}