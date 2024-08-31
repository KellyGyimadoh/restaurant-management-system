<?php
require("../../includes/sessions.php");


//using api and json
header("Content-Type: application/json");
//retrieve raw post data
$input= json_decode(file_get_contents('php://input'),true);
$email=filter_var($input['email'],FILTER_SANITIZE_EMAIL);
$password=filter_var($input['password'],FILTER_SANITIZE_SPECIAL_CHARS);

if(empty($email)|| empty($password)){
    echo json_encode(['success'=>false,'message'=>'fill all input']);
    exit();
}
if($email==="manager@gmail.com" && $password="m"){
    //success login
    echo json_encode(['success'=>true, 'message'=>'login successful', 'redirecturl'=>'../../manager/testhome.php']);
}else{
    echo json_encode(['success'=>false, 'message'=>'login not successful', 'redirecturl'=>'../../auth/index.php']);
}
