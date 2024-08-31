<?php

class Insertmail extends Mail
{
    private $subject;
    private $message;
    private $recipient;

    private $errors;
    public function __construct($subject, $recipient, $message)
    {
        $subject = $this->validateData($subject);
        $this->subject = $this->SanitizeData($subject);

        $message = $this->validateData($message);
        $this->message = $this->SanitizeData($message);


        $recipient = $this->validateData($recipient);
        $this->recipient = $this->sanitizeEmail($recipient);
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

    //insert into database




    //error checking
    private function isEmpty()
    {
        if (empty($this->recipient) || empty($this->message)) {
            return true;
        } else {
            return false;
        }
    }

    private function invalidEmail()
    {
        if (!filter_var($this->recipient, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }



    /* private function digitOnly()
    {
        $phoneNumber = preg_replace("/[^0-9]/", "", $this->phone);
        return strlen($phoneNumber) !== 10;
    } */


    //confirm errors else signup

    public function logTheMail()
    {

        if ($this->invalidEmail()) {
            $this->errors[] = "Email is invalid";
        }

        if ($this->isEmpty()) {
            $this->errors[] = "Please fill all input";
        }


        if (empty($this->errors)) {
            $result = parent::InsertMail($this->subject, $this->recipient, $this->message,);
            if ($result) {
                $mailinfo = [
                    "subject" => $this->subject,
                    "recipient" => $this->recipient,
                    "message" => $this->message

                ];
                $_SESSION['mailinfo'] = $mailinfo;
                return $result;
            } else {
                return (["success" => false, "message" => $this->errors]);
            }
        }
    }
}
