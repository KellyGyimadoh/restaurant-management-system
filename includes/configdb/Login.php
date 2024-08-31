<?php

class Login extends Dbconnection
{
    private $email;
    private $password;
    private $errors;
    private $id;
    private $accounttype;
    private $phone;
    private $fname;
    private $lname;
    private $image;

    public function __construct($email, $password)
    {
        $email = $this->validateData($email);
        $this->email = $this->sanitizeEmail($email);
        $password = $this->validateData($password);
        $this->password = $this->SanitizeData($password);
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

    private function verifyUser()
    {
        try {
            $query = "SELECT id,firstname,lastname,email,phone,password,account_type,image FROM workers WHERE email=:email";
            $stmt = parent::connect()->prepare($query);

            $stmt->bindParam(":email", $this->email);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result > 0 && $this->email === $result['email']) {
                $this->id = $result['id'];
                $this->fname = $result['firstname'];
                $this->lname = $result['lastname'];
                $this->email = $result['email'];
                $this->phone = $result['phone'];
                $this->accounttype = $result['account_type'];
                $this->image=$result['image'];
                if (password_verify($this->password . SALT, $result['password'])) {

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die("User not found" . $e->getMessage());
        }
    }

    private function invalidEmail()
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }
    private function isEmpty()
    {
        if (empty($this->email) || empty($this->password)) {
            return true;
        } else {
            return false;
        }
    }

    private function verifyAccountType($account)
    {
        if ($account) {
            if ($account == "director") {
                $_SESSION['accounttype'] = "director";
            } elseif ($account == "staff") {
                $_SESSION['accounttype'] = "staff";
            } else {
                $_SESSION['accounttype'] = "";
            }
        } else {
            die();
        }
        return $account;
    }


    public function allowUserLogin()
    {
        $userinfo = [];
        if ($this->isEmpty()) {
            $this->errors['emptyinput'] = "Fill all input";
        }
        if ($this->invalidEmail()) {
            $this->errors['invalidemail'] = "Enter correct email";
        }
        if (empty($this->errors)) {
            if ($this->verifyUser()) {
                $this->verifyAccountType($this->accounttype);
                $_SESSION['loggedin'] = true;
                $_SESSION['userid'] = $this->id;
                $userinfo = [
                    "fname" => $this->fname,
                    "lname" => $this->lname,
                    "email" => $this->email,
                    "id" => $this->id,
                    "phone" => $this->phone,
                    "accounttype" => $this->accounttype,
                    "image"=>$this->image
                ];
                $_SESSION['userinfo'] = $userinfo;
                
                //$newsessionid = session_create_id();
               // $sessionid = $newsessionid . "_" . $_SESSION['userid'];
               // session_id($sessionid);
               session_regenerate_id(true);
                

                if ($this->accounttype == "director") {

                    // header("Location:../../manager/home.php");
                    return ["success" => true, "message" => "Login Successful", "redirecturl" => "../manager/home.php"];
                } elseif ($this->accounttype == "staff") {
                    //header("Location:../../user/home.php");
                    return ["success" => true, "message" => "Login Successful", "redirecturl" => "../user/home.php"];
                }
            } else {
                $this->errors['login'] = "Invalid login credentials";
                return ["success" => false, "message" => $this->errors];
                // $_SESSION['errors'] = $this->errors;
                // header("Location:../../auth/index.php");
                //die();
            }
        } else {
            //$_SESSION['errors']=$this->errors;
            return ["success" => false, "message" => $this->errors];
            // header("Location:../../auth/index.php");
            // die(); , "redirecturl"=>"../../auth/index.php"
        }
    }
}
