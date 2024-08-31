<?php
class Selectshop extends Shop {
    private $shopid;

   public function __construct($shopid) {
        $this->shopid = filter_var($shopid, FILTER_SANITIZE_NUMBER_INT);
    }

    public function viewTheShopinfo() {
        $result = parent::viewShopinfo($this->shopid);
        if (!empty($result)) {
          
            $_SESSION['shopinfo'] = $result;
            $redirecturl = "../shop/shopprofile.php";
            return ['success' => true, 'data' => $result, 'redirecturl' => $redirecturl];
        } else {
            return ['success' => false, 'message' => 'Failed to fetch data'];
        }
    }
}
