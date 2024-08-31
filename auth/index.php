<?php
include("../includes/sessions.php");
$title = "Restaurant Login";
include("../includes/head.php");

if (isset($_SESSION['success']) && $_SESSION['success'] == true) {
    echo "<script>alert('Registration Successful')</script>";
    unset($_SESSION['success']);
    unset($_SESSION['signupdata']);
}
?>
<style>
    .alert,.error,
    select {
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-all;
        /* Optional: This can break words anywhere */
    }
</style>

<body class="bg-gradient-primary loginbody">
    

    <?php
    if (isset($_GET['logout']) && $_GET['logout'] == true) {
    ?>
        <div class="container">
            <div class="alert alert-success logalert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Logout Successful</strong>
            </div>
        </div>
        <script>
            // Remove the query parameter to prevent the message from reappearing on refresh
            window.history.replaceState({}, document.title, window.location.pathname);
        </script>
    <?php
    } 
    $_GET['logout'] = false;
    ?>
           <div class="container">
               <div class="alert alert-success successalert" hidden>
                   <strong id="message"></strong>
               </div>
       <div class="row justify-content-center min-vh-100 align-items-center">
    <div class="col-md-6 col-lg-5 col-xl-4">
        <div class="card shadow-lg o-hidden border-0 my-2">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h4 class="text-dark mb-4">Welcome Back!</h4>
                            </div>
                            <form class="user" method="POST" id="loginForm">
                                <div class="form-group">
                                    <input class="form-control form-control-user" type="email" id="email" aria-describedby="emailHelp" placeholder="Enter Email Address..." name="email">
                                </div>
                                <div class="form-group">
                                    <input class="form-control form-control-user" type="password" id="password" placeholder="Password" name="password">
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox small">
                                        <div class="form-check">
                                            <input class="form-check-input custom-control-input" type="checkbox" id="formCheck-1">
                                            <label class="form-check-label custom-control-label" for="formCheck-1">Remember Me</label>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary btn-block text-white btn-user" type="submit" id="submit" name="submit">Login</button>
                                <hr>
                                <a class="btn btn-primary btn-block text-white btn-google btn-user" role="button"><i class="fab fa-google"></i>&nbsp; Login with Google</a>
                                <a class="btn btn-primary btn-block text-white btn-facebook btn-user" role="button"><i class="fab fa-facebook-f"></i>&nbsp; Login with Facebook</a>
                                <hr>
                            </form>
                            <span class='error' style='text-align:center; color:red; font-size:20px'></span> <br>
                            <div class="text-center"><a class="small" href="../files/forgot-password.html">Forgot Password?</a></div>
                            <div class="text-center"><a class="small" href="signup.php">Create an Account!</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        <?php include("../includes/script.php"); ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const form = document.getElementById("loginForm");
                const logerror = document.querySelector(".error");
                const alertBox = document.querySelector(".successalert");
                const messagebox = document.getElementById("message");

                if (form) {
                    form.addEventListener("submit", async function(event) {
                        event.preventDefault();
                        const formData = new FormData(form);
                        const formobj = Object.fromEntries(formData.entries());
                        try {
                            const response = await fetch("../api/auth/process.login.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify(formobj)
                            });

                            if (!response.ok) {
                                throw new Error("Could not process data");
                            }
                            const data = await response.json();

                            if (logerror) logerror.innerHTML = data.message;
                            if (data.success) {
                                alertBox.hidden = false;
                                if (messagebox) messagebox.innerHTML = data.message;
                                setTimeout(function() {
                                    window.location.href = data.redirecturl;
                                }, 5000);
                            } else {
                                if (logerror) logerror.innerHTML = Object.values(data.message).join("<br>");
                            }

                        } catch (error) {
                            console.error(error);
                            if (logerror) logerror.innerHTML = error;
                        }
                    });
                }

                setTimeout(function() {
                    let logalert = document.querySelector(".logalert");
                    if (logalert) {
                        logalert.style.display = 'none';
                    }
                }, 5000); // Hide after 5 seconds
            });
        </script>
</body>

</html>