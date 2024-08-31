<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../../includes/sessions.php");
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configmenu/FoodItemDetails.php';
require_once '../../includes/viewfooddetails/Viewfooditem.php';
if (isset($_GET['id'])) {
    $foodItemId = $_GET['id'];
    $foodItemDetails = new Viewfooditem($foodItemId);
    if(!empty($foodItemDetails->viewthefooditem())){
       htmlspecialchars($_SESSION['fooddetails']= $foodItemDetails->viewthefooditem());
        //header("Location:../../menus/detailspage.php");
        header("Location:../../menus/editmenuprofile.php");
    }
    
   
} else {
    die("Invalid request.");
}