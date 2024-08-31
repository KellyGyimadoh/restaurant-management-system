<?php
include("../../includes/sessions.php");
if(isset($_POST['logout'])){
logout();
//header("Location:../../auth/login.php");
die();
}