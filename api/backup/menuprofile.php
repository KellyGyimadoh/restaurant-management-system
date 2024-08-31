<?php
$title = "Manager Profile";
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
            <div class="row ">
                <div class="col-md-4 mb-4">
                    <div class="card shadow mb-3">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <p class="text-primary m-0 font-weight-bold">Add Menu Type and Description</p>
                            </div>
                            <div class="card-body">
                                <form action="" id="menutypeform" method="POST">
                                    <!--menu type and description-->
                                    <div class='form-row'>
                                        <div class='form-group'><label for='menutype'><strong>Menu Type</strong></label>&nbsp;<select name='menutype' id="menutypeselect">Menu Type
                                                <option value=''>Select Menu Type</option>
                                                <option value='breakfast'>Breakfast</option>
                                                <option value='lunch'>Lunch</option>
                                                <option value='supper'>Supper</option>
                                            </select></div>
                                    </div>
                                    <!--menu type and description-->
                                    <div class='form-row'>
                                        <div class='form-group'><label for='section_name'><strong>Menu Section</strong></label>&nbsp;<select name='section_name' id="themenusection">Menu Section
                                                <option value=''>Select Menu Section</option>
                                                <option value='starters'>Starters</option>
                                                <option value='main'>Main</option>
                                                <option value='dessert'>Dessert</option>
                                                <option value='appetizer'>Appetizers</option>

                                            </select></div>
                                    </div>
                                    <div class='form-row'>
                                        <div class='col'>
                                            <div class='form-group'><label for='menuid'><strong>Menu ID</strong></label><input class='form-control' type='text' placeholder='Menu ID' name='menuid' value='' id="menuidd" readonly></div>
                                        </div>
                                        <div class='col'>
                                            <div class='form-group'><label for='menuidname'><strong>Menu ID Name</strong></label><input class='form-control' type='text' placeholder='Menu ID Name' name='menuidname' value='' id="menuiddname" readonly></div>
                                        </div>
                                    </div>
                                    <div class='form-row'>
                                        <div class='col'>
                                            <div class='form-group'><label for='menudescription'><strong>Menu Description</strong></label><input class='form-control' type='text' placeholder='Menu Description' name='menudescription' value=''></div>
                                        </div>
                                    </div>
                                    <div class="form-row">

                                        <div class="mb-3 form-group"><button class="btn btn-primary btn-sm" type="submit" name="addmenu">Add Menu Type</button></div>

                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>


                <!--add fooitemesction -->

                <div class="col-md-8 mb-3">
                    <div class="card shadow mb-3">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <p class="text-primary m-0 font-weight-bold">Add Food Item</p>
                            </div>
                            <div class="card-body">
                                <form action="" id="menufooditem" method="POST" enctype="multipart/form-data">

                                    <div class='form-row'>
                                        <div class='form-group'><label for='food_item'><strong>Food Item</strong></label>&nbsp;
                                            <!-- Use a text input for custom food items -->
                                            <input class='form-control' type='text' placeholder='Food Item' name='food_item' value=''>
                                        </div>
                                    </div>

                                    <div class='form-row'>
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

                                    <div class='form-row'>
                                        <div class='col'>
                                            <div class='form-group'><label for='price'><strong>Price</strong></label>
                                                <!-- Use a text input for price -->
                                                GHS<input class="form-control" type="text" id="priceInput" name="price" placeholder="Price" pattern="\d+(\.\d{2})?" title="Please enter a valid price (e.g., 10.00)" required>

                                            </div>
                                        </div>
                                        <div class='col'>
                                            <div class='form-group'><label for='itemdescription'><strong> Item Description</strong></label>
                                                <!-- Use a text input for description -->
                                                <input class='form-control' type='text' placeholder=' Item Description' name='itemdescription' value=''>
                                            </div>
                                        </div>
                                    </div>

                                    <div class='form-row'>
                                        <div class='col'>
                                            <div class='form-group'><label for='menusection_id'><strong>Menu Section ID</strong></label>
                                                <!-- Use a text input for menu section ID -->
                                                <input class='form-control' type='text' placeholder='Menu Section ID' name='menusection_id' value='' id="menusection_id" readonly>
                                            </div>
                                        </div>
                                        <div class='col'>
                                            <div class='form-group'><label for='menusection_idname'><strong>Menu Section ID Name</strong></label>
                                                <!-- Use a text input for menu section ID -->
                                                <input class='form-control' type='text' placeholder='Menu Section ID Name' name='menusection_idname' value='' id="menusection_idname" readonly>
                                            </div>
                                        </div>
                                        <div class='col'>
                                            <div class='form-group'><label for='image'><strong>Image</strong></label>
                                                <!-- Use a file input for image -->
                                                <input type='file' class='form-control-file' name='image'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="mb-3 form-group">
                                            <button class="btn btn-primary btn-sm" type="submit" name="addfooditem">Add Food Item</button>
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

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuform = document.getElementById("menutypeform");
            const fooditemform = document.getElementById("menufooditem");
            const msgbox = document.querySelector("#messagebox");
            const alertbox = document.querySelector(".alert");
            const menutypeselect = document.getElementById("menutypeselect");
            const menuidbox = document.getElementById("menuidd");
            const menuidname = document.getElementById("menuiddname");
            const menusection = document.getElementById("themenusection");
            const menusectionbox = document.getElementById("menusection_id");
            const menudescription = document.querySelector("[name='menudescription']");
            const menusectionidname= document.getElementById("menusection_idname");

            async function retrieveId() {
                const type = menutypeselect.value;
                const description = menudescription.value;

                try {
                    const response = await fetch(`../api/menus/process.retrieveid.php`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            menutype: type,
                            menudescription: description
                        })
                    });
                    if (!response.ok) {
                        throw new Error("Could not send item");
                    }
                    const data = await response.json();
                    menuidbox.value = data.id;
                    menuidname.value=data.type;
                } catch (error) {
                    console.error(error);
                    alertbox.hidden = false;
                    alertbox.classList.remove("alert-success");
                    alertbox.classList.add("alert-danger");
                    msgbox.innerHTML = error.message;
                }
            }

            menutypeselect.addEventListener("change", retrieveId);
            menudescription.addEventListener("input", retrieveId);

            async function handleFormSubmission(form, endpoint) {
                form.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const formData = new FormData(form);
                    const formObj = Object.fromEntries(formData.entries());

                    try {
                        const response = await fetch(endpoint, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify(formObj)
                        });

                        if (!response.ok) {
                            throw new Error("Could not process form");
                        }

                        const data = await response.json();
                        alertbox.hidden = false;
                        if (data.success) {
                            alertbox.classList.remove("alert-danger");
                            alertbox.classList.add("alert-success");
                            msgbox.innerHTML = data.message;
                            form.reset();
                        } else {
                            alertbox.classList.remove("alert-success");
                            alertbox.classList.add("alert-danger");
                            msgbox.innerHTML = Array.isArray(data.message) ? data.message.join("<br>") : data.message;
                        }
                    } catch (error) {
                        console.error(error);
                        alertbox.hidden = false;
                        alertbox.classList.remove("alert-success");
                        alertbox.classList.add("alert-danger");
                        msgbox.innerHTML = error.message;
                    }
                });
            }
           
            //retrivemenu section id like starters appetizers
            async function retrieveMenuSectionId() {
                const menusectiontype= menusection.value;
                const menuidd= menuidbox.value;

                try {
                    const response = await fetch(`../api/menus/process.retrievesectionid.php`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            menusection: menusectiontype,
                            menuid:menuidd
                           
                        })
                    });
                    if (!response.ok) {
                        throw new Error("Could not send item");
                    }
                    const data = await response.json();
                    menusectionbox.value = data.id;
                    menusectionidname.value=data.section;
                } catch (error) {
                    console.error(error);
                    alertbox.hidden = false;
                    alertbox.classList.remove("alert-success");
                    alertbox.classList.add("alert-danger");
                    msgbox.innerHTML = error.message;
                }
            }
            menusection.addEventListener("change",retrieveMenuSectionId);

            if (menuform) {
                handleFormSubmission(menuform, "../api/menus/process.insertmainmenu.php")
            }
            if (fooditemform) {
                handleFormSubmission(fooditemform, "../api/menus/process.insertfooditem.php")
            }



            const priceInput = document.getElementById('priceInput');

            priceInput.addEventListener('input', function() {
                // Remove any non-numeric characters
                let price = this.value.replace(/[^\d.]/g, '');

                // Ensure there's only one decimal point
                price = price.replace(/(\..*)\./g, '$1');

                // Limit the number of decimal places to two
                const decimalIndex = price.indexOf('.');
                if (decimalIndex !== -1) {
                    price = price.substr(0, decimalIndex + 3);
                }

                // Update the input value
                this.value = price;
            });
        });
    </script>

</body>

</html>