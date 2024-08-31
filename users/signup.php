<?php
require("../includes/sessions.php");
$title = "Register User";

include("../includes/head.php");

if (!isloggedin() && !isset($_SESSION['accounttype']) && $_SESSION['accounttype'] !== "director") {

    header("Location:../auth/index.php");
    die();
}
?>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="card  shadow-md  border-0 my-2 ">
            <div class="card-body p-0">
                <div class="row  min-vh-100 align-items-center justify-content-center ">
                    <div class="col-md-7 col-lg-6 col-xl-8">

                        <div class="p-5">
                            <div class="text-center">
                                <h4 class="text-dark mb-4">Register User</h4>
                            </div>
                            <form class="user" action="../api/users/process.signup.php" method="POST">
                                <?php
                                signupData();
                                ?>
                        </div><button class="btn btn-primary btn-block text-white btn-user" type="submit" name="submit">Register Account</button>
                        </form>
                        <?php
                        if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
                            foreach ($_SESSION['errors'] as $error) {
                                echo "<span class='error' style='text-align:center; color:red; font-size:20px'>$error</span> <br>";
                            }
                            unset($_SESSION['errors']);
                        }
                        ?>
                        <hr><a class="btn btn-primary btn-block text-white btn-google btn-user" role="button"><i class="fab fa-google"></i>&nbsp; Register with Google</a><a class="btn btn-primary btn-block text-white btn-facebook btn-user" role="button"><i class="fab fa-facebook-f"></i>&nbsp; Register with Facebook</a>
                        <hr>
                       
                        <div class="text-center"><a class="small btn btn-primary btn-block text-white btn-google btn-user" href="../manager/home.php">Go To Dashboard</a></div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include("../includes/script.php");
    ?>
</body>

</html>