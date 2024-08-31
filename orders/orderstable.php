<?php
$title = "Order table";
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
            <h1 class="mb-4">Customer Orders</h1>
            <div class="card shadow">
                <div class="card-header py-3">
                    <p class="text-primary m-0 font-weight-bold">Customer Orders</p>
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
                                    <th>Customer Name</th>
                                    <th>Order ID</th>
                                    <th>Customer ID</th>
                                    <th>Items</th>
                                    <th>Total Quantity</th>
                                    <th>Total Cost</th>
                                    <th>Amount Owed</th>
                                    <th>Payment Status</th>
                                    <th>Order Status</th>
                                    <th>Amount Paid</th>
                                    <th>Actions</th>
                                    <th>Make Payment</th>
                                </tr>
                            </thead>
                            <tbody id="orderTableBody">
                                <!-- Orders will be inserted here -->
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

            const msgbox = document.querySelector("#messagebox");
            const successAlert = document.querySelector(".alert-success");
            const dangerAlert = document.querySelector(".alert-danger");
            const orderTable = document.getElementById("orderTableBody");
            const pagination = document.getElementById("pagination");
            const recordsPerPage = document.getElementById("recordsPerPage");
            const searchForm = document.getElementById("searchForm");
            const searchInput = document.getElementById("searchInput");

            let limit = parseInt(recordsPerPage.value, 10);
            let page = 1;
            let search = '';

            searchForm.addEventListener('submit', function(event) {
                event.preventDefault();
                search = searchInput.value.trim();
                page = 1;
                fetchOrders();
            });

            recordsPerPage.addEventListener('change', function() {
                limit = parseInt(this.value, 10);
                page = 1;
                fetchOrders();
            });

            async function fetchOrders() {
                try {
                    const response = await fetch(`../api/orders/process.retrievejoinedorders.php?limit=${limit}&offset=${(page - 1) * limit}&search=${encodeURIComponent(search)}`);
                    if (!response.ok) {
                        throw new Error("Could not retrieve orders");
                    }

                    const data = await response.json();

                    if (data.success) {
                        orderTable.innerHTML = ''; // Clear existing rows
                        data.result.forEach(order => {
                            addOrderRow(order);

                        });
                        setupPagination(data.total);
                    } else {
                        orderTable.innerHTML = '<tr><td colspan="10">No orders found</td></tr>';
                    }
                } catch (error) {
                    console.error(error);
                    orderTable.innerHTML = '<tr><td colspan="10">An error occurred</td></tr>';
                }
            }

            function addOrderRow(order) {
                const row = document.createElement("tr");

                row.innerHTML = `
        <td>${order.customerfirstname} ${order.customerlastname}</td>
        <td>${order.orderid}</td>
        <td>${order.customerid}</td>
        <td>${order.items}</td>
        <td>${order.totalquantity}</td>
        <td>${order.totalcost}</td>
        <td>${order.amountowed}</td>
        <td>${order.paymentstatus}</td>
        <td>${order.orderstatus}</td>
        <td>${order.amountpaid}</td>
        <td>
            <button class="btn btn-success btn-sm edit-order" data-id="${order.orderid}" ${disableEditButton(order.paymentstatus, order.amountpaid)}>Add/Edit</button>
            <button class="btn btn-danger btn-sm delete-order" data-id="${order.orderid}">Delete</button>
        </td>
        <td>
            ${(order.paymentstatus === 'partlypaid' || order.paymentstatus === 'unpaid') || parseFloat(order.amountpaid) < parseFloat(order.amountowed) ?
                `<button class="btn btn-primary btn-sm make-payment" data-id="${order.orderid}">Make Payment</button>` :
                `<button class="btn btn-info btn-sm print-receipt" data-id="${order.orderid}">Print Receipt</button>`}
        </td>
    `;

                orderTable.appendChild(row);

                // Add event listeners for edit, delete, make payment, and print receipt buttons
                row.querySelector('.edit-order').addEventListener('click', () => editOrder(order.orderid));
                row.querySelector('.delete-order').addEventListener('click', () => deleteOrder(order.orderid));

                // Check if make payment button exists before adding event listener
                const makePaymentButton = row.querySelector('.make-payment');
                if (makePaymentButton) {
                    makePaymentButton.addEventListener('click', () => makePayment(order.orderid));
                }

                // Check if print receipt button exists before adding event listener
                const printReceiptButton = row.querySelector('.print-receipt');
                if (printReceiptButton) {
                    printReceiptButton.addEventListener('click', () => printReceipt(order.orderid));
                }
            }

            function disableEditButton(paymentStatus, amountPaid) {
                if (paymentStatus === 'partlypaid' || paymentStatus === 'fullypaid' || parseFloat(amountPaid) > 0) {
                    return 'disabled';
                } else {
                    return '';
                }
            }





            async function printReceipt(orderId) {
                // Implement functionality to print receipt based on order ID
                try {
                    const response = await fetch(`../api/payment/process.viewpaymentstatus.php`, {
                        method: 'POST',
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            orderid: orderId
                        })
                    });
                    if (!response.ok) {
                        throw new Error("Could not fetch order receipt");
                    }
                    const data = await response.json();
                    if (data.success) {
                        localStorage.setItem("receiptnumber", data.result.receiptnumber);
                        localStorage.setItem("paymentid", data.result.paymentid);
                        localStorage.setItem("paymentstatus", data.result.paymentstatus);
                        localStorage.setItem("orderid", data.result.orderid);
                        localStorage.setItem("orderdate", data.orderdetails.orderdate);
                        localStorage.setItem("orderstatus", data.orderdetails.orderstatus);
                        localStorage.setItem("totalcost", data.orderdetails.totalcost);
                        localStorage.setItem("amountowed", data.orderdetails.amountowed);
                        localStorage.setItem("customerid", data.orderdetails.customerid);
                        localStorage.setItem("fooditem", data.orderitemdetails.items);
                        localStorage.setItem("quantity", data.orderitemdetails.totalquantity);


                        showAlert("success", "Receipt retrieved");
                        setTimeout(() => {
                            window.open(`../payment/transaction.php`);
                        }, 3000)
                    } else {
                        showAlert("danger", data.message);
                    }
                } catch (error) {
                    console.error(error);
                    alert("An error occurred while processing receipt the order" + error);
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
                        fetchOrders();
                    });
                    pagination.appendChild(li);
                }
            }

            async function editOrder(orderId) {
                if (confirm(`Are you sure you want to edit order with ID: ${orderId}?`)) {
                    try {
                        const response = await fetch(`../api/orders/process.vieworder.php?id=${orderId}`, {
                            method: "GET"
                        });
                        if (!response.ok) {
                            throw new Error("Could not edit order");
                        }
                        const data = await response.json();
                        if (data.success) {
                            showAlert("success", "Order details retrieved");
                            setTimeout(() => {
                                window.location.href = data.redirecturl;
                            }, 3000)
                        } else {
                            showAlert("danger", data.message);
                        }
                    } catch (error) {
                        console.error(error);
                        alert("An error occurred while editing the order" + error);
                    }
                }
            }

            async function deleteOrder(orderId) {
                if (confirm(`Are you sure you want to delete order with ID: ${orderId}?`)) {
                    try {
                        const response = await fetch(`../api/orders/process.deleteorder.php?id=${orderId}`, {
                            method: 'DELETE'
                        });
                        if (!response.ok) {
                            throw new Error("Could not delete order");
                        }
                        const data = await response.json();
                        if (data.success) {
                            showAlert("danger", data.message);
                            fetchOrders(); // Refresh the orders list
                        } else {
                            showAlert("danger", data.message);
                        }
                    } catch (error) {
                        console.error(error);
                        alert("An error occurred while deleting the order");
                    }
                }
            }

            async function makePayment(orderId) {
                if (confirm(`Are you sure you want to make Payment for order with ID: ${orderId}?`)) {
                    try {
                        const response = await fetch(`../api/payment/process.viewpaymentstatus.php`, {
                            method: 'POST',
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                orderid: orderId
                            })
                        });
                        if (!response.ok) {
                            throw new Error("Could not send orderid");
                        }
                        const data = await response.json();
                        if (data.success) {
                            localStorage.setItem("receiptnumber", data.result.receiptnumber);
                            localStorage.setItem("paymentid", data.result.paymentid);
                            localStorage.setItem("paymentstatus", data.result.paymentstatus);
                            localStorage.setItem("orderid", data.result.orderid);
                            localStorage.setItem("orderdate", data.orderdetails.orderdate);
                            localStorage.setItem("orderstatus", data.orderdetails.orderstatus);
                            localStorage.setItem("totalcost", data.orderdetails.totalcost);
                            localStorage.setItem("amountowed", data.orderdetails.amountowed);
                            localStorage.setItem("customerid", data.orderdetails.customerid);
                            localStorage.setItem("fooditem", data.orderitemdetails.items);
                            localStorage.setItem("quantity", data.orderitemdetails.totalquantity);


                            showAlert("success", data.message);
                            // successAlert.hidden = false;
                            // successAlert.classList.remove("alert-danger");
                            // successAlert.classList.add("alert-success");
                            // msgbox.innerHTML = data.message;

                            setTimeout(() => {
                                // successAlert.hidden = true;
                                window.location.href = "../payment/paymenttable.php";
                            }, 5000);
                        } else {
                            showAlert("danger", data.message);
                            // dangerAlert.hidden = false;
                            // dangerAlert.classList.remove("alert-success");
                            // dangerAlert.classList.add("alert-danger");
                            //msgbox.innerHTML = Array.isArray(data.message) ? data.message.join("<br>") : data.message;
                        }
                    } catch (error) {
                        console.error(error);
                        alert("An error occurred while fetching the order");
                    }
                }
            }

            // Initial fetch of orders
            fetchOrders();


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

            // Example Usage:
            // showAlert('success', 'Operation completed successfully!');
            // showAlert('danger', 'There was an error processing your request.');



        });
    </script>
</body>

</html>