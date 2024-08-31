<?php
require("../includes/sessions.php");
$title = "Register Shop";
include("../includes/head.php");

if (!isloggedin() && !isset($_SESSION['accounttype']) && $_SESSION['accounttype'] !== "director") {
    header("Location:../auth/index.php");
    die();
}
?>
<style>
    .custom-alert {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        display: none;
    }
</style>

<body class="bg-gradient-dark">
    <div class="container">
        <div class="card shadow-md border-0 my-2">
            <div class="card-body p-3 m-2">
                <div class="row min-vh-100 align-items-center justify-content-center">
                    <div class="col-md-7 col-lg-6 col-xl-8">
                        <div class="p-5">
                            <div class="text-center">
                                <h4 class="text-dark mb-4">Register Shop</h4>
                            </div>
                            <form id="shopinsert" class="user" method="POST" action="processForm.php">
                                <?php signupShop(); ?>
                                <div class="d-grid gap-2 col-6 mx-auto">
                                    <button class="small btn btn-success btn-block text-white btn-user" type="submit" name="submit">Register Shop</button>
                                </div>
                            </form>
                            <?php
                            if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
                                foreach ($_SESSION['errors'] as $error) {
                                    echo "<span class='error' style='text-align:center; color:red; font-size:20px'>$error</span> <br>";
                                }
                                unset($_SESSION['errors']);
                            }
                            ?>
                            <hr>
                            <div class="text-center"><a class="small btn btn-primary btn-block text-white btn-user m-3" href="../manager/home.php">Go To Dashboard</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="success-alert" class="alert alert-success custom-alert" role="alert">
            <strong id="success-message"></strong>
        </div>
        <div id="danger-alert" class="alert alert-danger custom-alert" role="alert">
            <strong id="danger-message"></strong>
        </div>
    </div>

    <?php include("../includes/script.php"); ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("shopinsert");

            const AddShopForm = async (e) => {
                e.preventDefault();
                const shopFormdata = new FormData(form);
                const formobj = Object.fromEntries(shopFormdata.entries());
                try {
                    const response = await fetch('../api/shop/process.addshop.php', {
                        method: "POST",
                        headers:{'Content-Type':'application/json'},
                        body: JSON.stringify(formobj)
                    });

                    if (!response.ok) {
                        throw new Error('Error sending data');
                    }
                    const data = await response.json();
                    if (data.success) {
                        showAlert("success", data.message);
                        setTimeout(() => {
                            form.reset();
                        }, 3000);
                    } else {
                        let errormessage = [data.message];
                        errormessage.forEach((msg) => {
                            showAlert("danger", msg);
                        });
                    }
                } catch (error) {
                    showAlert("danger", error.message);
                }
            }
            form.addEventListener('submit', AddShopForm);

            function showAlert(type, message) {
                let alertBox, messageBox;
                if (type === 'success') {
                    alertBox = document.getElementById('success-alert');
                    messageBox = document.getElementById('success-message');
                } else if (type === 'danger') {
                    alertBox = document.getElementById('danger-alert');
                    messageBox = document.getElementById('danger-message');
                }

                messageBox.textContent = message;
                alertBox.style.display = 'block';

                setTimeout(() => {
                    alertBox.style.display = 'none';
                }, 3000);
            }
        });
    </script>
</body>
</html>
