<?php
class Selectonemenusection extends Menusection{

    private $id;
    private $errors=[];

  /*  public function __construct($id){
        $this->id= filter_var($id,FILTER_SANITIZE_NUMBER_INT);
    } */
    public function viewMenuSectionInfo($id){
        $id= filter_var($id,FILTER_SANITIZE_NUMBER_INT);
        $result=parent::viewSectionmenu($id);
        if (!empty($result)) {
            $_SESSION['menusectioninfo'] = $result;
                header("Location: ../../menus/editmenusection.php");
               
            } else {
                $_SESSION['viewerrors']='no result found';
                $_SESSION['viewerrors'] = true;
                header("Location: ../../manager/home.php"); // Redirect back to the team page on error
            }
         
        exit(); // Ensure no further code is executed after redirection
    }
    public function retrievemenusectionId($section){
        $result = $this->selectMenuSectionId($section);
        return $result ? $result : "";
    }
}