<?php
class Selectonejoinedorder extends Orders {
    private $orderid;

    function __construct($orderid) {
        $this->orderid = filter_var($orderid, FILTER_SANITIZE_NUMBER_INT);
    }

    public function viewOrderinfo() {
        $result = parent::viewOneorder($this->orderid);
        if (!empty($result)) {
            // Ensure items are correctly parsed into an array
            if (!empty($result['items'])) {
                $items = explode(', ', $result['items']);
                $parsedItems = [];
                foreach ($items as $item) {
                    list($name, $quantity, $price) = explode(' - ', $item);
                    $parsedItems[] = [
                        'name' => $name,
                        'quantity' => $quantity,
                        'price' => $price
                    ];
                }
                $result['items'] = $parsedItems;
            } else {
                $result['items'] = [];
            }
            $_SESSION['orderinfo'] = $result;
            $redirecturl = "../orders/newvieworderprofile.php";
            return ['success' => true, 'data' => $result, 'redirecturl' => $redirecturl];
        } else {
            return ['success' => false, 'message' => 'Failed to fetch data'];
        }
    }
}
