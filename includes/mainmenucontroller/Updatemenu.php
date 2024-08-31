<?php
class UpdateMenu extends Menu
{

    private $id;
    private $menutype;
    private $menudescription;
    private $errors = [];


    public function __construct($id, $menutype, $menudescription)
    {
        $id = $this->validateId($id);
        $this->id = $this->SanitizeData($id);

        $menudescription = $this->validateData($menudescription);
        $this->menudescription = $this->SanitizeData($menudescription);
        $menutype = $this->validateData($menutype);
        $this->menutype = $this->SanitizeData($menutype);
    }


    private function SanitizeData($data)
    {
        $data = trim($data);

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        return $data;
    }

    private function validateData($data)
    {
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        return $data;
    }

    private function validateId($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        return $id;
    }









    //erro checking
    private function isEmpty()
    {
        if (empty($this->menutype) || empty($this->menudescription)) {
            return true;
        } else {
            return false;
        }
    }




    public function updateTheMenu()
    {

        if ($this->isEmpty()) {
            $this->errors['emptyinput'] = "Please fill all input";
        }

        if (empty($this->errors)) {
            $result = parent::updateMenuType($this->id, $this->menutype, $this->menudescription);
            if ($result) {
                $_SESSION['updatedmenu'] = true;
                header("Location: ../../menus/tablemenutype.php");
                exit(); // Prevent further execution
            } else {
                $this->errors['cannotupdate'] = "Could not update user";
            }
        } else {
            $_SESSION['updatemenuerrors'] = $this->errors;

            header("Location: ../../menus/editmenutype.php");
        }

        // header("Location: ../../customers/tablecust.php");
        error_log("Errors: " . print_r($this->errors, true));
        exit(); // Prevent further execution
    }
}
