<?php
function regenerate_sessionid_loggedin()
{
    session_regenerate_id(true); // Regenerate session ID to create a new session
    $userid = $_SESSION['userid']; // Retrieve the user ID from session
    $newsessionid = session_create_id(); // Create a new session ID
    session_commit(); // Close the current session to apply changes
    session_id($newsessionid . "_" . $userid); // Set the new session ID
    session_start(); // Restart the session with the new session ID
    $_SESSION['userid'] = $userid; // Reassign the user ID to the session
    $_SESSION['last_regeneration'] = time(); // Update the session regeneration time

}




function regenerate_sessionid()
{
    if (!isset($_SESSION['last_regeneration'])) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    } else {
        $interval = 60 * 50;
        if (time() - $_SESSION['last_regeneration'] >= $interval) {

            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }
}


function checkAccount($accounttype)
{
    $account = isset($_SESSION['accounttype']) ? $_SESSION['accounttype'] : "";
    //get available account
    $allow = true;
    if (!empty($accounttype) && isset($accounttype) && $account === $accounttype) {
        if (in_array($accounttype, $account) && $allow === true) {
        } else {
            header("../auth/index.php");
        }
    } else {
        header("Location:../auth/index.php");
        die();
    }
}


function checkfoodexist($fooditem, $conn)
{
    try {
        $query = "SELECT fooditem FROM menusitem WHERE fooditem = :fooditem";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":fooditem", $fooditem);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $fooditem === $result['fooditem'];
    } catch (PDOException $e) {
        error_log("Could not select menu items: " . $e->getMessage());
        throw new Exception("Could not select menu items");
    }
}


function logout()
{

    $_SESSION['logged_out'] = true; // Set a session variable to indicate that the user has logged out
    // session_destroy(); // Destroy all data registered to a session

    session_destroy();

    header("Location: ../../auth/index.php?logout=true"); // Redirect to the login page
    //header("Refresh: 2; URL=login.php"); // Redirect to the login page
    exit; // Terminate the script
}
function isloggedin()
{
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        return true;
    } else {
        return false;
    }
}


 function signupData()
{
    if (isset($_SESSION['signupdata']['fname'])) {
        echo "<div class='form-group row'>
<div class='col-sm-6 mb-3 mb-sm-0'  ><input class='form-control form-control-user' type='text' id='fname' placeholder='First Name' name='fname' value=" . $_SESSION['signupdata']['fname'] . "></div>";
    } else {
        echo "<div class='form-group row'>
<div class='col-sm-6 mb-3 mb-sm-0'  ><input class='form-control form-control-user' type='text' id='fname' placeholder='First Name' name='fname'></div>";
    }
    if (isset($_SESSION['signupdata']['lname'])) {
        echo "<div class='col-sm-6'><input class='form-control form-control-user' type='text' id='lname' placeholder='Last Name' name='lname'  value=" . $_SESSION['signupdata']['lname'] . "></div>
    </div>";
    } else {
        echo "<div class='col-sm-6'><input class='form-control form-control-user' type='text' id='lname' placeholder='Last Name' name='lname'></div>
    </div>";
    }

    if (isset($_SESSION['signupdata']['email']) && !isset($_SESSION['errors']['invalidemail']) && !isset($_SESSION['errors']['emailexist'])) {
        echo "<div class='form-group'><input class='form-control form-control-user' type='email' id='email' aria-describedby='emailHelp' placeholder='Email Address' name='email' value=" . $_SESSION['signupdata']['email'] . "></div>";
    } else {
        echo "<div class='form-group'><input class='form-control form-control-user' type='email' id='email' aria-describedby='emailHelp' placeholder='Email Address' name='email'></div>";
    }
    if (isset($_SESSION['signupdata']['phone']) && !isset($_SESSION['errors']['wrongphone'])) {
        echo "<div class='form-group'><input class='form-control form-control-user' type='tel' id='phone' aria-describedby='phoneHelp' placeholder='Telephone' name='phone' value=" . $_SESSION['signupdata']['phone'] . "></div>";
    } else {
        echo "<div class='form-group'><input class='form-control form-control-user' type='tel' id='phone' aria-describedby='phoneHelp' placeholder='Telephone' name='phone' ></div>";
    }

    echo "<div class='form-group row'>
<div class='col-sm-6 mb-3 mb-sm-0'><input class='form-control form-control-user' type='password' id='password' placeholder='Password' name='password'></div>
<div class='col-sm-6'><input class='form-control form-control-user' type='password' id='rptpassword' placeholder='Repeat Password' name='rptpassword'></div>";
}


function ViewUserSignupForm()
{
    if (isset($_SESSION['updatesignupinfo']['id'])) {
        echo "
        <div class='col-sm-4' hidden >
            <div class='form-group'><label for='id'><strong>ID</strong></label><input class='form-control' type='text'  name='id' value='" . $_SESSION['updatesignupinfo']['id'] . "' hidden ></div>
            </div>";
    } else {
        echo "
        <div class='col-sm-4' hidden>
            <div class='form-group'><label for='id'><strong>ID</strong></label><input class='form-control' type='text'  name='id' hidden></div>
            </div>";
    }
    if (isset($_SESSION['updatesignupinfo']['firstname'])) {
        echo "<div class='form-row'>
    <div class='col'>
        <div class='form-group'><label for='fname'><strong>First Name</strong></label><input class='form-control' type='text' placeholder='John' name='fname' value='" . $_SESSION['updatesignupinfo']['firstname'] . "'></div>
    </div>";
    } else {
        echo "<div class='form-row'>
    <div class='col'>
        <div class='form-group'><label for='fname'><strong>First Name</strong></label><input class='form-control' type='text' placeholder='John' name='fname'></div>
    </div>";
    }

    if (isset($_SESSION['updatesignupinfo']['lastname'])) {
        echo "<div class='col'>
    <div class='form-group'><label for='lname'><strong>Last Name</strong></label><input class='form-control' type='text' placeholder='Doe' name='lname' value='" . $_SESSION['updatesignupinfo']['lastname'] . "'></div>
</div>";
    } else {
        echo "<div class='col'>
    <div class='form-group'><label for='lname'><strong>Last Name</strong></label><input class='form-control' type='text' placeholder='Doe' name='lname'></div>
</div>";
    }

    if (isset($_SESSION['updatesignupinfo']['account_type'])) {
        $accountType = $_SESSION['updatesignupinfo']['account_type'];

        echo "<div class='col'>
    <div class='form-group'><label for='accounttype'><strong>Account Type</strong></label><select name='accounttype'>Account Type
    <option value='' " . ($accountType == '' ? 'selected' : '') . ">Select Option</option>
    <option value='director' " . ($accountType == 'director' ? 'selected' : '') . ">Director</option>
    <option value='staff' " . ($accountType == 'staff' ? 'selected' : '') . ">Staff</option>
    
    </select></div>
</div>
</div>";
    } else {
        $accountType = '';
        echo "<div class='col'>
    <div class='form-group'><label for='accounttype'><strong>Account Type</strong></label><select name='accounttype'>Account Type
    <option value=''>Select Option</option>
        <option value='director'>Director</option>
        <option value='staff'>Staff</option>
    </select></div>
</div>
</div>";
    }
    if (isset($_SESSION['updatesignupinfo']['email'])) {
        echo "<div class='form-row'> 
    <div class='col'>
       <div class='form-group'><label for='email'><strong>Email Address</strong></label><input class='form-control' type='email' placeholder='user@example.com' name='email' value='" . $_SESSION['updatesignupinfo']['email'] . "'></div>
    </div>";
    } else {
        echo "<div class='form-row'> 
    <div class='col'>
       <div class='form-group'><label for='email'><strong>Email Address</strong></label><input class='form-control' type='email' placeholder='user@example.com' name='email'></div>
    </div>";
    }
    if (isset($_SESSION['updatesignupinfo']['phone'])) {
        echo "<div class='col'>
    <div class='form-group'><label for='phone'><strong>Phone</strong></label><input class='form-control' type='tel' placeholder='000-000-000' name='phone' value='" . $_SESSION['updatesignupinfo']['phone'] . "'></div>   
</div>";
    } else {
        echo "<div class='col'>
    <div class='form-group'><label for='phone'><strong>Phone</strong></label><input class='form-control' type='tel' placeholder='000-000-000' name='phone'></div>
    
</div>";
    }
}

