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
            <div class="custom-form-container">
                <form id="orderForm">
                    <h2>Customer Information</h2>
                    <div class="form-group">
                        <label for="customerid">Customer ID</label>
                        <input type="text" class="form-control" id="customerid" name="customerid" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="customerid">Customer Name</label>
                        <select id="ordercustomer" name="customername">
                            <option value="">Select Name</option>
                            <option value="noname">No Name</option>
    
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="orderstatus">Order Status</label>
                        <input type="text" class="form-control" id="orderstatus" name="orderstatus" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="paymentstatus">Payment Status</label>
                        <input type="text" class="form-control" id="paymentstatus" name="paymentstatus" required readonly>
                    </div>

                    <h2>Order Items</h2>
                    <div id="orderItems">
                        <!-- Selected items will be displayed here -->
                    </div>
                    <div class="form-group text-center">
                        <button type="button" id="addItemBtn" class="btn btn-secondary">Add Item</button>
                    </div>

                    <h2>Payment Information</h2>

                    <h2>Summary</h2>
                    <div class="form-group">
                        <label for="totalcost">Total Cost</label>
                        <input type="number" step="0.01" class="form-control" id="totalcost" name="totalcost" readonly>
                    </div>
                    <div class="form-group">
                        <label for="amountowed">Amount Owed(includes 10% tax)</label>
                        <input type="number" step="0.01" class="form-control" id="amountowed" name="amountowed" readonly>
                    </div>
                    
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">Submit Order</button>
                    </div>
                </form>

                <div id="success-alert" class="alert alert-success custom-alert" role="alert">
                    <strong id="success-message"></strong>
                </div>
                <div id="danger-alert" class="alert alert-danger custom-alert" role="alert">
                    <strong id="danger-message"></strong>
                </div>
            </div>
        </div>
        <?php
        include("../includes/footer.php");
        include("../includes/script.php");
        ?>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                localStorage.clear();
                const msgbox = document.querySelector("#messagebox");
                const successAlert = document.querySelector(".alert-success");
                const dangerAlert = document.querySelector(".alert-danger");
                const customerid = document.getElementById("customerid");
                const orderstatus = document.getElementById("orderstatus");
                const paymentstatus = document.getElementById("paymentstatus");
                const selectcustomer=document.getElementById("ordercustomer");
                orderstatus.value = localStorage.getItem("orderstatus");
                customerid.value = localStorage.getItem("customerid");
               
                let fetchedItems = [];
                
                   
                
                async function addNewCustomer(customerid){
                try {
                    const customerval= customerid;
                    const response = await fetch("../api/customers/process.insertcustnull.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({customerid:customerval})
                    });

                    if (!response.ok) {
                        throw new Error("Could not add customer");
                    }

                    const data = await response.json();
                  
                    if (data.success) {
                        localStorage.setItem("customerid",data.customerid);
                        localStorage.setItem("orderstatus",data.orderstatus);
                        customerid.value=data.customerid;
                        customerid.innerHTML=data.customerid;
                       showAlert("success",data.message);


                    } else {
                    showAlert("danger",data.message);    
                    }
                } catch (error) {
                    console.error(error);
                    showAlert("danger",error)
                }
            
            }

                async function fetchItems() {
                    try {
                        const response = await fetch('../api/orders/process.retrieveitemprices.php', {
                            method: 'POST'
                        });
                        if (!response.ok) {
                            throw new Error("Could not process form");
                        }
                        const data = await response.json();
                        if (data.success) {
                            fetchedItems = data.items;
                        } else {
                            throw new Error(data.message);
                        }
                    } catch (error) {
                        console.error("Error fetching items:", error);
                    }
                }

                fetchItems();

                function updateSummary() {
                    const quantities = document.querySelectorAll('.quantity-input');
                    const prices = document.querySelectorAll('.price-input');
                    let totalCost = 0;
                    paymentstatus.value="unpaid";
                    localStorage.setItem("paymentstatus",paymentstatus.value);
                    quantities.forEach((quantityInput, index) => {
                        const quantity = parseFloat(quantityInput.value) || 0;
                        const price = parseFloat(prices[index].value) || 0;
                        totalCost += quantity * price;
                    });

                    const tax = totalCost * 0.1; // 10% tax
                    const amountOwed = totalCost + tax;
                   

                    document.getElementById('totalcost').value = totalCost.toFixed(2);
                    document.getElementById('amountowed').value = amountOwed.toFixed(2);
                   
                }

                function createItemSelect(itemCount) {
                    const select = document.createElement('select');
                    select.classList.add('form-control', 'item-select');
                    select.name = 'items[]';
                    select.required = true;
                    select.innerHTML = `<option value="">Select an item</option>`;
                    fetchedItems.forEach(item => {
                        select.innerHTML += `<option value="${item.fooditem}" data-price="${item.price}">${item.fooditem} - $${item.price}</option>`;
                    });
                    return select;
                }

                function addItemRow() {
                    const orderItems = document.getElementById('orderItems');
                    const itemCount = orderItems.querySelectorAll('.item-row').length + 1;

                    const itemRow = document.createElement('div');
                    itemRow.classList.add('form-row', 'item-row');
                    itemRow.innerHTML = `
                        <div class="form-group col-md-6">
                            <label for="item${itemCount}">Item</label>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="quantity${itemCount}">Quantity</label>
                            <input type="number" class="form-control quantity-input" min="0" id="quantity${itemCount}" name="quantities[]" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="price${itemCount}">Price</label>
                            <input type="number" step="0.01" class="form-control price-input" id="price${itemCount}" name="prices[]" readonly>
                        </div>
                        <div class="form-group col-md-2 align-self-end">
                            <button type="button" class="btn btn-danger remove-item">Remove</button>
                        </div>
                    `;

                    const itemSelect = createItemSelect(itemCount);
                    itemRow.querySelector('.form-group.col-md-6').appendChild(itemSelect);
                    orderItems.appendChild(itemRow);
                }

                document.getElementById('addItemBtn').addEventListener('click', addItemRow);

                document.getElementById('orderItems').addEventListener('change', function(event) {
                    if (event.target.classList.contains('item-select')) {
                        const priceInput = event.target.closest('.item-row').querySelector('.price-input');
                        const selectedItem = event.target.options[event.target.selectedIndex];
                        priceInput.value = selectedItem.getAttribute('data-price');
                    }
                    updateSummary();
                });

                document.getElementById('orderItems').addEventListener('click', function(event) {
                    if (event.target.classList.contains('remove-item')) {
                        event.target.closest('.item-row').remove();
                        updateSummary();
                    }
                });

                document.getElementById('orderForm').addEventListener('submit', async function(event) {
                    event.preventDefault();
                    const formData = new FormData(this);

                    const items = formData.getAll('items[]');
                    const quantities = formData.getAll('quantities[]');
                    const prices = formData.getAll('prices[]').map(price => parseFloat(price));

                    let totalCost = 0;
                    items.forEach((item, index) => {
                        totalCost += parseFloat(quantities[index]) * prices[index];
                    });

                    const tax = totalCost * 0.1; // 10% tax
                    const amountOwed = totalCost + tax;
                    totalCost=amountOwed;
                    

                    const formobj = {
                        customerid: localStorage.getItem("customerid"),
                        items: items,
                        quantities: quantities,
                        prices: prices,
                        totalcost: totalCost.toFixed(2),
                        tax: tax.toFixed(2),
                        amountowed: amountOwed.toFixed(2),
                       
                        orderstatus: localStorage.getItem("orderstatus"),
                        paymentstatus: paymentstatus.value
                    };

                    for (const key in formobj) {
                        if (formobj.hasOwnProperty(key)) {
                            localStorage.setItem(key, formobj[key]);
                        }
                    }

                    try {
                        const response = await fetch('../api/orders/processalltheorders.php', {
                            method: 'POST',
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify(formobj)
                        });
                        const data = await response.json();
                        if (data.success) {
                            localStorage.setItem('receiptnumber', data['receiptnumber']);
                           showAlert("success",data.message);
                            this.reset();
                            setTimeout(() => {
                                
                                window.location.href="orderstable.php"
                            }, 2000);
                        } else {
                           
                            showAlert("danger", data.message)
                            showAlert("danger",data.message.join("<br>")) ;
                        }
                    } catch (error) {
                        console.error(error);
                        dangerAlert.hidden = false;
                        dangerAlert.classList.remove("alert-success");
                        dangerAlert.classList.add("alert-danger");
                        msgbox.innerHTML = error.message;
                    }
                });

                document.getElementById('orderForm').addEventListener('input', updateSummary);
                async function retrieveCustomer() {
    try {
        const response = await fetch("../api/customers/process.retrievecustomers.php");
        if (!response.ok) {
            throw new Error("Could not retrieve item");
        }

        const data = await response.json();

        if (data.success) {
            // Clear existing options (if any) before adding new ones
           /// selectcustomer.innerHTML = '<option value="">Select Name</option>';

            // Loop through the customer data and create an option for each
            data.result.forEach(customer => {
                createOption(customer);
              
            });
          
        } else {
            // Show error message if retrieval was not successful
            console.log(data.message);
        }
    } catch (error) {
        // Catch and display any errors that occur during the fetch process
        console.error(error);
    }
}
 // Add an event listener to update customer ID input on selection change
 selectcustomer.addEventListener("change", () => {
        customerid.value = selectcustomer.value;
        orderstatus.value="pending";
        localStorage.setItem("orderstatus",orderstatus.value);
        // Optionally, store the selected customer ID in local storage
        localStorage.setItem("customerid", selectcustomer.value);
        if(customerid.value=="noname"){
            let custid= customerid.value;
            addNewCustomer(custid);
        }
       
    });

function createOption(customer) {
    const newOption = document.createElement("option");
    newOption.value = customer.customerid;
    newOption.text = `${customer.firstname} ${customer.lastname}`.toUpperCase();
    selectcustomer.add(newOption);
   // localStorage.setItem("customerid",customer.customerid)
   
}

// Call the function to retrieve and populate customer data
retrieveCustomer();

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
    </div>
</body>
</html>
