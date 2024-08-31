<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .receipt {
            max-width: 400px;
            margin: auto;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .receipt h2, .receipt h3 {
            text-align: center;
        }
        .receipt .details {
            margin-bottom: 20px;
        }
        .receipt .transaction {
            border-top: 1px solid #ccc;
            padding-top: 10px;
            margin-top: 10px;
        }
        .receipt .transaction .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .receipt .actions {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <h2>Receipt Summary</h2>
        <h3>Receipt Number: <span id="receipt-number"></span></h3>
        <div class="details">
            <p><strong>Customer ID:</strong> <span id="customer-id"></span></p>
            <p><strong>Date:</strong> <span id="date"></span></p>
        </div>
        <div class="transactions">
            <!-- Transactions will be dynamically added here -->
        </div>
        <p><strong>Total Amount Paid:</strong> <span id="total-paid"></span></p>
        <p><strong>Total Amount Owed:</strong> <span id="total-owed"></span></p>
        <p><strong>Balance:</strong> <span id="balance"></span></p>
        <div class="actions">
            <button onclick="printReceipt()">Print Receipt</button>
        </div>
    </div>

    <script>
        // Retrieve the receipt number from the URL query parameter
        const urlParams = new URLSearchParams(window.location.search);
        const receiptNumber = urlParams.get('receiptNumber');

        if (receiptNumber) {
            fetch(`../api/getOrderDetails.php?receiptnumber=${receiptNumber}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const orderDetails = data.orderDetails[0]; // Assuming all relevant details are in the first entry

                        document.getElementById('customer-id').textContent = orderDetails.customerid;
                        document.getElementById('date').textContent = new Date(orderDetails.paymentdate).toLocaleDateString();
                        document.getElementById('total-paid').textContent = `$${parseFloat(orderDetails.amountpaid).toFixed(2)}`;
                        document.getElementById('total-owed').textContent = `$${parseFloat(orderDetails.amountowed).toFixed(2)}`;
                        document.getElementById('balance').textContent = `$${parseFloat(orderDetails.balance).toFixed(2)}`;
                        document.getElementById('receipt-number').textContent = orderDetails.receiptnumber;

                        const transactionsDiv = document.querySelector('.transactions');
                        data.orderDetails.forEach(detail => {
                            const transactionDiv = document.createElement('div');
                            transactionDiv.classList.add('transaction');
                            let itemsHTML = `
                                <div class="item"><span>${detail.fooditemid}</span><span>$${parseFloat(detail.price).toFixed(2)}</span></div>
                            `;
                            transactionDiv.innerHTML = `
                                <p><strong>Transaction ID:</strong> ${detail.orderid}</p>
                                ${itemsHTML}
                                <p><strong>Total:</strong> $${parseFloat(detail.itemtotal).toFixed(2)}</p>
                            `;
                            transactionsDiv.appendChild(transactionDiv);
                        });
                    } else {
                        console.error('Failed to fetch order details:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching order details:', error);
                });
        } else {
            console.error('No receipt number found in URL.');
        }

        // Print receipt function
        function printReceipt() {
            window.print();
        }

        // Clear relevant local storage items
localStorage.removeItem('customerid');
localStorage.removeItem('transactions');
localStorage.removeItem('amountpaid');
localStorage.removeItem('amountowed');
localStorage.removeItem('balance');
localStorage.removeItem('receiptNumber');

    </script>
</body>
</html>
