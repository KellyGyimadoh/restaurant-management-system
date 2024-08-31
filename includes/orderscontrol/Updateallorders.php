<?php
class Updateallorders extends Orders
{
    private $orderid;
    private $totalcost;
    private $amountowed;
    private $items;
    private $errors = [];

    public function __construct($orderid, $totalcost, $amountowed, $items)
    {
        $this->orderid = $this->sanitizeData($this->validateId($orderid));
        $this->totalcost = $this->sanitizeData($this->sanitizeFloat($totalcost));
        $this->amountowed = $this->sanitizeData($this->sanitizeFloat($this->removeTrailingZeroes($amountowed)));
        $this->items = $items;
    }

    private function removeTrailingZeroes($number)
    {
        $numberStr = rtrim($number, '0');
        if (substr($numberStr, -1) === '.') {
            $numberStr = rtrim($numberStr, '.');
        }
        return $numberStr;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function updateAllTheOrders()
    {
        if (empty($this->errors)) {
            $result = parent::updateAllOrder($this->orderid, $this->totalcost, $this->amountowed, $this->items);
            return $result;
        } else {
            return ["success" => false, "message" => $this->errors];
        }
    }

    private function sanitizeData($data)
    {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    private function validateId($id)
    {
        if (filter_var($id, FILTER_VALIDATE_INT)) {
            return $id;
        } else {
            $this->errors[] = "Invalid ID";
        }
    }

    private function sanitizeFloat($value)
    {
        if (filter_var($value, FILTER_VALIDATE_FLOAT)) {
            return $value;
        } else {
            $this->errors[] = "Invalid float value";
        }
    }
}
?>
