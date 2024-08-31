<?php
$title = "sale table";
include("../includes/sessions.php");
include("../includes/head.php");

if (!isloggedin() || !isset($_SESSION['accounttype']) || ($_SESSION['accounttype'] !== "director" && $_SESSION['accounttype'] !== "staff")) {
    header("Location: ../auth/index.php");
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


    .receipt-container {
        width: 100%;
        max-width: 600px;
        padding: 20px;
        background: #fff;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        text-align: center;
    }

    .receipt-container h2 {
        margin-bottom: 20px;
        color: #333;
    }

    .receipt-container h3 {
        margin-bottom: 20px;
        color: #666;
    }

    .details,
    .transactions,
    .totals {
        margin-bottom: 20px;
        text-align: left;
    }

    .transactions .transaction {
        border-top: 1px solid #ccc;
        padding-top: 10px;
        margin-top: 10px;
    }

    .totals p,
    .details p {
        margin: 5px 0;
        display: flex;
        justify-content: space-between;
    }

    .actions {
        text-align: center;
    }

    .actions button {
        padding: 10px 20px;
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .actions button:hover {
        background-color: #0056b3;
    }
</style>

<body id="page-top">
    <div id="wrapper">
        <?php
        if ($_SESSION['accounttype'] == "director") {
            include("../includes/auth.sidebar.php");
            include("../includes/auth.header.php");
        } elseif ($_SESSION['accounttype'] == "staff") {
            include("../includes/sidebar.php");
            include("../includes/header.php");
        } else {
            die();
        }
        ?>
        <div class="container-fluid">
            <h1 class="mb-4">Customer sales</h1>
            <div class="card shadow">
                <div class="card-header py-3">
                    <p class="text-primary m-0 font-weight-bold">Customer sales</p>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="recordsPerPage">Show
                                <select class="form-control form-control-sm custom-select custom-select-sm" id="recordsPerPage">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                            </label>
                        </div>
                        <div class="col-md-4 offset-md-2 text-right">
                            <form id="searchForm">
                                <input type="search" id="searchInput" class="form-control form-control-sm" placeholder="Search">
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Search</button>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                        <table class="table my-0" id="dataTable">
                            <thead>
                                <tr>
                                    
                                    <th>Order ID</th>
                                    <th>Customer ID</th>
                                    <th>Items</th>
                                    <th>Total Quantity</th>
                                    <th>Total Cost</th>
                                    
                                    <th>Amount Paid</th>
                                    <th>Payment Status</th>
                                   
                                    <th>Actions</th>
                                    <th>Make Payment</th>
                                </tr>
                            </thead>
                            <tbody id="salesTodayTableBody">
                                <!-- sales will be inserted here -->
                            </tbody>
                        </table>
                    </div>

                    <nav>
                        <ul class="pagination" id="pagination">
                            <!-- Pagination links will be inserted here -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Alert Boxes -->
        <div id="success-alert" class="alert alert-success custom-alert" role="alert">
            <strong id="success-message"></strong>
        </div>
        <div id="danger-alert" class="alert alert-danger custom-alert" role="alert">
            <strong id="danger-message"></strong>
        </div>
        <!--  <div class="alert alert-success successalert" hidden>
            <strong id="messagebox"></strong>
        </div>
        <div class="alert alert-danger dangeralert" hidden>
            <strong id="messagebox"></strong>
        </div> -->





        <?php
        include("../includes/footer.php");
        include("../includes/script.php");
        ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const salesTodayTableBody = document.getElementById("salesTodayTableBody");
            const pagination = document.getElementById("pagination");
            const recordsPerPage = document.getElementById("recordsPerPage");
            const searchForm = document.getElementById("searchForm");
            const searchInput = document.getElementById("searchInput");
            const successAlert = document.getElementById("success-alert");
            const dangerAlert = document.getElementById("danger-alert");

            let limit = parseInt(recordsPerPage.value, 10);
            let page = 1;
            let search = '';

            searchForm.addEventListener('submit', function(event) {
                event.preventDefault();
                search = searchInput.value.trim();
                page = 1;
                fetchTodaySales();
            });

            recordsPerPage.addEventListener('change', function() {
                limit = parseInt(this.value, 10);
                page = 1;
                fetchTodaySales();
            });

            async function fetchTodaySales() {
                try {
                    const response = await fetch(`../api/sales/process.retrievetodaysales.php?limit=${limit}&offset=${(page - 1) * limit}&search=${encodeURIComponent(search)}`);
                    if (!response.ok) {
                        throw new Error("Could not retrieve sales data");
                    }
                    const data = await response.json();
                    if (data.success) {
                        displaySales(data.result);
                        setupPagination(data.total);
                    } else {
                        showAlert("danger",data.message);
                    }
                } catch (error) {
                    console.error(error);
                    showErrorMessage("An error occurred while fetching sales data.");
                }
            }

            function displaySales(sales) {
                salesTodayTableBody.innerHTML = '';
                if (sales.length > 0) {
                    sales.forEach(sale => {
                        const row = `
                            <tr>
                                <td>${sale.orderid}</td>
                                <td>${sale.customerid}</td>
                                <td>${sale.items}</td>
                                <td>${sale.totalquantity}</td>
                                <td>${sale.totalcost}</td>
                                <td>${sale.amountpaid}</td>
                                <td>${sale.paymentstatus}</td>
                                <td>
                                    <button class="btn btn-success btn-sm edit-sale" data-id="${sale.orderid}" ${disableEditButton(sale.paymentstatus, sale.amountpaid)}>Edit</button>
                                    <button class="btn btn-danger btn-sm delete-sale" data-id="${sale.orderid}">Delete</button>
                                </td>
                            </tr>
                        `;
                        salesTodayTableBody.innerHTML += row;
                    });
                } else {
                    salesTodayTableBody.innerHTML = '<tr><td colspan="8">No sales found</td></tr>';
                }
            }

            function setupPagination(totalItems) {
                pagination.innerHTML = '';
                const totalPages = Math.ceil(totalItems / limit);
                for (let i = 1; i <= totalPages; i++) {
                    const li = document.createElement("li");
                    li.className = `page-item ${i === page ? 'active' : ''}`;
                    li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                    li.addEventListener('click', (event) => {
                        event.preventDefault();
                        page = i;
                        fetchTodaySales();
                    });
                    pagination.appendChild(li);
                }
            }

            function disableEditButton(paymentStatus, amountPaid) {
                if (paymentStatus === 'partlypaid' || paymentStatus === 'fullypaid' || parseFloat(amountPaid) > 0) {
                    return 'disabled';
                } else {
                    return '';
                }
            }

           

            // Initial fetch of sales
            fetchTodaySales();
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
        });
    </script>
    </body>

</html>