function formatTimeWithAmPm($time)
{
    if (!$time) {
        return false; // Handle the invalid input appropriately
    }
    $dateTime = DateTime::createFromFormat('g:i A', $time);
    if ($dateTime === false) {
        return false; // Handle the invalid input appropriately
    }
    return $dateTime->format('h:i A');
}



//customer details for update
//shop profile
//generate country


/*function fetchCountries() {
    $url = "https://restcountries.com/v3.1/all";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function generateCountryOptions($countries, $selectedCountry) {
    $options = "";
    foreach ($countries as $country) {
        $countryName = $country['name']['common'];
        $selected = ($countryName == $selectedCountry) ? "selected" : "";
        $options .= "<option value='{$countryName}' {$selected}>{$countryName}</option>";
    }
    return $options;
}

function signupShop(){
    $countries = fetchCountries();
    $selectedCountry = isset($_SESSION['signupshop']['country']) ? $_SESSION['signupshop']['country'] : "";
    $countryOptions = generateCountryOptions($countries, $selectedCountry);

    echo "<div class='form-group row'>";

    echo "<div class='col-sm-6 mb-3 mb-sm-0'>
            <input class='form-control form-control-user' type='text' id='shopname' placeholder='Shop Name' name='shopname' value='" . ($_SESSION['signupshop']['name'] ?? '') . "'>
          </div>";

    echo "<div class='col-sm-6'>
            <input class='form-control form-control-user' type='url' id='website' placeholder='Website' name='website' value='" . ($_SESSION['signupshop']['website'] ?? '') . "'>
          </div>";

    echo "</div>";

    echo "<div class='form-group'>
            <input class='form-control form-control-user' type='email' id='email' aria-describedby='emailHelp' placeholder='Email Address' name='email' value='" . ($_SESSION['signupshop']['email'] ?? '') . "'>
          </div>";

    echo "<div class='form-group'>
            <input class='form-control form-control-user' type='tel' id='phone' aria-describedby='phoneHelp' placeholder='Telephone' name='phone' value='" . ($_SESSION['signupshop']['phone'] ?? '') . "'>
          </div>";

    echo "<div class='form-group row'>";

    echo "<div class='col-sm-6 mb-3 mb-sm-0'>
           Select Country <select class='form-control form-control-user' id='country' name='country'>
                <option value=''>Select Country</option>" . $countryOptions . "
            </select>
          </div>";

    echo "<div class='col-sm-6'>
            <input class='form-control form-control-user' type='text' id='city' placeholder='Name Of City' name='city' value='" . ($_SESSION['signupshop']['city'] ?? '') . "'>
          </div>";

    echo "</div>";

    echo "<div class='form-group row'>";

    echo "<div class='col-6 p-2'>
            <input class='form-control form-control-user' type='text' id='state' placeholder='State' name='state' value='" . ($_SESSION['signupshop']['state'] ?? '') . "'>
          </div>";

    echo "<div class='col-6 p-2'>
            <input class='form-control form-control-user' type='text' id='address' placeholder='Address P.O Box' name='address' value='" . ($_SESSION['signupshop']['address'] ?? '') . "'>
          </div>";

    echo "<div class='col-6 p-2'>
            <input class='form-control form-control-user' type='number' id='postalcode' min='0' placeholder='Postal Code' name='postalcode' value='" . ($_SESSION['signupshop']['postalcode'] ?? '') . "'>
          </div>";

    echo "<div class='col-6 p-2'>
            <input class='form-control form-control-user' type='text' id='openinghours' placeholder='Opening Hours' name='openinghours' value='" . ($_SESSION['signupshop']['openinghours'] ?? '') . "'>
          </div>";

    echo "</div>";

    echo "<div class='form-group'>
            <textarea rows='3' cols='100' name='description' placeholder='Description' class='form-control'>" . ($_SESSION['signupshop']['description'] ?? '') . "</textarea>
          </div>";

    echo "</div>";
}*/







