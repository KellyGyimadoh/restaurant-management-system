<?php
$title = "Manager Profile";
include("../includes/sessions.php");
include("../includes/head.php");

if (!isloggedin() && !isset($_SESSION['accounttype']) && $_SESSION['accounttype'] !== "director") {

    header("Location:../auth/index.php");
    die();
}
if (isset($_SESSION['updated']) && $_SESSION['updated'] == true) {
    echo "<script>alert('User Successfully updated')</script>";
    unset($_SESSION['updated']);
}
if(isset( $_SESSION['uploadsuccess'])){
                                      
    echo "".$_SESSION['uploadsuccess'];
}
unset($_SESSION['uploadsuccess']);
?>

<body id="page-top">
    <div id="wrapper">
        <?php
        include("../includes/auth.sidebar.php");
        include("../includes/auth.header.php");
        ?>

        <div class="container-fluid">
            <h3 class="text-dark mb-4">Profile</h3>
            <div class="row justify-content-center min-vh-100 align-items-center">



                <div class="row">
                    <div class="col">
                        <div class="card shadow mb-3">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <p class="text-primary m-0 font-weight-bold">Manager Profile</p>
                                </div>
                                <div class="card-body">
                                    <form action="../api/users/process.edit.php" id="managerform" method="POST" enctype="multipart/form-data">
                                        <div class="col-lg-4">
                                            <div class="card mb-3">
                                                <div class="card-body text-center shadow">
                                                    <img id="profileImage" class="rounded-circle mb-3 mt-4" src="<?php echo $_SESSION['userinfo']['image'];?>" width="160" height="160">
                                                    <div class="mb-3">
                                                        <button class="btn btn-primary btn-sm" type="button" id="imagebtn">Change Photo</button>
                                                    </div>
                                                    <input type="file" id="imageInput" name="profileImage" style="display: none;">
                                                </div>
                                            </div>
                                        </div>
                                        <!--manager profile form here-->
                                        <?php
                                        viewManagerProfile();
                                        ?>
                                        <div class="col">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-success" id="enableedit">Press to Edit</button>
                                            </div>
                                            <div class="mb-3 form-group"><button class="btn btn-primary btn-sm" type="submit" name="confirmupdate" disabled>Confirm Edit</button></div>
                                        </div>
                                    </form>
                                    <?php
                                   
                                    if(isset($_SESSION['uploaderrors'])){
                                        foreach($_SESSION['uploaderrors'] as $error){
                                            echo "$error <br>";
                                        }
                                    }
                                    unset($_SESSION['uploaderrors']);
                                    ?>
                                </div>
                            </div>
                            <div class="card-header py-3">

                                <p class="text-primary m-0 font-weight-bold">User Settings</p>
                            </div>
                            <div class="card-body">
                                <form action="../api/users/process.edit.php" method="POST">
                                    <?php

                                    if (isset($_SESSION['updateerrors'])) {
                                        foreach ($_SESSION['updateerrors'] as $error) {
                                            echo "<tr><td colspan='6'>$error</td></tr><br>";
                                        }
                                        unset($_SESSION['updateerrors']);
                                    } else {


                                        if (!empty($_SESSION['updatesignupinfo']) && isset($_SESSION['updatesignupinfo'])) {

                                    ?>

                                            <?php
                                            ViewUserSignupForm();
                                            ?>
                                            <div class="col">
                                                <div class="form-group"><button class="btn btn-primary btn-sm" type="submit" name="save">Save Settings</button></div>
                                            </div>

                                </form>
                            </div>
                    <?php
                                        } else {
                                            echo "<tr><td colspan='6'>No data found at all</td></tr>";
                                        }
                                        unset($_SESSION['updatesignupinfo']);
                                    }

                    ?>
                        </div>
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <p class="text-primary m-0 font-weight-bold">Contact Settings</p>
                            </div>
                            <div class="card-body">
                                <!--contact form here-->
                                <!--contact form here-->
                            </div>
                        </div>
                    </div>
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
            // Get form and button elements
            const form = document.getElementById("managerform");
            const enableFormBtn = document.getElementById("enableedit");
            const imageBtn = document.getElementById('imagebtn');
            const imageInput = document.getElementById('imageInput');
            const profileImage = document.getElementById('profileImage');

            imageBtn.addEventListener('click', function() {
                imageInput.click();
            });

            imageInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profileImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
            // Function to enable the form
            function enableForm() {
                // Enable all input elements within the form
                // Enable all input and select elements within the form
                const inputs = form.querySelectorAll("input, select,button");
                for (let i = 0; i < inputs.length; i++) {
                    inputs[i].removeAttribute("disabled");
                }

            }

            // Event listener to enable form when button is clicked
            enableFormBtn.addEventListener("click", function(e) {
                e.preventDefault();
                enableForm();
            });

        })
    </script>
</body>

</html>