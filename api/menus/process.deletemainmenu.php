<?php
include("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configmenu/Menu.php");
require_once("../../includes/mainmenucontroller/Deletemenu.php");
error_reporting(E_ALL);
ini_set("display_errors",1);

if($_SERVER['REQUEST_METHOD']=="GET"){

    $id= $_GET['id'];
    $id= filter_var($id,FILTER_SANITIZE_NUMBER_INT);

    $deletemenutype= new DeleteMenu($id);
    $deletemenutype->deleteTheMenu();

}else{
    die("invalid request");
}
