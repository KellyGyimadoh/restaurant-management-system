
<?php
class Insertreceipt extends Receipt {
  
    private $orderreceipt;
    private $paymentid;
   private $amountpaid;
   private $balance;
    
    
    private $paymentmethod;
    private $orderid;
    
    
    private $errors = [];

    public function __construct($paymentid,$orderid,$orderreceipt,$amountpaid,$balance) {
        $this->orderid = $this->sanitizeData($this->validateId($orderid));
        $this->paymentid= $this->sanitizeData($this->validateId($paymentid));
        $this->orderreceipt = $this->sanitizeData($this->validateData($orderreceipt));
     
        $this->amountpaid = $this->sanitizeFloat($amountpaid);
        $this->balance = $this->sanitizeFloat($balance);
        
     
        
    }

    private function sanitizeData($data) {
        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        return $data;
    }

    private function validateData($data) {
        return filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    private function validateId($id) {
        return filter_var($id, FILTER_VALIDATE_INT);
    }

    private function sanitizeFloat($float) {
        $float = filter_var($float, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        return number_format((float)$float, 2, '.', ''); // Format with two decimal places
    }
    
    private function isEmpty() {
        return empty($this->paymentid) || empty($this->orderid) || empty($this->orderreceipt);
        
    }

    public function AddTheReceipt() {
        if ($this->isEmpty()) {
            $this->errors[] = "Please fill all input";
        }

        if (empty($this->errors)) {
            $result = parent::InsertReceipt($this->paymentid,$this->orderid,$this->orderreceipt
                    ,$this->amountpaid,$this->balance);
            if ($result) {
                return $result;
            } else {
                return ["success" => false, "message" => "Orders Failed to process!"];
            }
        } else {
            return ["success" => false, "message" => $this->errors];
        }

        error_log("Errors: " . print_r($this->errors, true));
        exit(); // Prevent further execution
    }
}