function viewShopProfile(){
    if (isset($_SESSION['shopinfo']['id'])) {
        echo "
        <div class='col-sm-4' hidden>
            <div class='form-group'><label for='id'><strong>ID</strong></label><input class='form-control' type='text' disabled  name='id' value='" . $_SESSION['shopinfo']['id'] . "'  hidden></div>
        </div>";
    } else {
        echo "
        <div class='col-sm-4' hidden>
            <div class='form-group'><label for='id'><strong>ID</strong></label><input class='form-control' type='text'  name='id' hidden></div>
        </div>";
    }

    echo "<div class='row'>
    <div class='col-sm-6'>
        <div class='form-group form_pos'>";

    if (isset($_SESSION['shopinfo']['name'])) {
        echo "<input type='text' name='shopname' disabled placeholder='Shop name' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Shop name\"' value='" . $_SESSION['shopinfo']['name'] . "'>";
    } else {
        echo "<input type='text' name='shopname' placeholder='Shop name' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Shop name\"'>";
    }

    echo "<span class='form_icon'></span>
        </div>
    </div>";

    echo "<div class='col-sm-6'>
        <div class='form-group form_pos'>";

    if (isset($_SESSION['shopinfo']['email'])) {
        echo "<input type='email' name='email' disabled placeholder='Shop email' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Shop email\"' value='" . $_SESSION['shopinfo']['email'] . "'>";
    } else {
        echo "<input type='email' name='email' placeholder='Shop email' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Shop email\"'>";
    }

    echo "<span class='form_icon'></span>
        </div>
    </div>";

    if (isset($_SESSION['shopinfo']['phone'])) {
        echo "</div>
        <div class='row'>
        <div class='col-6'>
            <div class='form-group form_pos'>
                <input type='text' name='phone' disabled placeholder='Phone' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Phone\"' value='" . $_SESSION['shopinfo']['phone'] . "'>
                <span class='form_icon'></span>
            </div>
        </div>";
    } else {
        echo "</div>
        <div class='row'>
        <div class='col-6'>
            <div class='form-group form_pos'>
                <input type='text' name='phone' placeholder='Phone' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Phone\"'>
                <span class='form_icon'></span>
            </div>
        </div>";
    }
    if (isset($_SESSION['shopinfo']['country'])) {
        echo "
        <div class='col-6'>
            <div class='form-group form_pos'>
                <input type='text' name='country' disabled placeholder='Name of Country' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Country\"' value='" . $_SESSION['shopinfo']['country'] . "'>
                <span class='form_icon'></span>
            </div>
        </div>";
    } else {
        echo "
        <div class='col-6'>
            <div class='form-group form_pos'>
                <input type='text' name='country' placeholder='Name of Country' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Country\"'>
                <span class='form_icon'></span>
            </div>
        </div>";
    }
    if (isset($_SESSION['shopinfo']['state'])) {
        echo "</div>
        <div class='row'>
        <div class='col-6'>
            <div class='form-group form_pos'>
                <input type='text' name='state' disabled placeholder='State' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"State\"' value='" . $_SESSION['shopinfo']['state'] . "'>
                <span class='form_icon'></span>
            </div>
        </div>";
    } else {
        echo "</div>
        <div class='row'>
        <div class='col-6'>
            <div class='form-group form_pos'>
                <input type='text' name='state' placeholder='State' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"State\"'>
                <span class='form_icon'></span>
            </div>
        </div>";
    }
    if (isset($_SESSION['shopinfo']['city'])) {
        echo "
        <div class='col-6'>
            <div class='form-group form_pos'>
                <input type='text' name='city' disabled placeholder='Name of City' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"City\"' value='" . $_SESSION['shopinfo']['city'] . "'>
                <span class='form_icon'></span>
            </div>
        </div>";
    } else {
        echo "
        <div class='col-6'>
            <div class='form-group form_pos'>
                <input type='text' name='city' placeholder='Name of City' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"City\"'>
                <span class='form_icon'></span>
            </div>
        </div>";
    }
    if (isset($_SESSION['shopinfo']['website'])) {
        echo "</div>
        <div class='row'>
        <div class='col-6'>
            <div class='form-group form_pos'>
                <input type='text' name='website' disabled placeholder='Website' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Website\"' value='" . $_SESSION['shopinfo']['website'] . "'>
                <span class='form_icon'></span>
            </div>
        </div>";
    } else {
        echo "</div>
        <div class='row'>
        <div class='col-6'>
            <div class='form-group form_pos'>
                <input type='text' name='website' placeholder='Website' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Website\"'>
                <span class='form_icon'></span>
            </div>
        </div>";
    }
    if (isset($_SESSION['shopinfo']['address'])) {
        echo "
        <div class='col-6'>
            <div class='form-group form_pos'>
                <input type='text' name='address' disabled placeholder=P.O Address' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Address\"' value='" . $_SESSION['shopinfo']['address'] . "'>
                <span class='form_icon'></span>
            </div>
        </div>";
    } else {
        echo "
        <div class='col-6'>
            <div class='form-group form_pos'>
                <input type='text' name='address' placeholder='P.O Address' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Address\"'>
                <span class='form_icon'></span>
            </div>
        </div>";
    }
    if (isset($_SESSION['shopinfo']['opening_hours'])) {
        echo "</div>
        <div class='row'>
        <div class='col-6'>
            <div class='form-group form_pos'>
                <input type='text' name='openinghours' disabled placeholder='Opening Hours' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Opening Hours\"' value='" . $_SESSION['shopinfo']['opening_hours'] . "'>
                <span class='form_icon'></span>
            </div>
        </div>";
    } else {
        echo "</div>
        <div class='row'>
        <div class='col-6'>
            <div class='form-group form_pos'>
                <input type='text' name='openinghours' placeholder='Opening Hours' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Opening Hours\"'>
                <span class='form_icon'></span>
            </div>
        </div>";
    }
    if (isset($_SESSION['shopinfo']['postal_code'])) {
        echo "
        <div class='col-6'>
            <div class='form-group form_pos'>
                <input type='text' name='postalcode' disabled placeholder=Postal Code' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Postal Code\"' value='" . $_SESSION['shopinfo']['postal_code'] . "'>
                <span class='form_icon'></span>
            </div>
        </div>";
    } else {
        echo "
        <div class='col-6'>
            <div class='form-group form_pos'>
                <input type='text' name='postalcode' placeholder='Postal Code' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Postal Code\"'>
                <span class='form_icon'></span>
            </div>
        </div>";
    } 
    if (isset($_SESSION['shopinfo']['status'])) {
        $status = $_SESSION['shopinfo']['status'];

        echo "<div class='col'>
    <div class='form-group'><label for='status'><strong>Status</strong></label><select name='status' disabled>Status
    <option value='' " . ($status == '' ? 'selected' : '') . ">Select Option</option>
    <option value='2' " . ($status == '2' ? 'selected' : '') . ">Active</option>
    <option value='1' " . ($status == '1' ? 'selected' : '') . ">Suspended</option>
    
    </select></div>
</div>
</div>";
    } else {
        $status = '';
        echo "<div class='col'>
    <div class='form-group'><label for='status'><strong>Status</strong></label><select name='status'>Status
    <option value=''>Select Option</option>
        <option value='2'>Active</option>
        <option value='1'>Suspended</option>
    </select></div>
</div>
</div>"; 
    }
   
    if (isset($_SESSION['shopinfo']['description'])) {

        echo "
       
        
        <div class='form-group'>
        
        <textarea rows='3' cols='100' name='description' placeholder='Description' class='form-control' disabled onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Description\"'>" . $_SESSION['shopinfo']['description'] . "</textarea>";
    } else {
 echo" <div class='form-group'>
         <div class= 'col-12'>
        <textarea rows='3' cols='200' name='description' placeholder='Description'  class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Description\"'></textarea>";
    }
 echo "</div>";
}

///insert shop

