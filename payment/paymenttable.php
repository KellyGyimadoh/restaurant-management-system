<?php
$title = "Orders";
include("../includes/sessions.php");
include("../includes/head.php");

if (!isloggedin() || !isset($_SESSION['accounttype']) || ($_SESSION['accounttype'] !== "director" && $_SESSION['accounttype'] !== "staff")) {
    header("Location: ../auth/index.php");
    die();
}
?>

<style>
    /* Custom Styles */
    .custom-alert {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        display: none;
    }

    .form-section {
        margin-bottom: 20px;
    }

    .form-section h2 {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: bold;
    }

    .card {
        margin-bottom: 20px;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .alert {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        display: none;
    }
</style>
</head>

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
            <div class="custom-form-container">
                <form id="paymentForm">
                    <!-- Form Contents -->
                    <div class="card form-section">
                        <h2>Payment</h2>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="customerid">Customer ID</label>
                                <input type="text" class="form-control" id="customerid" name="customerid" required readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="orderid">Order ID</label>
                                <input type="text" class="form-control" id="orderid" name="orderid" required readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="paymentid">Payment ID</label>
                                <input type="text" class="form-control" id="paymentid" name="paymentid" required readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="receiptnumber">Receipt Number</label>
                                <input type="text" class="form-control" id="receiptnumber" name="receiptnumber" required readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="orderdate">Order Date</label>
                                <input type="text" class="form-control" id="orderdate" name="orderdate" required readonly>
                            </div>
                        </div>
                    </div>

                    <div class="card form-section">
                        <h2>Order Items</h2>
                        <div id="orderItems">
                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <label for="fooditem">Item</label>
                                    <input type="text" class="form-control item-input" id="fooditem" name="fooditem" readonly required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" class="form-control quantity-input" id="quantity" name="quantity" readonly required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card form-section">
                        <div class="form-group">
                            <label for="paymentstatus">
                                <h3 style="text-align: center;">Payment Status</h3>
                            </label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="paymentstatus" id="unpaid" value="unpaid" required>
                                <label class="form-check-label" for="unpaid">Unpaid</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="paymentstatus" id="partlypaid" value="partlypaid" required>
                                <label class="form-check-label" for="partlypaid">Partly Paid</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="paymentstatus" id="fullypaid" value="fullypaid" required>
                                <label class="form-check-label" for="fullypaid">Fully Paid</label>
                            </div>
                            <h3 id="paymentstat" style="text-align: center;"></h3>
                        </div>
                    </div>

                    <div class="card form-section">
                        <h2>Payment Information</h2>
                        <div class="form-group">
                            <label for="amountpaid">Amount Paid</label>
                            <input type="number" step="0.01" class="form-control" id="amountpaid" name="amountpaid" required>
                        </div>

                        <div class="form-group">
                            <label>Payment Method</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="paymentmethod" id="cash" value="cash" required>
                                <label class="form-check-label" for="cash">Cash</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="paymentmethod" id="credit" value="credit" required>
                                <label class="form-check-label" for="credit">Credit Card</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="paymentmethod" id="mobile" value="mobile" required>
                                <label class="form-check-label" for="mobile">Mobile Payment</label>
                            </div>
                        </div>
                    </div>

                    <div class="card form-section">
                        <h2>Summary</h2>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="totalcost">Total Cost</label>
                                <input type="number" step="0.01" class="form-control" id="totalcost" name="totalcost" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="amountowed">Amount Owed (includes 10% tax)</label>
                                <input type="number" step="0.01" class="form-control" id="amountowed" name="amountowed" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="balance">Balance</label>
                                <input type="number" step="0.01" class="form-control" id="balance" name="balance" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">Make Payment</button>
                    </div>
                </form>

                <div id="success-alert" class="alert alert-success custom-alert" role="alert">
                    <strong id="success-message"></strong>
                </div>
                <div id="danger-alert" class="alert alert-danger custom-alert" role="alert">
                    <strong id="danger-message"></strong>
                </div>
            </div>
        </div> <?php
                include("../includes/footer.php");
                include("../includes/script.php");
                ?>

    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const successAlert = document.querySelector(".alert-success");
            const dangerAlert = document.querySelector(".alert-danger");
            const customerid = document.getElementById("customerid");
            const paymentid = document.getElementById("paymentid");
            const orderid = document.getElementById("orderid");
            const receiptnumber = document.getElementById("receiptnumber");
            const totalcost = document.getElementById("totalcost");
            const amountowed = document.getElementById("amountowed");
            const paystatus = document.getElementById("paymentstat");
            const orderdate = document.getElementById("orderdate");
            const fooditem = document.getElementById("fooditem");
            const quantity = document.getElementById("quantity");
            const amountpaidInput = document.getElementById("amountpaid");

            // Load values from localStorage
            fooditem.value = localStorage.getItem("fooditem");
            quantity.value = localStorage.getItem("quantity");
            customerid.value = localStorage.getItem("customerid");
            paymentid.value = localStorage.getItem("paymentid");
            orderid.value = localStorage.getItem("orderid");
            receiptnumber.value = localStorage.getItem("receiptnumber");
            paystatus.innerHTML = localStorage.getItem("paymentstatus");
            totalcost.value = localStorage.getItem("totalcost");
            amountowed.value = localStorage.getItem("amountowed");
            orderdate.value = localStorage.getItem("orderdate");

            function updateSummary() {
                const balance = document.getElementById("balance");
                const amountpaid = parseFloat(amountpaidInput.value) || 0;
                const amountowedValue = parseFloat(amountowed.value) || 0;

                if (isNaN(amountpaid) || isNaN(amountowedValue)) {
                    console.error("Invalid amount paid or amount owed value");
                    return;
                }

                const calculatedBalance = (amountowedValue - amountpaid).toFixed(2);
                balance.value = calculatedBalance;
                localStorage.setItem("balance", calculatedBalance);
            }

            document.getElementById('paymentForm').addEventListener('input', updateSummary);

            document.getElementById('paymentForm').addEventListener('submit', async function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                const amountPaid = parseFloat(formData.get('amountpaid')).toFixed(2);
                const balance = parseFloat(localStorage.getItem("balance")).toFixed(2);
                const paymentmethod = document.querySelector('input[name="paymentmethod"]:checked').value;
                const paymentstatus = document.querySelector('input[name="paymentstatus"]:checked').value;

                const formobj = {
                    customerid: localStorage.getItem("customerid"),
                    amountpaid: parseFloat(amountPaid), // Ensure amountpaid is sent as a float with two decimal places
                    orderstatus: localStorage.getItem("orderstatus"),
                    paymentstatus: paymentstatus,
                    orderid: orderid.value,
                    balance: parseFloat(balance), // Ensure balance is sent as a float with two decimal places
                    paymentmethod: paymentmethod,
                    amountowed: parseFloat(amountowed.value), // Ensure amountowed is sent as a float with two decimal places
                    orderreceipt:receiptnumber.value,
                    paymentid: paymentid.value
                };

                for (const key in formobj) {
                    if (formobj.hasOwnProperty(key)) {
                        localStorage.setItem(key, formobj[key]);
                    }
                }

                try {
                    const response = await fetch('../api/payment/process.makepayment.php', {
                        method: 'POST',
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(formobj)
                    });
                    const data = await response.json();
                    if (data.payment.success) {
                        showAlert("success", data.payment.message);
                        showAlert("success", data.order.message);

                        this.reset();
                        setTimeout(() => {
                            window.open("transaction.php", "_blank");
                        }, 2000);
                    } else {
                        showAlert("danger", data.payment.message);
                    }

                } catch (error) {
                    console.error(error);
                    dangerAlert.style.display = 'block';
                    dangerAlert.innerHTML = error.message;
                }
            });

            function showAlert(type, message) {
                let alertBox, messageBox, soundFile;
                if (type === 'success') {
                    alertBox = document.getElementById('success-alert');
                    messageBox = document.getElementById('success-message');
                    soundFile = 'success-sound.mp3'; // Add path to your success sound file
                } else if (type === 'danger') {
                    alertBox = document.getElementById('danger-alert');
                    messageBox = document.getElementById('danger-message');
                    soundFile = 'failure-sound.mp3'; // Add path to your failure sound file
                }

                messageBox.textContent = message;
                alertBox.style.display = 'block';

                let audio = new Audio(soundFile);
                audio.play();

                setTimeout(() => {
                    alertBox.style.display = 'none';
                }, 3000);
            }
        });
    </script>
</body>

</html>