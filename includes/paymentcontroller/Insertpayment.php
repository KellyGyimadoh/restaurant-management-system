
<?php
class Insertpayment extends Paymentorders {
  
    private $orderstatus;
    private $totalcost;
    private $amountpaid;
    
    private $paymentstatus;
    private $paymentmethod;
    private $orderid;
    
    
    private $errors = [];

    public function __construct($orderid,$amountpaid,$paymentmethod,$paymentstatus) {
        $this->orderid = $this->sanitizeData($this->validateId($orderid));
        $this->paymentstatus = $this->sanitizeData($this->validateData($paymentstatus));
        $this->paymentmethod = $this->sanitizeData($this->validateData($paymentmethod));
        
        $this->amountpaid = $this->sanitizeData($this->sanitizeFloat($amountpaid));
       
        $this->paymentstatus = $this->sanitizeData($this->validateData($paymentstatus));
        
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
        return empty($this->amountpaid) || empty($this->paymentstatus) || empty($this->orderid) || empty($this->paymentmethod);
    }

    public function AddThePayment() {
        if ($this->isEmpty()) {
            $this->errors[] = "Please fill all input";
        }

        if (empty($this->errors)) {
            $result = parent::InsertPayment($this->orderid,$this->amountpaid,$this->paymentmethod,$this->paymentstatus);
                
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