function signupShop(){
    echo "<div class='form-group row'>";

    if (isset($_SESSION['signupshop']['name'])) {
        echo "<div class='col-sm-6 mb-3 mb-sm-0'>
                <input class='form-control form-control-user' type='text' id='shopname' placeholder='Shop Name' name='shopname' value='" . $_SESSION['signupshop']['name'] . "'>
              </div>";
    } else {
        echo "<div class='col-sm-6 mb-3 mb-sm-0'>
                <input class='form-control form-control-user' type='text' id='shopname' placeholder='Shop Name' name='shopname'>
              </div>";
    }

    if (isset($_SESSION['signupshop']['website'])) {
        echo "<div class='col-sm-6'>
                <input class='form-control form-control-user' type='url' id='website' placeholder='Website' name='website' value='" . $_SESSION['signupshop']['website'] . "'>
              </div>";
    } else {
        echo "<div class='col-sm-6'>
                <input class='form-control form-control-user' type='url' id='website' placeholder='Website' name='website'>
              </div>";
    }

    echo "</div>"; // Closing the row div

    if (isset($_SESSION['signupshop']['email']) && !isset($_SESSION['errors']['invalidemail']) && !isset($_SESSION['errors']['emailexist'])) {
        echo "<div class='form-group'>
                <input class='form-control form-control-user' type='email' id='email' aria-describedby='emailHelp' placeholder='Email Address' name='email' value='" . $_SESSION['signupshop']['email'] . "'>
              </div>";
    } else {
        echo "<div class='form-group'>
                <input class='form-control form-control-user' type='email' id='email' aria-describedby='emailHelp' placeholder='Email Address' name='email'>
              </div>";
    }

    if (isset($_SESSION['signupshop']['phone']) && !isset($_SESSION['errors']['wrongphone'])) {
        echo "<div class='form-group'>
                <input class='form-control form-control-user' type='tel' id='phone' aria-describedby='phoneHelp' placeholder='Telephone' name='phone' value='" . $_SESSION['signupshop']['phone'] . "'>
              </div>";
    } else {
        echo "<div class='form-group'>
                <input class='form-control form-control-user' type='tel' id='phone' aria-describedby='phoneHelp' placeholder='Telephone' name='phone'>
              </div>";
    }

    echo "<div class='form-group row'>";

    if (isset($_SESSION['signupshop']['country'])) {
        echo "<div class='col-sm-6 mb-3 mb-sm-0'>
                <input class='form-control form-control-user' type='text' id='country' placeholder='Country' name='country' value='" . $_SESSION['signupshop']['country'] . "'>
              </div>";
    } else {
        echo "<div class='col-sm-6 mb-3 mb-sm-0'>
                <input class='form-control form-control-user' type='text' id='country' placeholder='Country' name='country'>
              </div>";
    }

    if (isset($_SESSION['signupshop']['city'])) {
        echo "<div class='col-sm-6'>
                <input class='form-control form-control-user' type='text' id='city' placeholder='Name Of City' name='city' value='" . $_SESSION['signupshop']['city'] . "'>
              </div>";
    } else {
        echo "<div class='col-sm-6'>
                <input class='form-control form-control-user' type='text' id='city' placeholder='Name Of City' name='city'>
              </div>";
    }

    echo "</div>"; // Closing the row div

    echo "<div class='form-group row'>";

    if (isset($_SESSION['signupshop']['state'])) {
        echo "<div class='col-6 p-2'>
                <input class='form-control form-control-user' type='text' id='state' placeholder='State' name='state' value='" . $_SESSION['signupshop']['state'] . "'>
              </div>";
    } else {
        echo "<div class='col-6 p-2'>
                <input class='form-control form-control-user' type='text' id='state' placeholder='State' name='state'>
              </div>";
    }

    if (isset($_SESSION['signupshop']['address'])) {
        echo "<div class='col-6 p-2'>
                <input class='form-control form-control-user' type='text' id='address' placeholder='Address P.O Box' name='address' value='" . $_SESSION['signupshop']['address'] . "'>
              </div>";
    } else {
        echo "<div class='col-6 p-2'>
                <input class='form-control form-control-user' type='text' id='address' placeholder='Address P.O Box' name='address'>
              </div>";
    }

    if (isset($_SESSION['signupshop']['postalcode'])) {
        echo "<div class='col-6 p-2'>
                <input class='form-control form-control-user' type='number' id='postalcode' min='0' placeholder='Postal Code' name='postalcode' value='" . $_SESSION['signupshop']['postalcode'] . "'>
              </div>";
    } else {
        echo "<div class='col-6 p-2'>
                <input class='form-control form-control-user' type='number' id='postalcode' min='0' placeholder='Postal Code' name='postalcode'>
              </div>";
    }

    if (isset($_SESSION['signupshop']['openinghours'])) {
        echo "<div class='col-6 p-2'>
                <input class='form-control form-control-user' type='text' id='openinghours' placeholder='Opening Hours' name='openinghours' value='" . $_SESSION['signupshop']['openinghours'] . "'>
              </div>";
    } else {
        echo "<div class='col-6 p-2'>
                <input class='form-control form-control-user' type='text' id='openinghours' placeholder='Opening Hours' name='openinghours'>
              </div>";
    }
    

    echo "</div>"; // Closing the row div

    if (isset($_SESSION['signupshop']['description'])) {
        echo "<div class='form-group'>
                <textarea rows='3' cols='100' name='description' placeholder='Description' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Description\"'>" . $_SESSION['signupshop']['description'] . "</textarea>
              </div>";
    } else {
        echo "<div class='form-group'>
                <textarea rows='3' cols='100' name='description' placeholder='Description' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Description\"'></textarea>
              </div>";
    }

    echo "</div>"; // Closing the outer div
}




