<?php
class Selectjoinedorders extends Orders{

    private $receiptnumber;
    private $errors=[];

   public function __construct($receiptnumber){
        $this->receiptnumber= filter_var($receiptnumber,FILTER_SANITIZE_SPECIAL_CHARS);
        $this->receiptnumber=$this->SanitizeData($this->receiptnumber);
    }

    private function SanitizeData($data)
    {
        $data = trim($data);

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        return $data;
    }
    public function printordersummary(){
        $result=parent::getOrderDetails($this->receiptnumber);
        if ($result) {
           return $result;
            } else {
              return ['success'=>false, 'message'=>'failed to retrieve order summary'];
            }
         
        exit(); // Ensure no further code is executed after redirection
    }

}