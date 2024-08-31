<?php
$title = "Shop Settings";
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
            <h1 class="mb-4">Shops Available</h1>
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
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Shop ID</th>
                            <th>Shop Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Website</th>
                            <th>Address</th>
                            <th>Description</th>
                            <th>Country</th>
                            <th>State</th>
                            <th>Postal code</th>
                            <th>Opening Hours</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="shopTableBody">
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
            const shopTable = document.getElementById("shopTableBody");
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
                fetchShops();
            });

            recordsPerPage.addEventListener('change', function() {
                limit = parseInt(this.value, 10);
                page = 1;
                fetchShops();
            });

            async function fetchShops() {
                try {
                    const response = await fetch(`../api/shop/process.viewallshops.php?limit=${limit}&offset=${(page - 1) * limit}&search=${encodeURIComponent(search)}`);
                    if (!response.ok) {
                        throw new Error("Could not retrieve shops");
                    }

                    const data = await response.json();

                    if (data.success) {
                        shopTable.innerHTML = ''; // Clear existing rows
                        data.result.forEach(shop => {
                            addShopRow(shop);

                        });
                        setupPagination(data.total);
                    } else {
                        shopTable.innerHTML = '<tr><td colspan="10">No orders found</td></tr>';
                    }
                } catch (error) {
                    console.error(error);
                    shopTable.innerHTML = '<tr><td colspan="10">An error occurred</td></tr>';
                }
            }

         function addShopRow(shop) {
    const row = document.createElement("tr");
    const  isActive= shop.status ;
    let status;
    if(isActive==2){
    
    status='<button class="btn btn-primary btn-sm shop-status" data-id="${shop.id}" >Active</button>' ;
               
    }else{
        
        status='<button class="btn btn-danger btn-sm shop-status" data-id="${shop.id}" >Suspended</button>' ;
           
    }
    row.innerHTML = `
        <td>${shop.id}</td>
        <td>${shop.name}</td>
        <td>${shop.email}</td>
        <td>${shop.phone}</td>
        <td>${status}</td>
        <td>${shop.website}</td>
        <td>${shop.address}</td>
        <td>${shop.description}</td>
        <td>${shop.country}</td>
        <td>${shop.state}</td>
        <td>${shop.postal_code}</td>
        <td>${shop.opening_hours}</td>
        
        <td>
            <button class="btn btn-success btn-sm edit-shop" data-id="${shop.id}">Add/Edit</button>
            <button class="btn btn-danger btn-sm delete-shop" data-id="${shop.id}">Delete</button>
        </td> `;

    shopTable.appendChild(row);

    // Add event listeners for edit, delete, make payment, and print receipt buttons
    row.querySelector('.edit-shop').addEventListener('click', () => editShop(shop.id));
    row.querySelector('.delete-shop').addEventListener('click', () => deleteShop(shop.id));
    row.querySelector('.shop-status').addEventListener('click', () => {
        let statusValue= row.querySelector('.shop-status').innerHTML;
        
        ActivateShop(shop.id,statusValue)});

   

   
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

            async function editShop(shopId) {
                if (confirm(`Are you sure you want to edit order with ID: ${shopId}?`)) {
                    try {
                        const response = await fetch(`../api/shop/process.viewshop.php?id=${shopId}`, {
                            method: "GET"
                        });
                        if (!response.ok) {
                            throw new Error("Could not edit shop");
                        }
                        const data = await response.json();
                        if (data.success) {
                            showAlert("success", "shop details retrieved");
                            setTimeout(() => {
                                window.location.href = data.redirecturl;
                            }, 3000)
                        } else {
                            showAlert("danger", data.message);
                        }
                    } catch (error) {
                        console.error(error);
                        alert("An error occurred while editing the shop" + error);
                    }
                }
            }

            async function deleteShop(shopId) {
                if (confirm(`Are you sure you want to delete order with ID: ${shopId}?`)) {
                    try {
                        const response = await fetch(`../api/shop/process.deleteshop.php?id=${shopId}`, {
                            method: 'DELETE'
                        });
                        if (!response.ok) {
                            throw new Error("Could not delete order");
                        }
                        const data = await response.json();
                        if (data.success) {
                            showAlert("danger", data.message);
                            fetchShops(); // Refresh the orders list
                        } else {
                            showAlert("danger", data.message);
                        }
                    } catch (error) {
                        console.error(error);
                        alert("An error occurred while deleting the shop");
                    }
                }
            }

           
            async function ActivateShop(shopId,statusval) {
                let theStat;
                let theMsg;
                if(statusval=='Active'){
                 theMsg='suspend'
                 theStat=1;
                }else{
                    theMsg='activate';
                    theStat=2;
                }

                if (confirm(`Are you sure you want to ${theMsg} Shop for order with ID: ${shopId}?`)) {
                    try {
                        const response = await fetch(`../api/shop/process.updatestatus.php`, {
                            method: 'POST',
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                shopid: shopId,
                                status: theStat
                            })
                        });
                        if (!response.ok) {
                            throw new Error("Could not send orderid");
                        }
                        const data = await response.json();
                        if (data.success) {
                            
                            fetchShops();
                            showAlert("success", data.message);

                            
                           
                        } else {
                            showAlert("danger", data.message);
                             }
                    } catch (error) {
                        console.error(error);
                        alert("An error occurred while fetching the order");
                    }
                }
            }

            // Initial fetch of orders
            fetchShops();


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