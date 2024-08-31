<?php
class Selectoneordercustomer extends Ordercustomers{

    private $customerid;
    private $errors=[];

    public function __construct($customerid){
        $this->customerid= filter_var($customerid,FILTER_SANITIZE_NUMBER_INT);
    }
    public function vieworderCustomerInfo(){
        $result=parent::viewUser($this->customerid);
        if (!empty($result)) {
            $_SESSION['updateordercustomerinfo'] = $result;
                header("Location: ../../customers/custprofile.php");
               // header("Location: ../../manager/home.php");
            } else {
                $_SESSION['viewerrors']='no result found';
                $_SESSION['viewerrors'] = true;
                //header("Location: ../../customers/custprofile.php");
                header("Location: ../../manager/home.php"); // Redirect back to the team page on error
            }
         
        exit(); // Ensure no further code is executed after redirection
    }

}