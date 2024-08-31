<?php
$title = "Orders";
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


            <div class="container mt-5">
                <form id="orderForm">
                    <h2>Customer Information</h2>
                    <div class="form-group">
                        <label for="customerid">Customer ID</label>
                        <input type="text" class="form-control" id="customerid" name="customerid" required>
                    </div>

                    <h2>Order Items</h2>
                    <div id="orderItems">
                        <div class="form-group">
                            <label for="item1">Item</label>
                            <input type="text" class="form-control" id="item1" name="items[]" required>
                        </div>
                        <div class="form-group">
                            <label for="quantity1">Quantity</label>
                            <input type="number" class="form-control" id="quantity1" name="quantities[]" required>
                        </div>
                        <div class="form-group">
                            <label for="price1">Price</label>
                            <input type="number" step="0.01" class="form-control" id="price1" name="prices[]" required>
                        </div>
                    </div>
                    <button type="button" id="addItemBtn" class="btn btn-secondary">Add Item</button>

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

                    <h2>Summary</h2>
                    <div class="form-group">
                        <label for="totalcost">Total Cost</label>
                        <input type="number" step="0.01" class="form-control" id="totalcost" name="totalcost" readonly>
                    </div>
                    <div class="form-group">
                        <label for="amountowed">Amount Owed</label>
                        <input type="number" step="0.01" class="form-control" id="amountowed" name="amountowed" readonly>
                    </div>
                    <div class="form-group">
                        <label for="balance">Balance</label>
                        <input type="number" step="0.01" class="form-control" id="balance" name="balance" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit Order</button>
                </form>

                <div class="form-row">
                    <div class="container">
                        <div class="alert alert-success successalert " hidden>
                            <strong id="messagebox"></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include("../includes/footer.php");
        include("../includes/script.php");

        ?>

        <script>
           document.addEventListener("DOMContentLoaded", function() {
    const msgbox = document.querySelector("#messagebox");
    const alertbox = document.querySelector(".alert");
    const customerid = document.getElementById("customerid");
    customerid.value = localStorage.getItem("customerid");

    document.getElementById('addItemBtn').addEventListener('click', function() {
        const orderItems = document.getElementById('orderItems');
        const itemCount = orderItems.childElementCount / 3 + 1;

        const itemDiv = document.createElement('div');
        itemDiv.classList.add('form-group');
        itemDiv.innerHTML = `<label for="item${itemCount}">Item</label>
                             <input type="text" class="form-control" id="item${itemCount}" name="items[]" required>`;
        orderItems.appendChild(itemDiv);

        const quantityDiv = document.createElement('div');
        quantityDiv.classList.add('form-group');
        quantityDiv.innerHTML = `<label for="quantity${itemCount}">Quantity</label>
                                 <input type="number" class="form-control" id="quantity${itemCount}" name="quantities[]" required>`;
        orderItems.appendChild(quantityDiv);

        const priceDiv = document.createElement('div');
        priceDiv.classList.add('form-group');
        priceDiv.innerHTML = `<label for="price${itemCount}">Price</label>
                              <input type="number" step="0.01" class="form-control" id="price${itemCount}" name="prices[]" required>`;
        orderItems.appendChild(priceDiv);

        updateSummary();
    });

    document.getElementById('orderForm').addEventListener('input', function() {
        updateSummary();
    });

    document.getElementById('orderForm').addEventListener('submit', async function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        const items = formData.getAll('items[]');
        const quantities = formData.getAll('quantities[]');
        const prices = formData.getAll('prices[]');

        let totalCost = 0;
        items.forEach((item, index) => {
            totalCost += parseFloat(quantities[index]) * parseFloat(prices[index]);
        });

        const tax = totalCost * 0.1; // 10% tax
        const amountOwed = totalCost + tax;
        const amountPaid = parseFloat(formData.get('amountpaid'));
        const balance = amountOwed - amountPaid;

        const formobj = {
            customerid: localStorage.getItem("customerid"),
            items: items,
            quantities: quantities,
            prices: prices,
            totalcost: totalCost,
            tax: tax,
            amountowed: amountOwed,
            amountpaid: amountPaid,
            balance: balance,
            paymentmethod: formData.get('paymentmethod')
        };

        try {
            const response = await fetch('../api/orders/processalltheorders.php', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(formobj)
            });

            if (!response.ok) {
                throw new Error("Could not process form");
            }

            const data = await response.json();
            alertbox.hidden = false;
            if (data.success) {
                alertbox.classList.remove("alert-danger");
                alertbox.classList.add("alert-success");
                msgbox.innerHTML = data.message;
               this.reset();
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
    });
    function updateSummary() {
        const quantities = document.getElementsByName('quantities[]');
        const prices = document.getElementsByName('prices[]');
        let totalCost = 0;
        for (let i = 0; i < quantities.length; i++) {
            totalCost += parseFloat(quantities[i].value || 0) * parseFloat(prices[i].value || 0);
        }

        const tax = totalCost * 0.1; // 10% tax
        const amountOwed = totalCost + tax;
        const amountPaid = parseFloat(document.getElementById('amountpaid').value || 0);
        const balance = amountOwed - amountPaid;

        document.getElementById('totalcost').value = totalCost.toFixed(2);
        document.getElementById('amountowed').value = amountOwed.toFixed(2);
        document.getElementById('balance').value = balance.toFixed(2);
    }
});

        </script>

</body>

</html>