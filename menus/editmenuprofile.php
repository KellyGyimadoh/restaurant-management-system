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
            <h3 class="text-dark mb-4">Food Item Settings</h3>
            <div class="row row justify-content-center min-vh-100 align-items-center ">
                <div class="col-md-8 col-lg-7 col-xl-8">
                    <div class="card shadow mb-3">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <p class="text-primary m-0 font-weight-bold">Change Food settings</p>

                            </div>
                            <div class="form-row">
                                <div class="container">
                                    <div class="alert alert-success successalert " hidden>
                                        <strong id="messagebox"></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="" id="foodeditform" method="POST">
                                    <!--manager profile form here-->
                                    <?php viewFoodProfile(); ?>

                                    <div class="form-row">
                                        <div class="col-12 d-grid gap-2 d-md-flex justify-content-md-between">
                                            <div class="mb-3 form-group">
                                                <button type="button" class="btn btn-success btn-md" id="enablefoodedit">Press to Edit</button>
                                            </div>
                                            <div class="mb-3 form-group">
                                                <button class="btn btn-primary btn-md" type="submit" name="confirmfoodupdate" disabled>Confirm Edit</button>
                                            </div>
                                            <div class="mb-3 form-group">
                                                <button class="btn btn-danger btn-md" type="button" name="deleteall" id="deleteall" disabled>Delete All</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="mb-3 form-group">
                                    <button class="btn btn-danger btn-md" type="button" name="moveback" id="moveback">Back</button>
                                </div>
                            </div>

                            <?php
                            unset($_SESSION['fooddetails']);
                            ?>

                        </div>

                    </div>
                </div>

                <!--add fooitemesction -->




            </div>
        </div>

        <?php
    include("../includes/footer.php");
    include("../includes/script.php");

    ?>
    </div>
  
    
    


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const foodform = document.getElementById("foodeditform");
            const msgbox = document.querySelector("#messagebox");
            const alertbox = document.querySelector(".alert");
            const deleteallbtn = document.getElementById("deleteall");
            const movebackbtn = document.getElementById("moveback");
            const menuid = document.getElementById("typeid");

            // Get form and button elements
            movebackbtn.addEventListener("click", () => {
                window.history.back();
            })
            const enableFormBtn = document.getElementById("enablefoodedit");
            disableForm();
            // Function to enable the form
            function enableForm() {
                // Enable all input elements within the form
                // Enable all input and select elements within the form
                const inputs = foodform.querySelectorAll("input, select,button");
                for (let i = 0; i < inputs.length; i++) {
                    inputs[i].removeAttribute("disabled");
                }

            }
            //disbale form
            function disableForm() {
                const inputs = foodform.querySelectorAll("input,select,button[name='confirmfoodupdate']");
                for (let i = 0; i < inputs.length; i++) {
                    inputs[i].setAttribute('disabled', 'true');

                }
            }

            // Event listener to enable form when button is clicked
            enableFormBtn.addEventListener("click", function(e) {
                e.preventDefault();
                enableForm();
            });

            deleteallbtn.addEventListener("click", async (e) => {
                typeid = menuid.value;
                try {
                    const response = await fetch(`../api/menus/process.deleteall.php`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            typeid: typeid
                        })
                    });
                    if (!response.ok) {
                        throw new Error("Could not send item");
                    }
                    const data = await response.json();
                    alertbox.hidden = false;
                    if (data.success) {
                        alertbox.classList.remove("alert-danger");
                        alertbox.classList.add("alert-success");
                        msgbox.innerHTML = data.message;
                        foodform.reset();
                        setTimeout(function() {
                            window.location.href = data.redirecturl;
                        }, 4000)

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

            })
            /*  async function retrieveId() {
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
                    menuidname.value = data.type;
                } catch (error) {
                    console.error(error);
                    alertbox.hidden = false;
                    alertbox.classList.remove("alert-success");
                    alertbox.classList.add("alert-danger");
                    msgbox.innerHTML = error.message;
                }
            }
*/
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
                            setTimeout(function() {
                                window.location.href = data.redirecturl;
                            }, 4000)

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
            /*  async function retrieveMenuSectionId() {
                  const menusectiontype = menusection.value;
                  const menuidd = menuidbox.value;

                  try {
                      const response = await fetch(`../api/menus/process.retrievesectionid.php`, {
                          method: "POST",
                          headers: {
                              "Content-Type": "application/json"
                          },
                          body: JSON.stringify({
                              menusection: menusectiontype,
                              menuid: menuidd

                          })
                      });
                      if (!response.ok) {
                          throw new Error("Could not send item");
                      }
                      const data = await response.json();
                      menusectionbox.value = data.id;
                      menusectionidname.value = data.section;
                  } catch (error) {
                      console.error(error);
                      alertbox.hidden = false;
                      alertbox.classList.remove("alert-success");
                      alertbox.classList.add("alert-danger");
                      msgbox.innerHTML = error.messages;
                  }
              }*/
            // menusection.addEventListener("change", retrieveMenuSectionId);

            /* if (menuform) {
                 handleFormSubmission(menuform, "../api/menus/process.insertmainmenu.php")
             }
             if (fooditemform) {
                 handleFormSubmission(fooditemform, "../api/menus/process.insertfooditem.php")
             }*/
            if (foodform) {
                handleFormSubmission(foodform, "../api/menus/process.editalldetails.php");




            }






            const priceInput = document.getElementById('price');

            priceInput.addEventListener('focus', function() {
                // Select the entire value on focus
                this.select();
            });

            priceInput.addEventListener('input', function() {
                // Remove any non-numeric characters except the decimal point
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