function viewCustomerForm()
{
    if (isset($_SESSION['updatecustomerinfo']['id'])) {
        echo "
        <div class='col-sm-4' hidden>
            <div class='form-group'><label for='id'><strong>ID</strong></label><input class='form-control' type='text'  name='id' value='" . $_SESSION['updatecustomerinfo']['id'] . "'  hidden></div>
        </div>";
    } else {
        echo "
        <div class='col-sm-4' hidden>
            <div class='form-group'><label for='id'><strong>ID</strong></label><input class='form-control' type='text'  name='id' hidden></div>
        </div>";
    }

    echo "<div class='row'>
    <div class='col-sm-6'>
        <div class='form-group form_pos'>";

    if (isset($_SESSION['updatecustomerinfo']['name'])) {
        echo "<input type='text' name='name' placeholder='Your name' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Your name\"' value='" . $_SESSION['updatecustomerinfo']['name'] . "'>";
    } else {
        echo "<input type='text' name='name' placeholder='Your name' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Your name\"'>";
    }

    echo "<span class='form_icon'></span>
        </div>
    </div>";

    echo "<div class='col-sm-6'>
        <div class='form-group form_pos'>";

    if (isset($_SESSION['updatecustomerinfo']['email'])) {
        echo "<input type='email' name='email' placeholder='Your email' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Your email\"' value='" . $_SESSION['updatecustomerinfo']['email'] . "'>";
    } else {
        echo "<input type='email' name='email' placeholder='Your email' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Your email\"'>";
    }

    echo "<span class='form_icon'></span>
        </div>
    </div>";

    if (isset($_SESSION['updatecustomerinfo']['phone'])) {
        echo "</div>
        <div class='row'>
        <div class='col-sm-4'>
            <div class='form-group form_pos'>
                <input type='text' name='phone' placeholder='Phone' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Phone\"' value='" . $_SESSION['updatecustomerinfo']['phone'] . "'>
                <span class='form_icon'></span>
            </div>
        </div>";
    } else {
        echo "</div>
        <div class='row'>
        <div class='col-sm-4'>
            <div class='form-group form_pos'>
                <input type='text' name='phone' placeholder='Phone' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Phone\"'>
                <span class='form_icon'></span>
            </div>
        </div>";
    }

    echo "<div class='col-sm-4'>
        <div class='form-group form_pos'>";

    if (isset($_SESSION['updatecustomerinfo']['date'])) {
        echo "<input type='text' name='date' placeholder='Date' class='form-control' id='reserv_date' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Date\"' value='" . $_SESSION['updatecustomerinfo']['date'] . "'>";
    } else {
        echo "<input type='text' name='date' placeholder='Date' class='form-control' id='reserv_date' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Date\"'>";
    }

    echo "<span class='form_icon'></span>
        </div>
    </div>";

    echo "<div class='col-sm-4'>
        <div class='form-group form_pos'>";

    if (isset($_SESSION['updatecustomerinfo']['time'])) {
        echo "<input type='text' name='time' placeholder='Time' class='form-control' id='reserv_time' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Time\"' value='" . $_SESSION['updatecustomerinfo']['time'] . "'>";
    } else {
        echo "<input type='text' name='time' placeholder='Time' class='form-control' id='reserv_time' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Time\"'>";
    }

    echo "<span class='form_icon'></span>
        </div>
    </div>
    </div>";

    echo "<div class='form-group'>";

    if (isset($_SESSION['updatecustomerinfo']['message'])) {
        echo "<textarea rows='3' name='message' placeholder='Message' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Message\"'>" . $_SESSION['updatecustomerinfo']['message'] . "</textarea>";
    } else {
        echo "<textarea rows='3' name='message' placeholder='Message' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Message\"'></textarea>";
    }

    echo "</div>";
}




 function viewManagerProfile()
 {
    if (isset($_SESSION['userinfo']['id'])) {
        echo "
        <div class='col-sm-4'  hidden>
            <div class='form-group'><label for='id'><strong>ID</strong></label><input class='form-control' type='text'  name='id' value='" . $_SESSION['userinfo']['id'] . "' hidden disabled></div>
            </div>";
    } else {
        echo "
        <div class='col-sm-4' hidden>
            <div class='form-group'><label for='id'><strong>ID</strong></label><input class='form-control' type='text'  name='id' hidden></div>
            </div>";
    }
    if (isset($_SESSION['userinfo']['fname'])) {
        echo "<div class='form-row'>
    <div class='col'>
        <div class='form-group'><label for='fname'><strong>First Name</strong></label><input class='form-control'  type='text' placeholder='John' name='fname' value='" . $_SESSION['userinfo']['fname'] . "' disabled></div>
    </div>";
    } else {
        echo "<div class='form-row'>
    <div class='col'>
        <div class='form-group'><label for='fname'><strong>First Name</strong></label><input class='form-control' type='text' placeholder='John' name='fname'></div>
    </div>";
    }

    if (isset($_SESSION['userinfo']['lname'])) {
        echo "<div class='col'>
    <div class='form-group'><label for='lname'><strong>Last Name</strong></label><input class='form-control' type='text' placeholder='Doe' name='lname' value='" . $_SESSION['userinfo']['lname'] . "' disabled></div>
</div>";
    } else {
        echo "<div class='col'>
    <div class='form-group'><label for='lname'><strong>Last Name</strong></label><input class='form-control' type='text' placeholder='Doe' name='lname'></div>
</div>";
    }

    if (isset($_SESSION['userinfo']['accounttype'])) {
        $accountType = $_SESSION['userinfo']['accounttype'];

        echo "<div class='col'>
    <div class='form-group'><label for='accounttype'><strong>Account Type</strong></label><select name='accounttype' disabled>Account Type
    <option value='' " . ($accountType == '' ? 'selected' : '') . ">Select Option</option>
    <option value='director' " . ($accountType == 'director' ? 'selected' : '') . ">Director</option>
    <option value='staff' " . ($accountType == 'staff' ? 'selected' : '') . ">Staff</option>
    
    </select></div>
</div>
</div>";
    } else {
        $accountType = '';
        echo "<div class='col'>
    <div class='form-group'><label for='accounttype'><strong>Account Type</strong></label><select name='accounttype'>Account Type
    <option value=''>Select Option</option>
        <option value='director'>Director</option>
        <option value='staff'>Staff</option>
    </select></div>
</div>
</div>";
    }
    if (isset($_SESSION['userinfo']['email'])) {
        echo "<div class='form-row'> 
    <div class='col'>
       <div class='form-group'><label for='email'><strong>Email Address</strong></label><input class='form-control' type='email' placeholder='user@example.com' name='email' value='" . $_SESSION['userinfo']['email'] . "' disabled></div>
    </div>";
    } else {
        echo "<div class='form-row'> 
    <div class='col'>
       <div class='form-group'><label for='email'><strong>Email Address</strong></label><input class='form-control' type='email' placeholder='user@example.com' name='email'></div>
    </div>";
    }
    if (isset($_SESSION['userinfo']['phone'])) {
        echo "<div class='col'>
    <div class='form-group'><label for='phone'><strong>Phone</strong></label><input class='form-control' type='tel' placeholder='000-000-000' name='phone' value='" . $_SESSION['userinfo']['phone'] . "' disabled></div>   
</div>";
    } else {
        echo "<div class='col'>
    <div class='form-group'><label for='phone'><strong>Phone</strong></label><input class='form-control' type='tel' placeholder='000-000-000' name='phone'></div>
    
</div>";
    }
}

//view worker profile
function viewWorkerProfile()
{
   if (isset($_SESSION['userinfo']['id'])) {
       echo "
       <div class='col-sm-4'  hidden>
           <div class='form-group'><label for='id'><strong>ID</strong></label><input class='form-control' type='text'  name='id' value='" . $_SESSION['userinfo']['id'] . "' hidden disabled></div>
           </div>";
   } else {
       echo "
       <div class='col-sm-4' hidden>
           <div class='form-group'><label for='id'><strong>ID</strong></label><input class='form-control' type='text'  name='id' hidden></div>
           </div>";
   }
   if (isset($_SESSION['userinfo']['fname'])) {
       echo "<div class='form-row'>
   <div class='col'>
       <div class='form-group'><label for='fname'><strong>First Name</strong></label><input class='form-control'  type='text' placeholder='John' name='fname' value='" . $_SESSION['userinfo']['fname'] . "' disabled></div>
   </div>";
   } else {
       echo "<div class='form-row'>
   <div class='col'>
       <div class='form-group'><label for='fname'><strong>First Name</strong></label><input class='form-control' type='text' placeholder='John' name='fname'></div>
   </div>";
   }

   if (isset($_SESSION['userinfo']['lname'])) {
       echo "<div class='col'>
   <div class='form-group'><label for='lname'><strong>Last Name</strong></label><input class='form-control' type='text' placeholder='Doe' name='lname' value='" . $_SESSION['userinfo']['lname'] . "' disabled></div>
</div>";
   } else {
       echo "<div class='col'>
   <div class='form-group'><label for='lname'><strong>Last Name</strong></label><input class='form-control' type='text' placeholder='Doe' name='lname'></div>
</div>";
   }

   if (isset($_SESSION['userinfo']['accounttype'])) {
       $accountType = $_SESSION['userinfo']['accounttype'];

       echo "<div class='col'>
   <div class='form-group'><label for='accounttype'><strong>Account Type</strong></label><select name='accounttype' disabled>Account Type
   <option value='' " . ($accountType == '' ? 'selected' : '') . ">Select Option</option>
   
   <option value='staff' " . ($accountType == 'staff' ? 'selected' : '') . ">Staff</option>
   
   </select></div>
</div>
</div>";
   } else {
       $accountType = '';
       echo "<div class='col'>
   <div class='form-group'><label for='accounttype'><strong>Account Type</strong></label><select name='accounttype'>Account Type
   <option value=''>Select Option</option>
       <option value='staff'>Staff</option>
   </select></div>
</div>
</div>";
   }
   if (isset($_SESSION['userinfo']['email'])) {
       echo "<div class='form-row'> 
   <div class='col'>
      <div class='form-group'><label for='email'><strong>Email Address</strong></label><input class='form-control' type='email' placeholder='user@example.com' name='email' value='" . $_SESSION['userinfo']['email'] . "' disabled></div>
   </div>";
   } else {
       echo "<div class='form-row'> 
   <div class='col'>
      <div class='form-group'><label for='email'><strong>Email Address</strong></label><input class='form-control' type='email' placeholder='user@example.com' name='email'></div>
   </div>";
   }
   if (isset($_SESSION['userinfo']['phone'])) {
       echo "<div class='col'>
   <div class='form-group'><label for='phone'><strong>Phone</strong></label><input class='form-control' type='tel' placeholder='000-000-000' name='phone' value='" . $_SESSION['userinfo']['phone'] . "' disabled></div>   
</div>";
   } else {
       echo "<div class='col'>
   <div class='form-group'><label for='phone'><strong>Phone</strong></label><input class='form-control' type='tel' placeholder='000-000-000' name='phone'></div>
   
</div>";
   }
}






