<?php
class Selectonemenu extends Menu{

    private $id;
   // private $type;
    private $errors=[];

  /* public function Selectonemenu($id){
        $this->id= filter_var($id,FILTER_SANITIZE_NUMBER_INT);
    } */
    public function viewMenuInfo($id){
        $id=filter_var($id,FILTER_SANITIZE_NUMBER_INT);
        $result=parent::viewMenutype($id);
        if (!empty($result)) {
            $_SESSION['updatemenuinfo'] = $result;
            header("Location: ../../menus/editmenutype.php");
                
            } else {
                $_SESSION['viewerrors']='no result found';
                $_SESSION['viewerrors'] = true;
                header("Location: ../../manager/home.php"); // Redirect back to the team page on error
            }
         
        exit(); // Ensure no further code is executed after redirection
    }
    public function retrieveId($type, $description){
        $result = $this->selectTypeId($type, $description);
        return $result ? $result : "";
    }

}