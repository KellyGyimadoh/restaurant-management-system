<?php
$title = "Menu Settings";
include("../includes/sessions.php");
include("../includes/head.php");


if (!isloggedin() && !isset($_SESSION['accounttype']) && $_SESSION['accounttype'] !== "director") {

    header("Location:../auth/index.php");
    die();
}

?>
<style>
    .alert,
    select {
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-all;
        /* Optional: This can break words anywhere */
    }
</style>

<body id="page-top">
    <div id="wrapper">
        <?php
        if (($_SESSION['accounttype'] == "director")) {
            include("../includes/auth.sidebar.php");
            include("../includes/auth.header.php");
        } elseif (($_SESSION['accounttype'] == "staff")) {
            include("../includes/sidebar.php");
            include("../includes/header.php");
        } else {
            die();
        }

        ?>

        <div class="container-fluid">

            <h3 class="text-dark mb-4">Menu Settings</h3>
            <div class="row justify-content-center min-vh-100 align-items-center">
                <div class="col-md-8 col-lg-7 col-xl-8">
                    <div class="card shadow mb-3">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <p class="text-primary m-0 font-weight-bold">Add Menu Type and Description And Item</p>
                            </div>
                            <div class="card-body ">
                                <form id="menuForm" method="POST" enctype="multipart/form-data">
                                    <!-- Add Menu Type -->
                                    <!-- Add Menu Type -->
                                    <div class='form-row'>
                                        <div class="col">
                                            <div class='form-group'>
                                                <label for='menutype'><strong>Menu Type</strong></label>
                                                <select name='menutype' id="menutypeselect">
                                                    <option value=''>Select Menu Type</option>
                                                    <!--    <option value='breakfast'>Breakfast</option>
                                                <option value='lunch'>Lunch</option>
                                                <option value='supper'>Supper</option> -->
                                                </select>
                                                <input type="" id="menuIdInput" name="menu_id" value="">
                                                <button type="button" id="addMenuTypeButton" class="btn btn-sm btn-primary">Add Menu Type</button>
                                                <input type="text" id="newMenuTypeInput" class="form-control mt-2" placeholder="New Menu Type" style="display:none;">

                                                <button type="button" id="saveNewMenuTypeButton" class="btn btn-sm btn-success mt-2" style="display:none;">Save</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add Menu Section -->
                                    <div class='form-row'>
                                        <div class="col">
                                            <div class='form-group'>
                                                <label for='section_name'><strong>Menu Section</strong></label>
                                                <select name='section_name' id="themenusection">
                                                    <!--  <option value=''>Select Menu Section</option>
                                                <option value='starters'>Starters</option>
                                                <option value='main'>Main</option>
                                                <option value='dessert'>Dessert</option>
                                                <option value='appetizer'>Appetizers</option> -->
                                                </select>
                                                <button type="button" id="addMenuSectionButton" class="btn btn-sm btn-primary">Add Menu Section</button>
                                                <input type="text" id="newMenuSectionInput" class="form-control mt-2" placeholder="New Menu Section" style="display:none;">
                                                <input type="text" id="menuiddname" class="form-control mt-2" placeholder="Action" style="display:none;" readonly>
                                                <button type="button" id="saveNewMenuSectionButton" class="btn btn-sm btn-success mt-2" style="display:none;">Save</button>
                                            </div>
                                        </div>
                                    </div>


                                    <div class='form-row'>
                                        <div class="col">
                                            <div class='form-group'>
                                                <label for='food_item'><strong>Food Item</strong></label>
                                                <input class='form-control' type='text' placeholder='Food Item' name='food_item'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class='form-row'>
                                        <div class="col">
                                            <div class='form-group'>
                                                <label for='fooditemselect'><strong>Food Item</strong></label>&nbsp;
                                                <!-- Use a select input for menu sections -->
                                                <select name='fooditemselect'>
                                                    <option value=''>Select Menu Section</option>
                                                    <option value='cake'>Cake</option>
                                                    <option value='fruit'>Fufu</option>
                                                    <option value='tea'>Tea</option>
                                                    <option value='bread'>Bread</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='form-row'>
                                        <div class="col">
                                            <div class='form-group'>
                                                <label for='price'><strong>Price</strong></label>
                                                GHS<input class="form-control" type="text" id="priceInput" name="price" placeholder="Price" pattern="\d+(\.\d{2})?" title="Please enter a valid price (e.g., 10.00)" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='form-row'>
                                        <div class="col">
                                            <div class='form-group'>
                                                <label for='itemdescription'><strong>Item Description</strong></label>
                                                <input class='form-control' type='text' placeholder='Item Description' name='itemdescription'>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='form-row'>
                                        <div class="col">
                                            <div class='form-group'>
                                                <label for='image'><strong>Image</strong></label>
                                                <input type='file' class='form-control-file' name='image'>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="mb-3 form-group">
                                            <button class="btn btn-primary btn-sm" type="submit">Submit</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
                        
              

            <div class="form-row">
                <div class="container">
                    <div class="alert alert-success successalert " hidden>
                        <strong id="messagebox"></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include("../includes/footer.php");
    include("../includes/script.php");

    ?>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            retrieveMenu();
            retrieveSection();
            const menuForm = document.getElementById("menuForm");
            const msgbox = document.querySelector("#messagebox");
            const alertbox = document.querySelector(".alert");
            const menuidname = document.getElementById("menuiddname");
            const menuArr = [];
            const menusectionSelect = document.getElementById("themenusection");
            async function handleFormSubmission(event) {
                event.preventDefault();
                const formData = new FormData(menuForm);
                const formobj = Object.fromEntries(formData.entries());

                try {
                    const response = await fetch("../api/menus/processallmenus.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(formobj)
                    });

                    if (!response.ok) {
                        throw new Error("Could not process form");
                    }

                    const data = await response.json();
                    alertbox.hidden = false;
                    if (data.success) {
                        alertbox.classList.remove("alert-danger");
                        alertbox.classList.add("alert-success");
                        msgbox.innerHTML = data.messages;
                        setTimeout(()=>{
                            alertbox.hidden="true";
                            menuForm.reset();
                        },3000)
                        
                    } else {
                        alertbox.classList.remove("alert-success");
                        alertbox.classList.add("alert-danger");
                        msgbox.innerHTML = Array.isArray(data.message) ? data.messages.join("<br>") : data.messages;
                    }
                } catch (error) {
                    console.error(error);
                    alertbox.hidden = false;
                    alertbox.classList.remove("alert-success");
                    alertbox.classList.add("alert-danger");
                    msgbox.innerHTML = error.messages;
                }
            }

            menuForm.addEventListener("submit", handleFormSubmission);

            const priceInput = document.getElementById('priceInput');
            priceInput.addEventListener('input', function() {
                let price = this.value.replace(/[^\d.]/g, '');
                price = price.replace(/(\..*)\./g, '$1');
                const decimalIndex = price.indexOf('.');
                if (decimalIndex !== -1) {
                    price = price.substr(0, decimalIndex + 3);
                }
                this.value = price;
            });

            // Set menu ID when menu type is selected
            const menuTypeSelect = document.getElementById("menutypeselect");
            const menuIdInput = document.getElementById("menuIdInput");

            /* menuTypeSelect.addEventListener("change", function() {
                 const selectedOption = menuTypeSelect.options[menuTypeSelect.selectedIndex];
                 menuIdInput.value = selectedOption.value;
             });  */
            menuTypeSelect.addEventListener("change", retrieveId);
            //menuTypeSelect.addEventListener("click", retrieveMenu);
            // retrieve id
            async function retrieveId() {
                const type = menuTypeSelect.value.toLowerCase();
                // const description = menudescription.value;

                try {
                    const response = await fetch(`../api/menus/process.retrieveid.php`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            menutype: type,
                            // menudescription: description
                        })
                    });
                    if (!response.ok) {
                        throw new Error("Could not send item");
                    }
                    const data = await response.json();

                    menuIdInput.value = data.id;
                    menuidname.value = `adding to ${data.type}`;
                } catch (error) {
                    console.error(error);
                    alertbox.hidden = false;
                    alertbox.classList.remove("alert-success");
                    alertbox.classList.add("alert-danger");
                    msgbox.innerHTML = error.message;
                }
            }

            //retrieve menu
            async function retrieveMenu() {
                try {
                    // Await the fetch call to ensure it completes before proceeding
                    const response = await fetch("../api/menus/process.selectmenu.php");
                    if (!response.ok) {
                        throw new Error("Could not retrieve item");
                    }

                    // Await the response.json() call to parse the JSON response
                    const data = await response.json();

                    if (data.success) {
                        const menutypeSelect = document.getElementById("menutypeselect");

                        // Clear existing options (if any) before adding new ones
                        menutypeSelect.innerHTML = '<option value="">Select Menu Type</option>';

                        // Loop through the menu types and create an option for each
                        data.types.forEach(type => {
                            createOption(type);
                        });

                        // Show success message
                        alertbox.hidden = false;
                        alertbox.classList.remove("alert-danger");
                        alertbox.classList.add("alert-success");
                        msgbox.innerHTML = data.message;
                    } else {
                        // Show error message if retrieval was not successful
                        alertbox.hidden = false;
                        alertbox.classList.remove("alert-success");
                        alertbox.classList.add("alert-danger");
                        msgbox.innerHTML = Array.isArray(data.message) ? data.message.join("<br>") : data.message;
                    }
                } catch (error) {
                    // Catch and display any errors that occur during the fetch process
                    const alertbox = document.querySelector(".alert");
                    const msgbox = document.querySelector("#messagebox");
                    alertbox.hidden = false;
                    alertbox.classList.remove("alert-success");
                    alertbox.classList.add("alert-danger");
                    msgbox.innerHTML = error.message;
                }
            }

            // Function to create a new option element and add it to the select menu
            function createOption(type) {
                const menutypeSelect = document.getElementById("menutypeselect");
                const newOption = document.createElement("option");
                newOption.value = type.type;
                newOption.text = type.type.toUpperCase()
                menutypeSelect.add(newOption);
            }

            //retrieve sections 
            //  menusectionSelect.addEventListener("click",retrieveSection);
            async function retrieveSection() {
                try {
                    // Await the fetch call to ensure it completes before proceeding
                    const response = await fetch("../api/menus/process.selectsection.php");
                    if (!response.ok) {
                        throw new Error("Could not retrieve item");
                    }

                    // Await the response.json() call to parse the JSON response
                    const data = await response.json();

                    if (data.success) {

                        // Clear existing options (if any) before adding new ones
                        menusectionSelect.innerHTML = '<option value="">Select Section Type</option>';

                        // Loop through the menu types and create an option for each
                        data.menusection.forEach(menusection => {
                            createSectionOption(menusection);
                        });

                        // Show success message
                        alertbox.hidden = false;
                        alertbox.classList.remove("alert-danger");
                        alertbox.classList.add("alert-success");
                        msgbox.innerHTML = data.message;
                    } else {
                        // Show error message if retrieval was not successful
                        alertbox.hidden = false;
                        alertbox.classList.remove("alert-success");
                        alertbox.classList.add("alert-danger");
                        msgbox.innerHTML = Array.isArray(data.message) ? data.message.join("<br>") : data.message;
                    }
                } catch (error) {
                    // Catch and display any errors that occur during the fetch process

                    alertbox.hidden = false;
                    alertbox.classList.remove("alert-success");
                    alertbox.classList.add("alert-danger");
                    msgbox.innerHTML = error.message;
                }
            }

            // Function to create a new option element and add it to the select menu
            function createSectionOption(menusection) {
                const newOption = document.createElement("option");
                newOption.value = menusection.section
                newOption.text = menusection.section.toUpperCase()
                menusectionSelect.add(newOption);
            }

            //menudescription.addEventListener("input", retrieveId);
            // Add new menu type
            const addMenuTypeButton = document.getElementById("addMenuTypeButton");
            const newMenuTypeInput = document.getElementById("newMenuTypeInput");
            const saveNewMenuTypeButton = document.getElementById("saveNewMenuTypeButton");
            const exitmenubtn = document.getElementById("exitmenutype");

            addMenuTypeButton.addEventListener("click", function() {

                if (newMenuTypeInput.style.display == "block" && saveNewMenuTypeButton.style.display == "block") {
                    newMenuTypeInput.style.display = "none";
                    saveNewMenuTypeButton.style.display = "none";
                    addMenuTypeButton.textContent = "Add Menu Type";
                    addMenuTypeButton.classList = "btn-primary btn btn-sm";
                } else {
                    newMenuTypeInput.style.display = "block";
                    saveNewMenuTypeButton.style.display = "block";
                    addMenuTypeButton.textContent = "Exit";
                    addMenuTypeButton.classList = "btn-danger btn btn-sm";
                }
            })



            saveNewMenuTypeButton.addEventListener("click", async function() {
                const newMenuType = newMenuTypeInput.value.trim();
                if (newMenuType !== "") {
                    try {
                        const response = await fetch("../api/menus/process.insertmainmenu.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                menutype: newMenuType
                            })
                        });
                        const data = await response.json();
                        alertbox.hidden = false;
                        if (data.success) {

                            const menutypeSelect = document.getElementById("menutypeselect");
                            const newOption = document.createElement("option");
                            newOption.value = newMenuType; // Assume your API returns the new menu ID
                            newOption.text = newMenuType;
                            menutypeSelect.add(newOption);
                            menutypeSelect.value = newMenuType;
                            alertbox.classList.remove("alert-danger");
                            alertbox.classList.add("alert-success");
                            msgbox.innerHTML = data.message;
                            newMenuTypeInput.style.display = "none";
                            saveNewMenuTypeButton.style.display = "none";
                            newMenuTypeInput.value = ""; 
                            addMenuTypeButton.textContent = "Add Menu Type";
                            addMenuTypeButton.classList.remove("btn-danger");
                            addMenuTypeButton.classList.add("btn-primary");
                            //addMenuTypeButton.remove()

                        } else {
                            alertbox.classList.remove("alert-success");
                            alertbox.classList.add("alert-danger");
                            msgbox.innerHTML = Array.isArray(data.message) ? data.message.join("<br>") : data.message;
                            alert(data.message);
                        }
                    } catch (error) {
                        console.error("Error adding new menu type:", error);

                        alertbox.hidden = false;
                        alertbox.classList.remove("alert-success");
                        alertbox.classList.add("alert-danger");
                        msgbox.innerHTML = error.messages;
                    }
                }
                //retrieve after adding new
                retrieveMenu();
            });

            // Add new menu section
            const addMenuSectionButton = document.getElementById("addMenuSectionButton");
            const newMenuSectionInput = document.getElementById("newMenuSectionInput");
            const saveNewMenuSectionButton = document.getElementById("saveNewMenuSectionButton");

            addMenuSectionButton.addEventListener("click", function() {
                if (newMenuSectionInput.style.display == "block" && saveNewMenuSectionButton.style.display == "block") {
                    newMenuSectionInput.style.display = "none";
                    saveNewMenuSectionButton.style.display = "none";
                    menuidname.style.display = "none";
                    addMenuSectionButton.textContent = "Add Menu Section";
                    addMenuSectionButton.classList = "btn-primary btn btn-sm"
                } else {
                    newMenuSectionInput.style.display = "block";
                    saveNewMenuSectionButton.style.display = "block";
                    menuidname.style.display = "block";
                    addMenuSectionButton.textContent = "Exit";
                    addMenuSectionButton.classList = "btn-danger btn btn-sm";
                }
            });

            saveNewMenuSectionButton.addEventListener("click", async function() {
                const newMenuSection = newMenuSectionInput.value.trim();
                const menuId = menuIdInput.value;
                if (newMenuSection !== "" || menuId !== "") {
                    try {
                        const response = await fetch("../api/menus/insertmenusection.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                menusection: newMenuSection,
                                menuid: menuId
                            })
                        });
                        const data = await response.json();
                        alertbox.hidden = false;
                        if (data.success) {
                            const newOption = document.createElement("option");
                            newOption.value = newMenuSection;
                            newOption.text = newMenuSection;
                            menusectionSelect.add(newOption);
                            menusectionSelect.value = newMenuSection;
                            alertbox.classList.remove("alert-danger");
                            alertbox.classList.add("alert-success");
                            msgbox.innerHTML = data.message;
                            newMenuSectionInput.style.display = "none";
                            saveNewMenuSectionButton.style.display = "none";
                            newMenuSectionInput.value = "";
                        } else {
                            alertbox.classList.remove("alert-success");
                            alertbox.classList.add("alert-danger");
                            msgbox.innerHTML = Array.isArray(data.message) ? data.message.join("<br>") : data.message;
                            alert(data.message);
                        }
                    } catch (error) {
                        console.error("Error adding new menu section:", error);
                        alertbox.hidden = false;
                        alertbox.classList.remove("alert-success");
                        alertbox.classList.add("alert-danger");
                        msgbox.innerHTML = error.message;

                    }
                }
            });


            function showAlert(type, message) {
                let alertBox, messageBox, soundFile;
                if (type === 'success') {
                    alertBox = document.getElementById('success-alert');
                    messageBox = document.getElementById('success-message');
                    soundFile = '../sounds/mixkit-bell-notification-933.wav'; // Add path to your success sound file
                } else if (type === 'danger') {
                    alertBox = document.getElementById('danger-alert');
                    messageBox = document.getElementById('danger-message');
                    soundFile = '../sounds/mixkit-cartoon-failure-piano-473.wav'; // Add path to your failure sound file
                }

                messageBox.textContent = message;
                alertBox.style.display = 'block';

                // Play sound
                let audio = new Audio(soundFile);
                audio.play();

                // Hide after 3 seconds
                setTimeout(() => {
                    alertBox.style.display = 'none';
                }, 3000);
            }



        });
    </script>

</body>

</html>