<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../../includes/sessions.php");
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configmenu/Menu.php';
require_once '../../includes/mainmenucontroller/Selectonemenu.php';

if (isset($_GET['id'])) {
    $Id = $_GET['id'];
    $menudetails = new Selectonemenu($Id);
    $menudetails->viewMenuInfo($Id);
        //header("Location:../../menus/detailspage.php");
       
}  
   
 else {
    die("Invalid request.");
}