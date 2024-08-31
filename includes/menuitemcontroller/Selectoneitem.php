<?php
class Selectoneitem extends MenuItem{

    private $id;
    private $errors=[];

    function __construct($id){
        $this->id= filter_var($id,FILTER_SANITIZE_NUMBER_INT);
    }
    public function viewMenuItemInfo(){
        $result=parent::viewMenuItem($this->id);
        if (!empty($result)) {
            $_SESSION['menuiteminfo'] = $result;
                //header("Location: ../../customers/custprofile.php");
               // header("Location: ../../manager/home.php");
            } else {
                $_SESSION['viewerrors']='no result found';
                $_SESSION['viewerrors'] = true;
                header("Location: ../../manager/home.php"); // Redirect back to the team page on error
            }
         
        exit(); // Ensure no further code is executed after redirection
    }

}