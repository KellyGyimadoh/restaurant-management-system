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
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f9f9f9;
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
        .details, .transactions, .totals {
            margin-bottom: 20px;
            text-align: left;
        }
        .transactions .transaction {
            border-top: 1px solid #ccc;
            padding-top: 10px;
            margin-top: 10px;
        }
        .totals p, .details p {
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
  

<body>
    <div class="receipt-container">
        <h2>Receipt Summary</h2>
        <h3>Payment Receipt Number: <span id="paymentreceipt-number"></span></h3>
        <h3>Order Receipt Number: <span id="receipt-number"></span></h3>
        <div class="details">
            <p><strong>Customer ID:</strong> <span id="customer-id"></span></p>
            <p><strong>Date:</strong> <span id="date"></span></p>
        </div>
        <div class="transactions">
            <!-- Transactions will be dynamically added here -->
        </div>
        <div class="totals">
            <p><strong>Total Amount Paid:</strong> <span id="total-paid"></span></p>
            <p><strong>Total Amount Owed:</strong> <span id="total-owed"></span></p>
            <p><strong>Balance:</strong> <span id="balance"></span></p>
        </div>
        <div class="actions">
            <button onclick="printReceipt()">Print Receipt</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            // Retrieve the receipt number from local storage
            const receiptNumber = localStorage.getItem('receiptnumber');

            if (receiptNumber) {
                // Set the receipt number in the DOM
                document.getElementById('receipt-number').textContent = receiptNumber;

                // Fetch order details
                await fetchOrderDetails(receiptNumber);
            } else {
                console.error('Receipt number not found in local storage.');
            }
        });

        async function fetchOrderDetails(receiptNumber) {
            try {
                const response = await fetch('../api/orders/process.receiptnumber.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ receiptnumber: receiptNumber })
                });

                const data = await response.json();

                if (data.success) {
                    const details = data.orderDetails[0]; // Assuming the first item contains the details
                    document.getElementById('customer-id').textContent = details.customerid;
                    document.getElementById('date').textContent = details.paymentdate;
                    document.getElementById('total-paid').textContent = details.amountpaid;
                    document.getElementById('total-owed').textContent = details.amountowed;
                    document.getElementById('balance').textContent = details.balance;
                    document.getElementById('paymentreceipt-number').textContent = details.paymentreceipt

                    const transactionsContainer = document.querySelector('.transactions');
                    data.orderDetails.forEach(item => {
                        const transactionDiv = document.createElement('div');
                        transactionDiv.className = 'transaction';

                        const itemName = document.createElement('p');
                        itemName.className = 'item';
                        itemName.textContent = `Food Item ID: ${item.fooditemid}, Quantity: ${item.itemnumber}, Price: ${item.price}, Tax: ${item.tax}, Total: ${item.itemtotal}`;

                        transactionDiv.appendChild(itemName);
                        transactionsContainer.appendChild(transactionDiv);
                    });
                    localStorage.clear();
                } else {
                    console.error('Failed to fetch order details:', data.message);
                }
            } catch (error) {
                console.error('Error fetching order details:', error);
            }
        }
        
        function printReceipt() {
            window.print();
        }
    </script>
</body>
</html>
