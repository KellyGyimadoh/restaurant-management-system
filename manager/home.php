<?php
$title = "Manager Dashboard";
require("../includes/sessions.php");
require("../includes/head.php");
if (!isloggedin() && !isset($_SESSION['accounttype']) && $_SESSION['accounttype'] !== "director") {

    header("Location:../auth/index.php");
    die();
}
   

if(isset($_SESSION['viewerrors']) && $_SESSION['viewerrors'] == true){
    echo "<script>alert('User details not found')</script>";
    unset($_SESSION['viewerrors']);
}

?>

<body id="page-top">
    <div id="wrapper">
        <!--header-->
        <?php
        include("../includes/auth.sidebar.php");
        include("../includes/auth.header.php");
        ?>
        <div class="container-fluid">
            <div class="d-sm-flex justify-content-between align-items-center mb-4">
                <h3 class="text-dark mb-0"><?php echo $title . " Welcome " . htmlspecialchars($_SESSION['userinfo']['fname']); ?></h3><a class="btn btn-primary btn-sm d-none d-sm-inline-block" role="button" href="#"><i class="fas fa-download fa-sm text-white-50"></i>&nbsp;Generate Report</a>
            </div>
            <div class="row">
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-left-primary py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col mr-2">
                                    <div class="text-uppercase text-primary font-weight-bold text-xs mb-1"><span>Recent Reservation</span></div>
                                    <div class="text-dark font-weight-bold h5 mb-0"><span id="recentreservation"></span></div>
                                </div>

                                <div class="col-auto"><i class="fas fa-calendar fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-left-primary py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">

                                <div class="col mr-2">
                                    <div class="text-uppercase text-primary font-weight-bold text-xs mb-1"><span>Reservations Booked</span></div>
                                    <div class="text-dark font-weight-bold h5 mb-0"><span id="reservationcount"></span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-calendar fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-left-success py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col mr-2">
                                    <div class="text-uppercase text-success font-weight-bold text-xs mb-1"><span>Food Items Available</span></div>
                                    <div class="text-dark font-weight-bold h5 mb-0"><span id="fooditems"></span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-clipboard-list fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-left-success py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col mr-2">
                                    <div class="text-uppercase text-success font-weight-bold text-xs mb-1">
                                        <span>Sales Made Today</span>
                                    </div>
                                    <div class="text-dark font-weight-bold h5 mb-0">
                                        <span id="todaysales"></span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-left-success py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col mr-2">
                                    <div class="text-uppercase text-success font-weight-bold text-xs mb-1">
                                        <span>Sales This Month</span>
                                    </div>
                                    <div class="text-dark font-weight-bold h5 mb-0">
                                        <span id="monthlysales"></span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-left-success py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col mr-2">
                                    <div class="text-uppercase text-success font-weight-bold text-xs mb-1">
                                        <span>Annual Sales</span>
                                    </div>
                                    <div class="text-dark font-weight-bold h5 mb-0">
                                        <span id="yearlysales"></span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-left-info py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col mr-2">
                                    <div class="text-uppercase text-info font-weight-bold text-xs mb-1"><span>Tasks</span></div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="text-dark font-weight-bold h5 mb-0 mr-3"><span>50%</span></div>
                                        </div>
                                        <div class="col">
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-info" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%;"><span class="sr-only">50%</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto"><i class="fas fa-clipboard-list fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-left-warning py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col mr-2">
                                    <div class="text-uppercase text-warning font-weight-bold text-xs mb-1"><span>Pending Requests</span></div>
                                    <div class="text-dark font-weight-bold h5 mb-0"><span>18</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-comments fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7 col-xl-8">
                    <div class="card shadow mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="text-primary font-weight-bold m-0">Earnings Overview</h6>
                            <div class="dropdown no-arrow"><button class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button"><i class="fas fa-ellipsis-v text-gray-400"></i></button>
                                <div class="dropdown-menu shadow dropdown-menu-right animated--fade-in" role="menu">
                                    <p class="text-center dropdown-header">dropdown header:</p><a class="dropdown-item" role="presentation" href="#">&nbsp;Action</a><a class="dropdown-item" role="presentation" href="#">&nbsp;Another action</a>
                                    <div class="dropdown-divider"></div><a class="dropdown-item" role="presentation" href="#">&nbsp;Something else here</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-area"><canvas data-bs-chart="{&quot;type&quot;:&quot;line&quot;,&quot;
                            data&quot;:{&quot;labels&quot;:[&quot;Jan&quot;,&quot;Feb&quot;,
                            &quot;Mar&quot;,&quot;Apr&quot;,&quot;May&quot;,
                            &quot;Jun&quot;,&quot;Jul&quot;,&quot;Aug&quot;],&quot;
                            datasets&quot;:[{&quot;label&quot;:&quot;Earnings&quot;,&quot;
                            fill&quot;:true,&quot;data&quot;:[&quot;0&quot;,&quot;10000&quot;,&quot;
                            5000&quot;,&quot;15000&quot;,&quot;10000&quot;,&quot;20000&quot;
                            ,&quot;15000&quot;,&quot;25000&quot;],&quot;backgroundColor&quot;
                            :&quot;rgba(78, 115, 223, 0.05)&quot;,&quot;borderColor&quot;:&quot;
                            rgba(78, 115, 223, 1)&quot;}]},&quot;options&quot;:{&quot;maintainAspectRatio&quot;
                            :false,&quot;legend&quot;:{&quot;display&quot;:false},&quot;title&quot;:{},&quot;
                            scales&quot;:{&quot;xAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:
                            &quot;rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&
                            quot;,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;
                            :[&quot;2&quot;],&quot;zeroLineBorderDash&quot;:[&quot;2&quot;],&quot;drawOnChartArea&quot;
                            :false},&quot;ticks&quot;:{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;padding&quot;
                            :20}}],&quot;yAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:&quot;
                            rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&quot;
                            ,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;
                            :[&quot;2&quot;],&quot;zeroLineBorderDash&quot;:[&quot;2&quot;]},&quot;ticks&quot;
                            :{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;padding&quot;:20}}]}}}"></canvas></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-xl-4">
                    <div class="card shadow mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="text-primary font-weight-bold m-0">Revenue Sources</h6>
                            <div class="dropdown no-arrow"><button class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button"><i class="fas fa-ellipsis-v text-gray-400"></i></button>
                                <div class="dropdown-menu shadow dropdown-menu-right animated--fade-in" role="menu">
                                    <p class="text-center dropdown-header">dropdown header:</p><a class="dropdown-item" role="presentation" href="#">&nbsp;Action</a><a class="dropdown-item" role="presentation" href="#">&nbsp;Another action</a>
                                    <div class="dropdown-divider"></div><a class="dropdown-item" role="presentation" href="#">&nbsp;Something else here</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-area"><canvas data-bs-chart="{&quot;type&quot;:&quot;doughnut&quot;,&quot;data&quot;:{&quot;labels&quot;:[&quot;Direct&quot;,&quot;Social&quot;,&quot;Referral&quot;],&quot;datasets&quot;:[{&quot;label&quot;:&quot;&quot;,&quot;backgroundColor&quot;:[&quot;#4e73df&quot;,&quot;#1cc88a&quot;,&quot;#36b9cc&quot;],&quot;borderColor&quot;:[&quot;#ffffff&quot;,&quot;#ffffff&quot;,&quot;#ffffff&quot;],&quot;data&quot;:[&quot;50&quot;,&quot;30&quot;,&quot;15&quot;]}]},&quot;options&quot;:{&quot;maintainAspectRatio&quot;:false,&quot;legend&quot;:{&quot;display&quot;:false},&quot;title&quot;:{}}}"></canvas></div>
                            <div class="text-center small mt-4"><span class="mr-2"><i class="fas fa-circle text-primary"></i>&nbsp;Direct</span><span class="mr-2"><i class="fas fa-circle text-success"></i>&nbsp;Social</span><span class="mr-2"><i class="fas fa-circle text-info"></i>&nbsp;Refferal</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="text-primary font-weight-bold m-0">Projects</h6>
                        </div>
                        <div class="card-body">
                            <h4 class="small font-weight-bold">Server migration<span class="float-right">20%</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-danger" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;"><span class="sr-only">20%</span></div>
                            </div>
                            <h4 class="small font-weight-bold">Sales tracking<span class="float-right">40%</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-warning" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;"><span class="sr-only">40%</span></div>
                            </div>
                            <h4 class="small font-weight-bold">Customer Database<span class="float-right">60%</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-primary" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"><span class="sr-only">60%</span></div>
                            </div>
                            <h4 class="small font-weight-bold">Payout Details<span class="float-right">80%</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-info" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;"><span class="sr-only">80%</span></div>
                            </div>
                            <h4 class="small font-weight-bold">Account setup<span class="float-right">Complete!</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-success" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"><span class="sr-only">100%</span></div>
                            </div>
                        </div>
                    </div>
                          </div>
               
            </div>
        </div>
    </div>
    <?php
    include("../includes/footer.php");
    require("../includes/script.php");
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fooditembox = document.querySelector("#fooditems");
            const reservationcount = document.querySelector("#reservationcount");
            const recentreservation = document.querySelector("#recentreservation");
            const todaySales = document.getElementById("todaysales");
            const monthSales = document.getElementById("monthlysales");
            const yearSales = document.getElementById("yearlysales");
            getFoodCount();
            getReservation();
            getSales();
            async function getFoodCount() {
                try {
                    const response = await fetch("../api/dashboard/retrievefoodcount.php");
                    if (!response.ok) {
                        throw new Error('data cannot be fetched!');
                    }

                    const data = await response.json();
                    if (data.success) {
                        fooditembox.innerHTML = data.foodcount;
                    } else {

                        fooditembox.innerHTML = data.message;
                    }
                } catch (error) {
                    console.error(error);
                }
            }
            async function getReservation() {
                try {
                    const response = await fetch("../api/dashboard/retrievereservation.php");
                    if (!response.ok) {
                        throw new Error('data cannot be fetched!');
                    }

                    const data = await response.json();
                    if (data.success) {
                        reservationcount.innerHTML = data.reservationcount;
                        recentreservation.innerHTML = data.recentreservationname.toUpperCase() + " " + data.contact;
                    } else {

                        recentreservation.innerHTML = data.message;
                    }
                } catch (error) {
                    console.error(error);
                }
            }
            async function getSales() {
                try {
                    const response = await fetch("../api/dashboard/retrievesales.php");
                    if (!response.ok) {
                        throw new Error('data cannot be fetched!');
                    }

                    const data = await response.json();
                    if (data.success) {
                        todaySales.innerHTML = formatCurrency(data.todaysales);
                        monthSales.innerHTML = formatCurrency(data.monthsales);
                        yearSales.innerHTML = formatCurrency(data.yearsales);
                    } else {

                        todaySales.innerHTML = data.message;
                        monthSales.innerHTML = data.message;
                        yearSales.innerHTML = data.message;
                    }
                } catch (error) {
                    console.error(error);
                    todaySales.innerHTML = "Error fetching data";
                    monthSales.innerHTML = "Error fetching data";
                    yearSales.innerHTML = "Error fetching data";
                }


            }

            //add currecency
            function formatCurrency(amount) {
                return new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'GHS'
                }).format(amount);
            }

        })
    </script>

</body>

</html>