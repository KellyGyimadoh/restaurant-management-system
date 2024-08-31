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
        if ($_SESSION['accounttype'] === "director") {
            include("../includes/auth.sidebar.php");
            include("../includes/auth.header.php");
        } elseif ($_SESSION['accounttype'] === "staff") {
            include("../includes/sidebar.php");
            include("../includes/header.php");
        } else {
            die();
        }
        ?>

        <div class="container-fluid">
            <div class="custom-form-container">
                <?php
                if (!isset($_SESSION['orderinfo'])) {
                    header("Location: orderstable.php");
                    exit();
                }

                Orderprofile();
                ?>
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
      document.addEventListener('DOMContentLoaded', async () => {
        const orderItems = document.getElementById('orderItems');
                const addItemBtn = document.getElementById('addItemBtn');
                const updateOrderBtn = document.getElementById('updateOrderBtn');
            fetchItems();
                let fetchedItems = [];

                async function fetchItems() {
                    try {
                        const response = await fetch('../api/orders/process.retrieveitemprices.php', {
                            method: 'POST'
                        });
                        if (!response.ok) {
                            throw new Error("Could not fetch items");
                        }
                        const data = await response.json();
                        if (data.success) {
                            fetchedItems = data.items;
                            populateItemOptions();
                        } else {
                            throw new Error(data.message);
                        }
                    } catch (error) {
                        console.error("Error fetching items:", error);
                    }
                }

                function populateItemOptions() {
                    const itemSelects = document.querySelectorAll('.item-select');
                    itemSelects.forEach(select => {
                        const currentItem = select.value;
                        select.innerHTML = `<option value=''>Select an item</option>`;
                        fetchedItems.forEach(item => {
                            const selected = currentItem === item.fooditem ? 'selected' : '';
                            select.innerHTML += `<option value='${item.fooditem}' data-price='${item.price}' ${selected}>${item.fooditem} - $${item.price}</option>`;
                        });
                        if (currentItem) {
                            select.value = currentItem;
                            const selectedOption = select.querySelector(`option[value='${currentItem}']`);
                            if (selectedOption) {
                                select.setAttribute('data-price', selectedOption.getAttribute('data-price'));
                            }
                        }
                    });
                }
                function updateSummary() {
                    const quantities = document.querySelectorAll('.quantity-input');
                    const prices = document.querySelectorAll('.price-input');
                    let totalCost = 0;
                    
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
                document.getElementById('orderForm').addEventListener('input', updateSummary);
              /*  function createItemSelect(itemCount) {
                    const select = document.createElement('select');
                    select.classList.add('form-control', 'item-select');
                    select.name = 'items[]';
                    select.required = true;
                    select.innerHTML = `<option value="">Select an item</option>`;
                    fetchedItems.forEach(item => {
                        select.innerHTML += `<option value="${item.fooditem}" data-price="${item.price}">${item.fooditem} - $${item.price}</option>`;
                    });
                    return select;
                } */
              
              
function createItemRow(index) {
    const row = document.createElement('div');
    row.classList.add('form-row', 'item-row');
    row.innerHTML = `
        <div class='form-group col-md-6'>
            <label for='item${index}'>Item</label>
            <select class='form-control item-select' id='item${index}' name='items[${index}][item]' required>
                <option value=''>Select an item</option>
                ${fetchedItems.map(item => `<option value='${item.fooditem}' data-price='${item.price}'>${item.fooditem} - $${item.price}</option>`).join('')}
            </select>
        </div>
        <div class='form-group col-md-2'>
            <label for='quantity${index}'>Quantity</label>
            <input type='number' class='form-control quantity-input' id='quantity${index}' name='items[${index}][quantity]' value='1' min='1' required>
        </div>
        <div class='form-group col-md-2'>
            <label for='price${index}'>Price</label>
            <input type='number' step='0.01' class='form-control price-input' id='price${index}' name='items[${index}][price]' readonly>
        </div>
        <div class='form-group col-md-2 align-self-end'>
            <button type='button' class='btn btn-danger remove-new-item'>Remove</button>
        </div>
    `;

    // Add event listener to update price input when item selection changes
    const itemSelect = row.querySelector(`#item${index}`);
    itemSelect.addEventListener('change', () => {
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        const priceInput = row.querySelector(`#price${index}`);
        if (selectedOption) {
            priceInput.value = selectedOption.getAttribute('data-price');
        } else {
            priceInput.value = '0';
        }
        updateTotalCost(); // Update total cost whenever price changes
    });

    // Initialize price input with default item's price
    const defaultOption = itemSelect.options[itemSelect.selectedIndex];
    const priceInput = row.querySelector(`#price${index}`);
    if (defaultOption) {
        priceInput.value = defaultOption.getAttribute('data-price');
    }

    return row;
}

function addItemRow() {
    const orderItems = document.getElementById('orderItems');
    const itemCount = orderItems.querySelectorAll('.item-row').length + 1;

    const itemRow = createItemRow(itemCount);
    orderItems.appendChild(itemRow);
}
                


                async function removeItem(event) {
                    const row = event.target.closest('.item-row');
                    if (!row) return; // Ensure row exists

                    if (event.target.classList.contains('remove-new-item')) {
                        row.remove();
                        updateTotalCost();
                    } else if (event.target.classList.contains('remove-existing-item')) {
                        const itemNameElement = row.querySelector('.item-input');
                        if (!itemNameElement) return; // Ensure item select element exists

                        const itemName = itemNameElement.value;
                        if (!itemName) return; // Ensure item name is valid

                        try {
                            const response = await fetch('../api/orders/process.removeorderitem.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    item: itemName,
                                    orderid: document.getElementById('orderid').value
                                })
                            });
                            if (!response.ok) {
                                throw new Error("Could not process form");
                            }
                            const data = await response.json();
                            if (data.success) {
                                showAlert("success", data.message);
                                row.remove();
                                updateTotalCost();
                                updateSummary();
                            } else {
                                throw new Error(data.message);
                            }
                        } catch (error) {
                            console.error("Error removing order:", error);
                            showAlert("danger", error.message);
                        }
                    }
                }

                function updateTotalCost() {
                    let total = 0;
                    const rows = orderItems.querySelectorAll('.item-row');
                    rows.forEach(row => {
                        const quantity = parseInt(row.querySelector('.quantity-input').value, 10);
                        const price = parseFloat(row.querySelector('.price-input').value);
                        total += quantity * price;
                    });
                    document.getElementById('totalcost').value = total.toFixed(2);
                    document.getElementById('amountowed').value = (total * 1.1).toFixed(2);
                }

                async function updateOrder() {
                    const items = [];
                    orderItems.querySelectorAll('.item-row').forEach(row => {
                        const itemSelect = row.querySelector('.item-select');
                        const quantityInput = row.querySelector('.quantity-input');
                        const priceInput = row.querySelector('.price-input');

                        if (itemSelect && quantityInput && priceInput && itemSelect.value) {
                            items.push({
                                fooditem: itemSelect.value,
                                itemnumber: parseInt(quantityInput.value, 10),
                                price: parseFloat(priceInput.value)
                            });
                        }
                    });

                    const orderData = {
                        orderid: document.getElementById('orderid').value,
                        totalcost: document.getElementById('totalcost').value,
                        amountowed: document.getElementById('amountowed').value,
                        items: items
                    };

                    try {
                        const response = await fetch('../api/orders/process.updateorder.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(orderData)
                        });

                        if (!response.ok) {
                            throw new Error("Could not process form");
                        }

                        const data = await response.json();

                        if (data.success) {
                            showAlert("success", data.message);
                            setTimeout(()=>{
                                window.location.href="orderstable.php";
                            },3000)
                        } else {
                            throw new Error(data.message);
                        }
                    } catch (error) {
                        console.error("Error updating order:", error);
                        showAlert("danger", error.message);
                    }
                }
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

    addItemBtn.addEventListener('click', addItemRow);
    orderItems.addEventListener('click', removeItem);
    updateOrderBtn.addEventListener('click', updateOrder);

 // Fetch items initially
});

        </script>

    </div>
</body>

</html>
