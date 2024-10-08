<?php
include("../includes/sessions.php");
$title="Profile";
include("../includes/head.php");

if (!isloggedin() && !isset($_SESSION['accounttype']) && $_SESSION['accounttype'] !== "director") {

    header("Location:../auth/index.php");
    die();
}

?>

<body id="page-top">
    <div id="wrapper">
        <?php
        include("../includes/auth.sidebar.php");
        include("../includes/auth.header.php");
        ?>

        <div class="container-fluid">
            <h3 class="text-dark mb-4">Profile</h3>
            <div class="row mb-3">
                <div class="col-lg-4">
                    <div class="card mb-3">
                        <div class="card-body text-center shadow"><img class="rounded-circle mb-3 mt-4" src="assets/img/dogs/image2.jpeg" width="160" height="160">
                            <div class="mb-3"><button class="btn btn-primary btn-sm" type="button">Change Photo</button></div>
                        </div>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="text-primary font-weight-bold m-0">Projects</h6>
                        </div>
                        <div class="card-body">
                            <h4 class="small font-weight-bold">Server migration<span class="float-right">20%</span></h4>
                            <div class="progress progress-sm mb-3">
                                <div class="progress-bar bg-danger" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;"><span class="sr-only">20%</span></div>
                            </div>
                            <h4 class="small font-weight-bold">Sales tracking<span class="float-right">40%</span></h4>
                            <div class="progress progress-sm mb-3">
                                <div class="progress-bar bg-warning" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;"><span class="sr-only">40%</span></div>
                            </div>
                            <h4 class="small font-weight-bold">Customer Database<span class="float-right">60%</span></h4>
                            <div class="progress progress-sm mb-3">
                                <div class="progress-bar bg-primary" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"><span class="sr-only">60%</span></div>
                            </div>
                            <h4 class="small font-weight-bold">Payout Details<span class="float-right">80%</span></h4>
                            <div class="progress progress-sm mb-3">
                                <div class="progress-bar bg-info" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;"><span class="sr-only">80%</span></div>
                            </div>
                            <h4 class="small font-weight-bold">Account setup<span class="float-right">Complete!</span></h4>
                            <div class="progress progress-sm mb-3">
                                <div class="progress-bar bg-success" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"><span class="sr-only">100%</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row mb-3 d-none">
                        <div class="col">
                            <div class="card text-white bg-primary shadow">
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col">
                                            <p class="m-0">Peformance</p>
                                            <p class="m-0"><strong>65.2%</strong></p>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-rocket fa-2x"></i></div>
                                    </div>
                                    <p class="text-white-50 small m-0"><i class="fas fa-arrow-up"></i>&nbsp;5% since last month</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card text-white bg-success shadow">
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col">
                                            <p class="m-0">Peformance</p>
                                            <p class="m-0"><strong>65.2%</strong></p>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-rocket fa-2x"></i></div>
                                    </div>
                                    <p class="text-white-50 small m-0"><i class="fas fa-arrow-up"></i>&nbsp;5% since last month</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card shadow mb-3">
                                <div class="card-header py-3">

                                    <p class="text-primary m-0 font-weight-bold">User Settings</p>
                                </div>
                                <div class="card-body">
                                    <?php
                                     if (isset($_SESSION['updateerrors'])) {
                                        foreach ($_SESSION['updateerrors'] as $error) {
                                            echo "<tr><td colspan='6'>$error</td></tr><br>";
                                        }
                                        unset($_SESSION['updateerrors']);
                                    }  
                                    
                                    if (!empty($_SESSION['updatecustomerinfo']) && isset($_SESSION['updatecustomerinfo'])) { ?>
                                        <form action="../api/customers/process.editcust.php" method="POST">
                                            <?php viewCustomerForm(); 
                                           
                                            ?>
                                            <div class="col">
                                                <div class="form-group"><button class="btn btn-primary btn-sm" type="submit" name="save">Save Customer Booking Settings</button></div>
                                            </div>

                                        </form>
                                </div>
                            <?php  } else if (!empty($_SESSION['updateordercustomerinfo']) && isset($_SESSION['updateordercustomerinfo'])) { ?>
                                <form action="../api/customers/process.editordercustomer.php" method="POST">
                                    <?php viewOrderCustomerForm(); 
                                   
                                    ?>
                                    <div class="col">
                                        <div class="form-group"><button class="btn btn-primary btn-sm" type="submit" name="save">Save Order Customer Settings</button></div>
                                    </div>

                                </form>
                            </div>
                        <?php  } else {
                                        echo "<tr><td colspan='6'>No data found at all</td></tr>";
                                    }
                                    unset($_SESSION['updatecustomerinfo']);
                                    unset($_SESSION['updateordercustomerinfo']);



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
        <div class="card shadow mb-5">
            <div class="card-header py-3">
                <p class="text-primary m-0 font-weight-bold">Forum Settings</p>
            </div>

        </div>
    </div>
    </div>
    <?php
    include("../includes/footer.php");
    include("../includes/script.php");

    ?>


</body>

</html>