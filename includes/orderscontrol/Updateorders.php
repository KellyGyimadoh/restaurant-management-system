<?php
class Updateorders extends Orders
{

    private $receiptnumber;
    private $orderstatus;
    private $totalcost;
    private $amountpaid;
    private $amountowed;
    private $balance;
    private $orderid;

    private $errors = [];


    public function __construct($orderid,$receiptnumber,$orderstatus,$totalcost, $amountpaid, $balance,$amountowed)
    {
        $orderid = $this->validateId($orderid);
        $this->orderid = $this->SanitizeData($orderid);

        $receiptnumber = $this->validateData($receiptnumber);
        $this->receiptnumber = $this->SanitizeData($receiptnumber);
        $orderstatus = $this->validateData($orderstatus);
        $this->orderstatus = $this->SanitizeData($orderstatus);
        $totalcost= $this->sanitizeFloat($totalcost);
        $this->totalcost= $this->SanitizeData($totalcost);
        $amountpaid= $this->sanitizeFloat($amountpaid);
        $this->amountpaid= $this->SanitizeData($amountpaid);
        $balance= $this->sanitizeFloat($balance);
        $this->balance= $this->SanitizeData($balance);
        $amountowed= $this->sanitizeFloat($amountowed);
        $this->amountowed= $this->SanitizeData($amountowed);
    }


    private function SanitizeData($data)
    {
        $data = trim($data);

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        return $data;
    }

    private function validateData($data)
    {
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        return $data;
    }

    private function validateId($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        return $id;
    }

    private function sanitizeFloat($float){
        $float= filter_var($float,FILTER_SANITIZE_NUMBER_FLOAT);
        return $float;
    }







    //erro checking
    private function isEmpty()
    {
        if (empty($this->orderid) || empty($this->receiptnumber)|| empty($this->amountpaid)|| empty($this->amountowed)|| empty($this->orderstatus)|| empty($this->balance)) {
            return true;
        } else {
            return false;
        }
    }




    public function updateTheOrder()
    {

        if ($this->isEmpty()) {
            $this->errors[] = "Please fill all input";
        }

        if (empty($this->errors)) {
            $result = parent::updateOrders($this->orderid,$this->receiptnumber,$this->orderstatus,$this->totalcost,
             $this->amountpaid, $this->balance,$this->amountowed);
            if ($result) {
            return(["success"=>true, "message"=>"Orders processed successfully"]);
            } else {
                return(["success"=>false, "message"=>"Orders Failed to  process!"]);
            }
        } else {
            return(["success"=>false, "message"=>$this->errors]);
        }

        // header("Location: ../../customers/tablecust.php");
        error_log("Errors: " . print_r($this->errors, true));
        exit(); // Prevent further execution
    }
}
