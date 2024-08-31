<?php
require("functions.php");
define("SALT","ajshaddbjshjejbjddjjdj");
ini_set("session.use_only_cookies",1);
ini_set("session.use_strict_mode",1);
session_set_cookie_params([
    "lifetime"=>1800,
    "domain"=>"localhost",
    "path"=>"/",
    "secure"=>true,
    "httponly"=>true
]);
session_start();
//set time zone
date_default_timezone_set('Africa/Accra');
if(isset($_SESSION['userid'])){
    if(!isset($_SESSION['last_regeneration'])){
        regenerate_sessionid_loggedin();
    }else{
        $interval= 60*50;
        if(time()-$_SESSION['last_regeneration']>=$interval){
            regenerate_sessionid_loggedin();
        }

    }
    
}else{
    if(!isset($_SESSION['last_regeneration'])){
        regenerate_sessionid();
    }else{
        $interval= 60*50;
        if(time()-$_SESSION['last_regeneration']>=$interval){
            regenerate_sessionid();
        }
    }
   
}

