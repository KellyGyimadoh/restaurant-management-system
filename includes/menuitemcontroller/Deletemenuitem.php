<?php
class Deletemenuitem extends MenuItem{
    private $id;
    public function __construct($id)
    {
        $this->id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    }
   
    public function deleteTheMenuItem(){
       
        if( parent::deleteMenuItem($this->id)){
            $_SESSION['deletedmenuitem']=true;
            header("Location: ../../menus/tablemenuitems.php");
            exit();
        }else{
            $_SESSION['errormenuitemdelete']=true;
            header("Location: ../../menus/tablemenuitems.php");
           
            die();
        }
    }
}