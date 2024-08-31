<?php
class Addallorders extends Orders {
    private $receiptnumber;
    private $orderstatus;
    private $totalcost;
    private $amountowed;
    private $tax;
    private $paymentstatus;
    private $customerid;
    private $items; // New array to hold all items
    
    private $errors = [];

    public function __construct($customerid, $orderstatus, $totalcost, $amountowed, $tax, $paymentstatus, $items) {
        $this->customerid = $this->sanitizeData($this->validateId($customerid));
        $this->orderstatus = $this->sanitizeData($this->validateData($orderstatus));
        $this->totalcost = $this->sanitizeData($this->sanitizeFloat($totalcost));
        $this->amountowed = $this->sanitizeData($this->sanitizeFloat($amountowed));
        $this->tax = $this->sanitizeData($this->sanitizeFloat($tax));
        $this->paymentstatus = $this->sanitizeData($this->validateData($paymentstatus));
        $this->items = $items; // Store the items
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
        return empty($this->amountowed) || empty($this->orderstatus) || empty($this->totalcost) ;
    }

    public function AddTheOrder() {
        if ($this->isEmpty()) {
            $this->errors[] = "Please fill all input";
        }

        if (empty($this->errors)) {
            $result = parent::processOrder(
                $this->customerid, $this->orderstatus, $this->totalcost, 
                $this->amountowed, $this->tax, $this->paymentstatus, $this->items
            );

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