//function staff profile
function viewFoodProfile()
{
    if (isset($_SESSION['fooddetails']['typeid'])) {
        echo "
        <div class='form-row'>
        <div class='col-sm-4' >
            <div class='form-group'><label for='typeid'><strong>MenuTypeID</strong></label><input class='form-control' type='text' id='typeid'  name='typeid' value='" . $_SESSION['fooddetails']['typeid'] . "' hidden ></div>
            </div>
            ";
    } else {
        echo "
        <div class='form-row'>
        <div class='col-sm-4' >
            <div class='form-group'><label for='typeid'><strong>MenuTypeID</strong></label><input class='form-control' type='text' id='typeid'  name='typeid' hidden ></div>
            </div>";
    }
    if (isset($_SESSION['fooddetails']['sectionid'])) {
        echo "
        <div class='col-sm-4' >
            <div class='form-group'><label for='sectionid'><strong>Menu SectionID</strong></label><input class='form-control' type='text'  name='sectionid' value='" . $_SESSION['fooddetails']['sectionid'] . "' hidden ></div>
            </div>";
    } else {
        echo "
        <div class='col-sm-4' >
            <div class='form-group'><label for='sectionid'><strong>Menu SectionID</strong></label><input class='form-control' type='text'  name='sectionid' hideen></div>
            </div> </div>";
    }
    if (isset($_SESSION['fooddetails']['id'])) {
        echo "
        <div class='col-sm-4' >
            <div class='form-group'><label for='id'><strong>Food ID</strong></label><input class='form-control' type='text'  name='id' value='" . $_SESSION['fooddetails']['id'] . "'  ></div>
            </div>";
    } else {
        echo "
        <div class='col-sm-4' >
            <div class='form-group'><label for='id'><strong>Food ID</strong></label><input class='form-control' type='text'  name='id' ></div>
            </div>";
    }


    if (isset($_SESSION['fooddetails']['menu_type'])) {
        echo "<div class='form-row'><div class='col-sm-4'>
    <div class='form-group'><label for='menu_type'><strong>Menu type</strong></label><input class='form-control' type='text' placeholder='Menu Type eg,,breakfast' name='menu_type' value='" . $_SESSION['fooddetails']['menu_type'] . "'></div>
</div>";
    } else {
        echo "<div class='form-row'><div class='col-sm-4'>
    <div class='form-group'><label for='menu_type'><strong>Menu Type</strong></label><input class='form-control' type='text' placeholder='Menu Type eg,,breakfast' name='menu_type'></div>
</div>";
    }
    if (isset($_SESSION['fooddetails']['section_name'])) {
        echo "
    <div class='col-sm-4'>
       <div class='form-group'><label for='section_name'><strong>Menu Section</strong></label><input class='form-control' type='text' placeholder='section eg,starter,dessert' name='section_name' value='" . $_SESSION['fooddetails']['section_name'] . "'></div>
    </div>";
    } else {
        echo "
    <div class='col-sm-4'>
       <div class='form-group'><label for='section_name'><strong>Menu Section</strong></label><input class='form-control' type='text' placeholder='section eg,starter,dessert' name='section_name'></div>
    </div>";
    }
    if (isset($_SESSION['fooddetails']['fooditem'])) {
        echo "<div class='form-row'>
    <div class='col-sm-4'>
        <div class='form-group'><label for='fooditem'><strong>Food Item</strong></label><input class='form-control' type='text' placeholder='Food name eg..rice' name='fooditem' value='" . $_SESSION['fooddetails']['fooditem'] . "'></div>
    </div>";
    } else {
        echo "<div class='form-row'>
    <div class='col-sm-4'>
        <div class='form-group'><label for='fooditem'><strong>Food Item</strong></label><input class='form-control' type='text' placeholder='Food name eg..rice' name='fooditem'></div>
    </div>";
    }
    if (isset($_SESSION['fooddetails']['price'])) {

        echo "
    <div class='col'>
        <div class='form-group'><label for='price'><strong>Price</strong></label><input class='form-control' id='price' type='number'placeholder='Price' pattern='\d+(\.\d{2})?' title='Please enter a valid price (e.g., 10.00)' required name='price' value='" . $_SESSION['fooddetails']['price'] . "'></div>
    ";
    } else {
        echo "
    <div class='col'>
        <div class='form-group'><label for='price'><strong>Price</strong></label><input class='form-control' id='price' type='number'placeholder='Price' pattern='\d+(\.\d{2})?' title='Please enter a valid price (e.g., 10.00)' required name='price'></div>
    ";
    }
    if (isset($_SESSION['fooddetails']['itemdescription'])) {
        echo "<div class='col'>
    <div class='form-group'><label for='itemdescription'><strong>Item Description</strong></label><input class='form-control' type='text' placeholder='description' name='itemdescription' value='" . $_SESSION['fooddetails']['itemdescription'] . "'></div>   
</div>";
    } else {
        echo "<div class='col'>
    <div class='form-group'><label for='itemdescription'><strong>Item Description</strong></label><input class='form-control' type='text' placeholder='description' name='itemdescription'></div>
    
</div>";
    }
}
function removeTrailingZeroes($number)
{
    // Convert the number to string to handle trailing zeroes
    $numberStr = rtrim($number, '0');

    // If the last character is a decimal point, remove it as well
    if (substr($numberStr, -1) === '.') {
        $numberStr = rtrim($numberStr, '.');
    }

    return $numberStr;
}
// order customers form
function viewOrderCustomerForm()
{
    if (isset($_SESSION['updateordercustomerinfo']['customerid'])) {
        echo "
        <div class='col-sm-4' hidden>
            <div class='form-group'><label for='id'><strong>ID</strong></label><input class='form-control' type='text'  name='customerid' value='" . $_SESSION['updateordercustomerinfo']['customerid'] . "'  hidden></div>
        </div>";
    } else {
        echo "
        <div class='col-sm-4' hidden>
            <div class='form-group'><label for='id'><strong>ID</strong></label><input class='form-control' type='text'  name='customerid' hidden></div>
        </div>";
    }

    echo "<div class='row'>
    <div class='col-sm-6'>
        <div class='form-group form_pos'>";

    if (isset($_SESSION['updateordercustomerinfo']['firstname'])) {
        echo "<input type='text' name='firstname' placeholder='First name' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Your Firstname\"' value='" . $_SESSION['updateordercustomerinfo']['firstname'] . "'>";
    } else {
        echo "<input type='text' name='firstname' placeholder='First name' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Your Firstname\"'>";
    }

    echo "<span class='form_icon'></span>
        </div>
    </div>";

    echo "<div class='col-sm-6'>
        <div class='form-group form_pos'>";
    if (isset($_SESSION['updateordercustomerinfo']['lastname'])) {
        echo "<input type='text' name='lastname' placeholder='Last name' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Your Lastname\"' value='" . $_SESSION['updateordercustomerinfo']['lastname'] . "'>";
    } else {
        echo "<input type='text' name='lastname' placeholder='Last name' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Your Lastname\"'>";
    }

    echo "<span class='form_icon'></span>
            </div>
        </div>";

    echo "<div class='col-sm-6'>
            <div class='form-group form_pos'>";

    if (isset($_SESSION['updateordercustomerinfo']['email'])) {
        echo "<input type='email' name='email' placeholder='Your email' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Your email\"' value='" . $_SESSION['updateordercustomerinfo']['email'] . "'>";
    } else {
        echo "<input type='email' name='email' placeholder='Your email' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Your email\"'>";
    }

    echo "<span class='form_icon'></span>
        </div>
    </div>";

    if (isset($_SESSION['updateordercustomerinfo']['phone'])) {
        echo "</div>
        <div class='row'>
        <div class='col-sm-4'>
            <div class='form-group form_pos'>
                <input type='text' name='phone' placeholder='Phone' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Phone\"' value='" . $_SESSION['updateordercustomerinfo']['phone'] . "'>
                <span class='form_icon'></span>
            </div>
        </div>";
    } else {
        echo "</div>
        <div class='row'>
        <div class='col-sm-4'>
            <div class='form-group form_pos'>
                <input type='text' name='phone' placeholder='Phone' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Phone\"'>
                <span class='form_icon'></span>
            </div>
        </div>";
    }
}


