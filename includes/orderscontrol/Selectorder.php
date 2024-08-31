<?php
class Selectorder extends Orders{

    private $orderid;
    private $errors=[];

   public function __construct($orderid){
        $this->orderid= filter_var($orderid,FILTER_SANITIZE_NUMBER_INT);
        $this->orderid=$this->SanitizeData($this->orderid);
    }

    private function SanitizeData($data)
    {
        $data = trim($data);

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        return $data;
    }
    public function getOrder(){
        $result=parent::viewOrder($this->orderid);
        if ($result) {
           return $result;
            } else {
              return ['success'=>false, 'message'=>'failed to retrieve order'];
            }
         
        exit(); // Ensure no further code is executed after redirection
    }

}