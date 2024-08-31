<?php

class Updatestatus extends Shop
{
    private $shopid;
    private $errors;
    private $status;
    public function __construct($shopid,$status) {
        $shopid = $this->sanitizePhone($shopid);
        $this->shopid = $this->SanitizeData($shopid);

        $status = $this->sanitizePhone($status);
        $this->status = $this->SanitizeData($status);

    }



    private function SanitizeData($data)
    {
        $data = trim($data);

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        return $data;
    }
    private function sanitizeEmail($email)
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        return $email;
    }
    private function validateData($data)
    {
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        return $data;
    }
    private function sanitizePhone($phone)
    {
        $phone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
        return $phone;
    }






    //error checking
    private function isEmpty()
    {
        if (empty($this->shopid) || empty($this->status)) {
            return true;
        } else {
            return false;
        }
    }



    //confirm errors else signup

    public function updateTheShopStatus()
    {
       
        if ($this->isEmpty()) {
            $this->errors[] = "Please fill all input";
        }


        if (empty($this->errors)) {
            $result = parent::updateShopStatus( $this->shopid, $this->status);

            return $result;

          
        } else {
            $_SESSION['errors'] = $this->errors;
            return ['success' => false, 'message' => $this->errors];
        }
    }
}