function viewMenuTypeInfo()
{
    if (isset($_SESSION['updatemenuinfo']['id'])) {
        echo "
        <div class='col-sm-4'  hidden>
            <div class='form-group'><label for='id'><strong>ID</strong></label><input class='form-control' type='text'  name='id' value='" . $_SESSION['updatemenuinfo']['id'] . "' ></div>
        </div>";
    } else {
        echo "
        <div class='col-sm-4' hidden>
            <div class='form-group'><label for='id'><strong>ID</strong></label><input class='form-control' type='text'  name='id' hidden></div>
        </div>";
    }

    echo "<div class='row'>
    <div class='col-sm-6'>
        <div class='form-group form_pos'>";

    if (isset($_SESSION['updatemenuinfo']['type'])) {
        echo "<label for='menutype'><strong>Menu Type</strong></label><input type='text' name='menutype' placeholder='Menu Type' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Menu Type\"' value='" . $_SESSION['updatemenuinfo']['type'] . "'>";
    } else {
        echo "<label for='menutype'><strong>Menu Type</strong></label><input type='text' name='menutype' placeholder='Menu Type' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Menu Type\"'>";
    }

    echo "<span class='form_icon'></span>
        </div>
    </div>";

    echo "<div class='col-sm-6'>
        <div class='form-group form_pos'>";
    if (isset($_SESSION['updatemenuinfo']['description'])) {
        echo "<label for='menudescription'><strong>Menu Description</strong></label><input type='text' name='menudescription' placeholder='Description' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Description\"' value='" . $_SESSION['updatemenuinfo']['description'] . "'>";
    } else {
        echo "<label for='menudescription'><strong>Menu Description</strong></label><input type='text' name='menudescription' placeholder='Description' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Description\"'>";
    }
    echo "<span class='form_icon'></span>
    </div>
</div>";
}

//menusection
function viewMenuSectionInfo()
{
    if (isset($_SESSION['menusectioninfo']['id'])) {
        echo "
        <div class='col-sm-4' hidden>
            <div class='form-group'><label for='id'><strong>ID</strong></label><input class='form-control' type='text'  name='id' value='" . $_SESSION['menusectioninfo']['id'] . "' ></div>
        </div>";
    } else {
        echo "
        <div class='col-sm-4' hidden>
            <div class='form-group'><label for='id'><strong>ID</strong></label><input class='form-control' type='text'  name='id' hidden></div>
        </div>";
    }

    echo "<div class='row'>
    <div class='col-sm-6'>
        <div class='form-group form_pos'>";

    if (isset($_SESSION['menusectioninfo']['menu_id'])) {
        echo "<label for='menutype'><strong>Menu ID</strong></label><input type='text' name='menuid' placeholder='Menu ID' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Menu ID\"' value='" . $_SESSION['menusectioninfo']['menu_id'] . "'>";
    } else {
        echo "<label for='menutype'><strong>Menu ID</strong></label><input type='text' name='menuid' placeholder='Menu ID' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Menu ID\"'>";
    }

    echo "<span class='form_icon'></span>
        </div>
    </div>";

    echo "<div class='col-sm-6'>
        <div class='form-group form_pos'>";
    if (isset($_SESSION['menusectioninfo']['section'])) {
        echo "<label for='section'><strong>Menu Section</strong></label><input type='text' name='section' placeholder='Description' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Section\"' value='" . $_SESSION['menusectioninfo']['section'] . "'>";
    } else {
        echo "<label for='section'><strong>Menu Section</strong></label><input type='text' name='section' placeholder='Description' class='form-control' onfocus='this.placeholder = \"\"' onblur='this.placeholder = \"Section\"'>";
    }
    echo "<span class='form_icon'></span>
    </div>
</div>";
}



