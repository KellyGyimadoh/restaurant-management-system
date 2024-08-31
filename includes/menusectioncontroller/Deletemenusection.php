<?php
class DeleteMenuSection extends Menusection{
    private $id;
    public function __construct($id)
    {
        $this->id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    }
   
    public function deleteTheMenuSection(){
       
        if( parent::deleteMenuSection($this->id)){
            $_SESSION['deletemenusection']=true;
            header("Location: ../../menus/tablemenusection.php");
            exit();
        }else{
            $_SESSION['errormenusectiondelete']=true;
            header("Location: ../../customers/tablemenusection.php");
           
            die();
        }
    }
}