<?php
require_once '../../includes/sessions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../includes/configdb/Dbconnection.php';
require_once '../../includes/configmenu/Menu.php';
require_once '../../includes/configmenu/MenuSection.php';
require_once '../../includes/configmenu/Menuitem.php';
require_once '../../includes/mainmenucontroller/Updatemenu.php';

if(isset($_POST['save'])){
$id=$_POST['id'];
$menutype=$_POST['menutype'];
$menudescription=$_POST['menudescription'];
if($id){
    $updatemenu= new UpdateMenu($id,$menutype,$menudescription);
    $updatemenu->updateTheMenu();
}
}else{
    http_response_code(400);
    die("invalid request");
}