/*function Orderprofile() {
    echo "<form id='orderForm'>";

    if (isset($_SESSION['orderinfo']['orderid'])) {
        echo "
        <div class='form-group'>
            <label for='orderid'>Order ID</label>
            <input type='text' class='form-control' id='orderid' name='orderid' value='" . $_SESSION['orderinfo']['orderid'] . "' required readonly>
        </div>";
    }

    if (isset($_SESSION['orderinfo']['customerid'])) {
        echo "
        <div class='form-group'>
            <label for='customerid'>Customer ID</label>
            <input type='text' class='form-control' id='customerid' name='customerid' value='" . $_SESSION['orderinfo']['customerid'] . "' required readonly>
        </div>";
    }

    if (isset($_SESSION['orderinfo']['orderdate'])) {
        echo "
        <div class='form-group'>
            <label for='orderdate'>Order Date</label>
            <input type='text' class='form-control' id='orderdate' name='orderdate' value='" . $_SESSION['orderinfo']['orderdate'] . "' required readonly>
        </div>";
    }

    if (isset($_SESSION['orderinfo']['orderstatus'])) {
        echo "
        <div class='form-group'>
            <label for='orderstatus'>Order Status</label>
            <input type='text' class='form-control' id='orderstatus' name='orderstatus' value='" . $_SESSION['orderinfo']['orderstatus'] . "' required readonly>
        </div>";
    }

    if (isset($_SESSION['orderinfo']['amountowed'])) {
        echo "
        <div class='form-group'>
            <label for='amountowed'>Amount Owed (includes 10% tax)</label>
            <input type='number' step='0.01' class='form-control' id='amountowed' name='amountowed' value='" . $_SESSION['orderinfo']['amountowed'] . "' required readonly>
        </div>";
    }

    echo "<h2>Order Items</h2>
    <div id='orderItems'>";

    if (isset($_SESSION['orderinfo']['items']) && is_array($_SESSION['orderinfo']['items'])) {
        foreach ($_SESSION['orderinfo']['items'] as $index => $item) {
            echo createItemRow($index, $item);
        }
    } else {
        echo createItemRow(0);
    }

    if (isset($_SESSION['orderinfo']['totalcost'])) {
        echo "<input type='hidden' id='existingtotalcost' value='" . $_SESSION['orderinfo']['totalcost'] . "'>";
    } else {
        echo "<input type='hidden' id='existingtotalcost' value=''>";
    }

    echo "</div>
    <div class='form-group text-center'>
        <button type='button' id='addItemBtn' class='btn btn-secondary'>Add Item</button>
    </div>";

    echo "<h2>Summary</h2>
    <div class='form-group'>
        <label for='totalcost'>Total Cost</label>
        <input type='number' step='0.01' class='form-control' id='totalcost' name='totalcost' value='" . $_SESSION['orderinfo']['totalcost'] . "' required readonly>
    </div>
    <div class='form-group'>
        <label for='amountowed'>Amount Owed</label>
        <input type='number' step='0.01' class='form-control' id='amountowed' name='amountowed' value='" . $_SESSION['orderinfo']['amountowed'] . "' required readonly>
    </div>
    <div class='form-group'>
        <label for='paymentstatus'>Payment Status</label>
        <input type='text' class='form-control' id='paymentstatus' name='paymentstatus' value='" . $_SESSION['orderinfo']['paymentstatus'] . "' required readonly>
    </div>
    <div class='form-group text-center'>
        <button type='button' id='updateOrderBtn' class='btn btn-primary'>Update Order</button>
    </div>
    </form>";
}  */



function Orderprofile()
{
    echo "<form id='orderForm'>";

    if (isset($_SESSION['orderinfo']['orderid'])) {
        echo "
        <div class='form-group'>
            <label for='orderid'>Order ID</label>
            <input type='text' class='form-control' id='orderid' name='orderid' value='" . $_SESSION['orderinfo']['orderid'] . "' required readonly>
        </div>";
    }

    if (isset($_SESSION['orderinfo']['customerid'])) {
        echo "
        <div class='form-group'>
            <label for='customerid'>Customer ID</label>
            <input type='text' class='form-control' id='customerid' name='customerid' value='" . $_SESSION['orderinfo']['customerid'] . "' required readonly>
        </div>";
    }

    if (isset($_SESSION['orderinfo']['orderdate'])) {
        echo "
        <div class='form-group'>
            <label for='orderdate'>Order Date</label>
            <input type='text' class='form-control' id='orderdate' name='orderdate' value='" . $_SESSION['orderinfo']['orderdate'] . "' required readonly>
        </div>";
    }

    if (isset($_SESSION['orderinfo']['orderstatus'])) {
        echo "
        <div class='form-group'>
            <label for='orderstatus'>Order Status</label>
            <input type='text' class='form-control' id='orderstatus' name='orderstatus' value='" . $_SESSION['orderinfo']['orderstatus'] . "' required readonly>
        </div>";
    }

    if (isset($_SESSION['orderinfo']['amountowed'])) {
        echo "
        <div class='form-group'>
            <label for='amountowed'>New Amount Owed (includes 10% tax)</label>
            <input type='number' step='0.01' class='form-control' id='amountowed' name='amountowed' value='" . $_SESSION['orderinfo']['amountowed'] . "' required readonly>
        </div>";
    }

    echo "<h2>Order Items</h2>
    <div id='orderItems'>";

    // Display existing items if available
    if (isset($_SESSION['orderinfo']['items']) && is_array($_SESSION['orderinfo']['items'])) {
        foreach ($_SESSION['orderinfo']['items'] as $index => $item) {
            $name = htmlspecialchars($item['name']);
            $quantity = htmlspecialchars($item['quantity']);
            $price = htmlspecialchars($item['price']);

            echo "<div class='form-row item-row'>
                <div class='form-group col-md-6'>
                    <label for='item{$index}'>Item</label>
                    <input type='text' class='form-control item-input' id='item{$index}' name='items[{$index}][item]' value='{$name}' required readonly>
                </div>
                <div class='form-group col-md-2'>
                    <label for='quantity{$index}'>Quantity</label>
                    <input type='number' class='form-control quantity-input' id='quantity{$index}' name='items[{$index}][quantity]' value='{$quantity}' min='1' required readonly>
                </div>
                <div class='form-group col-md-2'>
                    <label for='price{$index}'>Price</label>
                    <input type='number' step='0.01' class='form-control price-input' id='price{$index}' name='items[{$index}][price]' value='{$price}' readonly>
                </div>
                <div class='form-group col-md-2 align-self-end'>
                    <button type='button' class='btn btn-danger remove-existing-item'>Remove</button>
                </div>
            </div>";
        }
    }


    if (isset($_SESSION['orderinfo']['totalcost'])) {
        echo "<input type='text' id='existingtotalcost' value='" . $_SESSION['orderinfo']['totalcost'] . "' hidden>";
    } else {
        echo "<input type='text' id='existingtotalcost' value='' hidden>";
    }

    echo "</div>
    <div class='form-group text-center'>
        <button type='button' id='addItemBtn' class='btn btn-secondary'>Add Item</button>
    </div>";

    echo "<h2>Summary</h2>
    <div class='form-group'>
        <label for='totalcost'>Total Cost</label>
        <input type='number' step='0.01' class='form-control' id='totalcost' name='totalcost' value='" . $_SESSION['orderinfo']['totalcost'] . "' required readonly>
    </div>
    <div class='form-group'>
        <label for='amountowed'>Old Amount Owed</label>
        <input type='number' step='0.01' class='form-control' id='amountowed' name='amountowed' value='" . $_SESSION['orderinfo']['amountowed'] . "' required readonly>
    </div>
    <div class='form-group'>
        <label for='paymentstatus'>Payment Status</label>
        <input type='text' class='form-control' id='paymentstatus' name='paymentstatus' value='" . $_SESSION['orderinfo']['paymentstatus'] . "' required readonly>
    </div>
    <div class='form-group text-center'>
        <button type='button' id='updateOrderBtn' class='btn btn-primary'>Update Order</button>
    </div>
    </form>";
} 








//error handling
/*function user_error_handler($severity, $msg, $filename, $linenum) {
    $dbh = new DB_Mysql_Prod;
    $query = “INSERT INTO errorlog
    (severity, message, filename, linenum, time)
    VALUES(?,?,?,?, NOW())”;
    $sth = $dbh->prepare($query); */
 /*   switch($severity) {
    case E_USER_NOTICE:
    $sth->execute(‘NOTICE’, $msg, $filename, $linenum);
    break;
    case E_USER_WARNING:
    $sth->execute(‘WARNING’, $msg, $filename, $linenum);
    break;
    case E_USER_ERROR:
    $sth->execute(‘FATAL’, $msg, $filename, $linenum); 
    print "FATAL error $msg at $filename:$linenum<br>";
    break;
    default:
    print "Unknown error at $filename:$linenum<br>";
    break;
    }
    } */
