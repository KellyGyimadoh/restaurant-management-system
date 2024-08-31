<?php
class Deleteorderitem extends Orders
{
    private $orderid;
    private $fooditemid;

    public function __construct($orderid, $fooditemid)
    {
        $this->orderid = filter_var($orderid, FILTER_SANITIZE_NUMBER_INT);
        $this->fooditemid = filter_var($fooditemid, FILTER_SANITIZE_NUMBER_INT);
    }

    public function deleteTheOrderItem()
    {
        return parent::removeOrderItem($this->orderid, $this->fooditemid);
    }
}
?>
