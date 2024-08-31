<?php

class Insertmainmenu extends Menu{
    private $menutype;
    private $menudescription;
    private $errors=[];

    public function __construct($menutype,$menudescription)
    {
        $this->menutype= $this->SanitizeData($menutype);
        $this->menudescription= $this->SanitizeData($menudescription);
    }

    private function SanitizeData($data)
    {
        $data = trim($data);

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        return $data;
    }
    private function isEmpty()
    {
        if (empty($this->menutype)) {
            return true;
        } else {
            return false;
        }
    }
    private function typeexist(){
        $result=parent::checktypeexist($this->menutype);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function insertMenuTypes(){
        if($this->isEmpty()){
            $this->errors="Please enter all fields";
        }
        if($this->typeexist()){
            $this->errors="Type already exist";
        }
        if(empty($this->errors)){
        $result=parent::insertMenuType($this->menutype,$this->menudescription);
        if($result){
           // $_SESSION['menutype']=$this->menutype;
        return $result;
            }else{
            return ["success"=>false, "message"=>"Failed to enter class for insert items"];
        }
        }else{
            
            return ["success"=>false, "message"=>$this->errors];
        }
        exit();
    }
}