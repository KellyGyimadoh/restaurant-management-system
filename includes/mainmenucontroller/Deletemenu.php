<?php
class DeleteMenu extends Menu{
    private $id;
    private $errors=[];
    public function __construct($id)
    {
        $this->id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    }
   
    private function isempty(){
        if(empty($this->id)){
            return true;
        }else{
            return false;
        }
    }
    public function deleteTheMenu(){
       
        if( parent::deleteMenuType($this->id)){
            $_SESSION['deletedmenu']=true;
            header("Location: ../../menus/tablemenutype.php");
            exit();
        }else{
            $_SESSION['errormenudelete']=true;
            header("Location: ../../menus/tablemenutype.php");
           
            die();
        }
    }
    public function deleteAllTheMenu(){
        if($this->isempty()){
            $this->errors[]="Empty ID";
        }
       if(empty($this->errors)){
        if( parent::deleteMenuType($this->id)){
           return (["success"=>true,"message"=>"Food item deleted successfully with it menu and section","redirecturl"=>"../menus/editmenuprofile.php"]);
        }else{
            return (["success"=>false,"message"=>"failed to delete"]);
        }
    }else{
        return (["success"=>false,"message"=>$this->errors]);
    }
    }
}