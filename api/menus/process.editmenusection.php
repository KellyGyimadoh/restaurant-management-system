<?php
require_once '../../includes/sessions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configmenu/Menu.php';
require_once '../../includes/configmenu/MenuSection.php';
require_once '../../includes/menusectioncontroller/Updatemenusection.php';

if(isset($_POST['save'])){
$id=$_POST['id'];
$menuid=$_POST['menuid'];
$section=$_POST['section'];
if($id){
    $updatemenusection= new Updatemenusection($id,$section,$menuid);
    $updatemenusection->updateTheMenuSection();
}
}else{
    http_response_code(400);
    die("invalid request");
}