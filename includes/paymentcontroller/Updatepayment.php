<?php
class Updatepayment extends Paymentorders
{
    private $paymentmethod;
    private $amountpaid;
    private $paymentstatus;
    private $orderid;
    private $errors = [];

    public function __construct($orderid, $paymentmethod, $paymentstatus, $amountpaid)
    {
        $this->orderid = $this->validateId($orderid);
        $this->paymentmethod = $this->sanitizeString($paymentmethod);
        $this->paymentstatus = $this->sanitizeString($paymentstatus);
        $this->amountpaid = $this->sanitizeFloat($amountpaid);
    }

    private function sanitizeString($data)
    {
        return filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    private function validateId($id)
    {
        return filter_var($id, FILTER_VALIDATE_INT);
    }

    private function sanitizeFloat($float)
    {
        return number_format((float)$float, 2, '.', '');
    }

    private function isEmpty()
    {
        return empty($this->orderid) || empty($this->amountpaid) || empty($this->paymentmethod) || empty($this->paymentstatus);
    }

    public function updateThePayment()
    {
        if ($this->isEmpty()) {
            $this->errors[] = "Please fill all input";
        }

        if (empty($this->errors)) {
            $result = parent::updatePayment($this->orderid, $this->amountpaid, $this->paymentmethod, $this->paymentstatus);

            if ($result) {
                return ["success" => true, "message" => "Payment processed successfully"];
            } else {
                return ["success" => false, "message" => "Payment Failed to process!"];
            }
        } else {
            return ["success" => false, "message" => $this->errors];
        }
    }
}
?>
