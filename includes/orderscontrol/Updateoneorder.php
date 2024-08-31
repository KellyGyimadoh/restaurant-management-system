<?php
class Updateoneorder extends Orders
{
    private $orderid;
    private $amountpaid;
    private $amountowed;
    private $balance;
    private $errors = [];

    public function __construct($orderid, $amountpaid, $balance, $amountowed)
    {
        $this->orderid = $this->validateId($orderid);
        $this->amountpaid = $this->sanitizeFloat($amountpaid);
        $this->balance = $this->sanitizeFloat($balance);
        $this->amountowed = $this->sanitizeFloat($amountowed);
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
        return empty($this->orderid) || empty($this->amountpaid);
    }

    public function updateTheOneOrder()
    {
        if ($this->isEmpty()) {
            $this->errors[] = "Please fill all input";
        }

        if (empty($this->errors)) {
            $result = parent::updateOneOrder($this->orderid, $this->amountpaid, $this->balance, $this->amountowed);

            if ($result) {
                return ["success" => true, "message" => "Order processed successfully"];
            } else {
                return ["success" => false, "message" => "Order Failed to process!"];
            }
        } else {
            return ["success" => false, "message" => $this->errors];
        }
    }
}
?>
