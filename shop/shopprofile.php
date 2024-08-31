<?php
$title = "Shop Profile";
include("../includes/sessions.php");
include("../includes/head.php");

if (!isloggedin() && !isset($_SESSION['accounttype']) && $_SESSION['accounttype'] !== "director") {

    header("Location:../auth/index.php");
    die();
}

if (isset($_SESSION['uploadsuccess'])) {

    echo "" . $_SESSION['uploadsuccess'];
}
unset($_SESSION['uploadsuccess']);
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

<body id="page-top">
    <div id="wrapper">
        <?php
        include("../includes/auth.sidebar.php");
        include("../includes/auth.header.php");
        ?>

        <div class="container-fluid">
            <h3 class="text-dark mb-4">Profile</h3>
            <div class="row justify-content-center min-vh-100 align-items-center mt-n5">

                
                    

                <div class="row m-5 p-5">
                    <div class="col">
                        <div class="card shadow mt-n5 ">
                            <div class="card shadow ">
                                <div class="card-header py-3">
                                    <p class="text-primary m-0 font-weight-bold">Shop Profile</p>
                                </div>
                                <div class="col p-3">
                        <button type="button" class="btn btn-primary" id="addshop">Register Shop</button>
                    </div>
            
                                <div class="card-body">
                                    <form action="" id="shopform" method="POST" enctype="multipart/form-data">

                                        <!--manager profile form here-->
                                        <?php
                                        viewShopProfile();
                                        ?>
                                        <div class="col">

                                            <div class="form-group mt-4">
                                                <button type="button" class="btn btn-success" id="enableedit">Press to Edit</button>
                                            </div>
                                            <div class="mb-3 form-group"><button class="btn btn-primary btn-sm" type="submit" name="confirmupdate" disabled>Confirm Edit</button></div>
                                        </div>
                                    </form>

                                </div>
                            </div>


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
    <?php
    include("../includes/footer.php");
    include("../includes/script.php");

    ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get form and button elements
            const form = document.getElementById("shopform");
            const enableFormBtn = document.getElementById("enableedit");
            const imageBtn = document.getElementById('imagebtn');
            const imageInput = document.getElementById('imageInput');
            const profileImage = document.getElementById('profileImage');
            const addBtn = document.getElementById('addshop');

            addBtn.addEventListener('click',()=>{
                window.location.href='shopsignup.php';
            })
            const updateShopForm = async (e) => {
                e.preventDefault();
                const shopFormdata = new FormData(form);
                const formobj = Object.fromEntries(shopFormdata.entries());
                try {
                    const response = await fetch('../api/shop/process.updateshop.php', {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(
                            formobj
                        )
                    })

                    if (!response.ok) {
                        throw new Error('Error sending data');
                    }
                    const data = await response.json();
                    if (data.success) {
                        showAlert("success", data.message);
                       setTimeout(()=>{
                        window.location.href=`shopsettings.php`
                       },3000)
                    } else {
                        showAlert("danger", data.message);
                    }

                } catch (error) {
                    showAlert("danger", error);
                    console.log(error);
                }
            }
            form.addEventListener('submit', updateShopForm);

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


            // imageBtn.addEventListener('click', function() {
            //     imageInput.click();
            // });

            // imageInput.addEventListener('change', function(event) {
            //     const file = event.target.files[0];
            //     if (file) {
            //         const reader = new FileReader();
            //         reader.onload = function(e) {
            //             profileImage.src = e.target.result;
            //         };
            //         reader.readAsDataURL(file);
            //     }
            // });
            // Function to enable the form
            function enableForm() {
                // Enable all input elements within the form
                // Enable all input and select elements within the form
                const inputs = form.querySelectorAll("input, select,button,textarea");
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