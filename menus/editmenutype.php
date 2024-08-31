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
                                <form action="../api/menus/process.editmainmenu.php" id="foodeditform" method="POST">
                                    <!--manager profile form here-->
                                    <?php viewMenuTypeInfo(); ?>

                                    <div class="form-row">
                                        <div class="col-12 d-grid gap-2 d-md-flex justify-content-md-between">
                                            <div class=" form-group">
                                                <button type="button" class="btn btn-success btn-sm" id="enablefoodedit">Press to Edit</button>
                                            </div>
                                            <div class=" form-group">
                                                <button class="btn btn-primary btn-sm" type="submit" name="save" disabled>Confirm Edit</button>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </form>
                                <div class="mb-3 form-group">
                                    <button class="btn btn-danger btn-md" type="button" name="moveback" id="moveback">Back</button>
                                </div>
                            </div>

                            <?php
                            if(isset( $_SESSION['updatemenuerrors'])){
                                foreach( $_SESSION['updatemenuerrors'] as $error){
                                    echo"$error <br>";
                                }
                            }
                         
                            unset( $_SESSION['updatemenuerrors']);
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
          
           

           


          
        });
    </script>

</body>

</html>