<?php
class Deletecontrol extends Deleteuser{

    private $id;
    public function __construct($id)
    {
        $this->id=$id;
    }

    public function performDelete(){
        if( parent::delete($this->id)){
            $_SESSION['deleted']=true;
            header("Location:../../users/table.php");
            die();
        }else{
            header("Location:../../users/table.php");
            $_SESSION['errordelete']=true;
            die();
        }
       
    }

}