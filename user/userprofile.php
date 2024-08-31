<?php
include("../includes/sessions.php");
$username = ucwords($_SESSION['userinfo']['fname']);
$title = "$username Profile";

include("../includes/head.php");

if (!isloggedin() || !isset($_SESSION['accounttype']) || ($_SESSION['accounttype'] !== "director" && $_SESSION['accounttype'] !== "staff")) {
    header("Location: ../auth/index.php");
    die();
}
if (isset($_SESSION['updated']) && $_SESSION['updated'] == true) {
    echo "<script>alert('User Successfully updated')</script>";
    unset($_SESSION['updated']);
}

?>

<body id="page-top">
    <div id="wrapper">
        <?php
        include("../includes/sidebar.php");
        include("../includes/header.php");
        ?>

        <div class="container-fluid">
            <h3 class="text-dark mb-4">Profile</h3>
            <div class="row mb-3">
                <div class="col-4">
                    <div class="card mb-3">
                        <div class="card-body text-center shadow"><img class="rounded-circle mb-3 mt-4" src="assets/img/dogs/image2.jpeg" width="160" height="160">
                            <div class="mb-3"><button class="btn btn-primary btn-sm" type="button">Change Photo</button></div>
                        </div>
                    </div>
                </div>



                <div class="col-8">
                    <div class="card shadow mb-3">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <p class="text-primary m-0 font-weight-bold"><?php echo $title ?></p>
                            </div>
                            <div class="card-body">
                                <form action="../api/users/process.edit.php" id="managerform" method="POST">
                                    <!--manager profile form here-->
                                    <?php
                                    viewWorkerProfile();
                                    ?>
                                    <div class="col">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-success" id="enableedit">Press to Edit</button>
                                        </div>
                                        <div class="mb-3 form-group"><button class="btn btn-primary btn-sm" type="submit" name="confirmuserupdate" disabled>Confirm Edit</button></div>
                                    </div>
                                </form>
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
        // Get form and button elements
        const form = document.getElementById("managerform");
        const enableFormBtn = document.getElementById("enableedit");

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
    </script>
</body>

</html>