<?php
class Updatemenusection extends Menusection
{

    private $id;
    private $menuid;
    private $section;
    private $errors = [];


    public function __construct($id,$section,$menuid)
    {
        $id = $this->validateId($id);
        $this->id = $this->SanitizeData($id);
        $menuid = $this->validateId($menuid);
        $this->menuid = $this->SanitizeData($menuid);

        $name = $this->validateData($section);
        $this->section = $this->SanitizeData($section);
       
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
        if (empty($this->menuid) || empty($this->section)) {
            return true;
        } else {
            return false;
        }
    }




    public function updateTheMenuSection()
    {

        if ($this->isEmpty()) {
            $this->errors['emptyinput'] = "Please fill all input";
        }

        if (empty($this->errors)) {
            $result = parent::updateMenuSection($this->id, $this->section, $this->menuid);
            if ($result) {
                $_SESSION['updatedmenusection'] = true;
                header("Location: ../../menus/tablemenusection.php");
                exit(); // Prevent further execution
            } else {
                $this->errors['cannotupdate'] = "Could not update user";
            }
        } else {
            $_SESSION['updatemenusectionerrors'] = $this->errors;

            header("Location: ../../menus/editmenusection.php");
        }

        // header("Location: ../../customers/tablecust.php");
        error_log("Errors: " . print_r($this->errors, true));
        exit(); // Prevent further execution
    }
}
