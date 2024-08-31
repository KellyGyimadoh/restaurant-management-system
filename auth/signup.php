<?php
require("../includes/sessions.php");
$title="Signup";
include("../includes/head.php");


?>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="card shadow-lg o-hidden border-0 my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-flex">
                        <div class="flex-grow-1 bg-register-image" style="background-image: url(&quot;../assets/img/dogs/image2.jpeg&quot;);"></div>
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h4 class="text-dark mb-4">Create an Account!</h4>
                            </div>
                            <form class="user" action="../api/auth/process.signup.php" method="POST">
                               <?php
                              signupData();
                               ?>
                                </div><button class="btn btn-primary btn-block text-white btn-user" type="submit" name="submit">Register Account</button>
                            </form>
                            <?php
                            if(isset($_SESSION['errors'])&& !empty($_SESSION['errors'])){
                                foreach($_SESSION['errors']as $error){
                                    echo "<span class='error' style='text-align:center; color:red; font-size:20px'>$error</span> <br>";
                                }
                                unset($_SESSION['errors']);
                            }
                            ?>
                            <hr><a class="btn btn-primary btn-block text-white btn-google btn-user" role="button"><i class="fab fa-google"></i>&nbsp; Register with Google</a><a class="btn btn-primary btn-block text-white btn-facebook btn-user" role="button"><i class="fab fa-facebook-f"></i>&nbsp; Register with Facebook</a>
                            <hr>
                            <div class="text-center"><a class="small" href="forgot-password.html">Forgot Password?</a></div>
                            <div class="text-center"><a class="small" href="index.php">Already have an account? Login!</a></div>
                        </div>
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