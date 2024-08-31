<?php
$title = "Edit Orders";
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

    <div class="wrapper">
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
       
            <h1 class="mt-5">Edit Order</h1>
            <div class="custom-form-container">
                <?php
                // Include the PHP code for session check and order profile
                 if (!isset($_SESSION['orderinfo'])) {
                    // Redirect to another page if no order info is available
                    header("Location: orderstable.php");
                    exit();
                }

                Orderprofile();
                ?>
            </div>
     
        </div>
        <?php
    include("../includes/footer.php");
    include("../includes/script.php");
    ?>

    </div>

   
   
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const orderForm = document.getElementById('orderForm');
            const orderItems = document.getElementById('orderItems');
            const addItemBtn = document.getElementById('addItemBtn');
            const updateOrderBtn = document.getElementById('updateOrderBtn');

            function createItemRow(index) {
                return `
                    <div class='form-row item-row'>
                        <div class='form-group col-md-6'>
                            <label for='item${index}'>Item</label>
                            <input type='text' class='form-control' id='item${index}' name='items[${index}][item]' required>
                        </div>
                        <div class='form-group col-md-2'>
                            <label for='quantity${index}'>Quantity</label>
                            <input type='number' class='form-control quantity-input' id='quantity${index}' name='items[${index}][quantity]' min='1' required>
                        </div>
                        <div class='form-group col-md-2'>
                            <label for='price${index}'>Price</label>
                            <input type='number' step='0.01' class='form-control price-input' id='price${index}' name='items[${index}][price]' readonly>
                        </div>
                        <div class='form-group col-md-2 align-self-end'>
                            <button type='button' class='btn btn-danger remove-item'>Remove</button>
                        </div>
                    </div>
                `;
            }

            function addItemRow() {
                const itemCount = orderItems.querySelectorAll('.item-row').length;
                orderItems.insertAdjacentHTML('beforeend', createItemRow(itemCount));
            }

            function removeItemRow(event) {
                if (event.target.classList.contains('remove-item')) {
                    event.target.closest('.item-row').remove();
                }
            }

            async function updateOrder(event) {
                event.preventDefault();
                const formData = new FormData(orderForm);
                const formObj = {};
                formData.forEach((value, key) => {
                    if (key.includes('items')) {
                        const [_, index, field] = key.split('[');
                        const cleanIndex = index.replace(']', '');
                        const cleanField = field.replace(']', '');
                        formObj['items'] = formObj['items'] || {};
                        formObj['items'][cleanIndex] = formObj['items'][cleanIndex] || {};
                        formObj['items'][cleanIndex][cleanField] = value;
                    } else {
                        formObj[key] = value;
                    }
                });

                try {
                    const response = await fetch('update_order.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formObj)
                    });

                    const result = await response.json();
                    if (result.success) {
                        alert('Order updated successfully!');
                    } else {
                        alert('Failed to update order.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }

            addItemBtn.addEventListener('click', addItemRow);
            orderItems.addEventListener('click', removeItemRow);
            updateOrderBtn.addEventListener('click', updateOrder);
        });
    </script>
</body>

</html>
