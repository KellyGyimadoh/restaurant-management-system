<?php
$title = "Register Customer";
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

            <h3 class="text-dark mb-4">Customer Settings</h3>
            <div class="row justify-content-center min-vh-100 align-items-center">
                <div class="col-md-8 col-lg-7 col-xl-8">
                    <div class="card shadow mb-3">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <p class="text-primary m-0 font-weight-bold">Add Customer Details</p>
                            </div>
                            <div class="card-body ">
                                <form id="customerform" method="POST" enctype="multipart/form-data">



                                  

                                    <div class='form-row'>
                                        <div class="col">
                                            <div class='form-group'>
                                                <label for='firstname'><strong>FirstName</strong></label>
                                                <input class='form-control' type='text' placeholder='First Name' name='firstname' >
                                            </div>
                                        </div>
                                    </div>
                                    <div class='form-row'>
                                        <div class="col">
                                            <div class='form-group'>
                                                <label for='lastname'><strong>LastName</strong></label>
                                                <input class='form-control' type='text' placeholder='Last Name' name='lastname' >
                                            </div>
                                        </div>
                                    </div>
                                    <div class='form-row'>
                                        <div class="col">
                                            <div class='form-group'>
                                                <label for='email'><strong>Email</strong></label>
                                                <input class='form-control' type='email' placeholder='Email' name='email' >
                                            </div>
                                        </div>
                                    </div>
                                    <div class='form-row'>
                                        <div class="col">
                                            <div class='form-group'>
                                                <label for='phone'><strong>Phone</strong></label>
                                                <input class='form-control' type='tel' placeholder='Phone' name='phone' >
                                            </div>
                                        </div>
                                    </div>
                                    <div class='form-row'>
                                        <div class="col">
                                            <div class='form-group'>
                                                <label for='loyaltypoint'><strong>Loyalty Point</strong></label>&nbsp;
                                                <!-- Use a select input for menu sections -->
                                                <select name='loyaltypoint'>
                                                    <option value=''>Select Menu Section</option>
                                                    <option value='1'>1</option>
                                                    <option value='2'>2</option>
                                                    <option value='3'>3</option>
                                                    <option value='4'>4</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="mb-3 form-group">
                                            <button class="btn btn-primary btn-sm" type="submit">Place Order</button>
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
    
            const customerForm = document.getElementById("customerform");
            const msgbox = document.querySelector("#messagebox");
            const alertbox = document.querySelector(".alert");
            async function handleFormSubmission(event) {
                event.preventDefault();
                const formData = new FormData(customerForm);
                const formobj = Object.fromEntries(formData.entries());

                try {
                    const response = await fetch("../api/customers/process.insertordercustomer.php", {
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
                        localStorage.setItem("customerid",data.customerid);
                        localStorage.setItem("orderstatus",data.orderstatus);
                        alertbox.classList.remove("alert-danger");
                        alertbox.classList.add("alert-success");
                        msgbox.innerHTML = data.message;
                        customerForm.reset();
                        setTimeout(function(){
                            window.location.href="../orders/neworderprofile.php";
                        },3000)


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
            }

            customerForm.addEventListener("submit", handleFormSubmission);

          

           
          




        });
    </script>

</body>

</html>