<?php
include("../includes/sessions.php");
require_once '../includes/configdb/Dbconnection.php';
require_once '../includes/configmenu/JoinMenus.php';
require_once '../includes/controlljoins/Viewjoined.php';
$title = "All Menus";
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isloggedin() || !isset($_SESSION['accounttype']) || ($_SESSION['accounttype'] !== "director" && $_SESSION['accounttype'] !== "staff")) {
    header("Location: ../auth/index.php");
    die();
}

if (isset($_SESSION['deletedmenuitem']) && $_SESSION['deletedmenuitem'] == true) {
    echo "<script>alert('Menu Type Successfully deleted')</script>";
    unset($_SESSION['deletedmenuitem']);
}
if (isset($_SESSION['errormenuitemdelete']) && $_SESSION['errormenuitemdelete'] == true) {
    echo "<script>alert('Failed to delete!')</script>";
    unset($_SESSION['errormenuitemdelete']);
}
if (isset($_SESSION['updatedmenuitem']) && $_SESSION['updatedmenuitem'] == true) {
    echo "<script>alert('User Successfully updated')</script>";
    unset($_SESSION['updatedmenuitem']);
}

// Fetch current page number from request, default to 1 if not provided
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$selectalljoinedmenus = new Viewjoined($limit,$offset,$search);

$total_menuall = $selectalljoinedmenus->totalMenuAllCount();
$total_pages = ceil($total_menuall / $limit);
$result = $selectalljoinedmenus->viewalljoined();
include("../includes/head.php");
?>

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
            <h3 class="text-dark mb-4"><?php echo $title ?></h3>
            <div class="card shadow">
                <div class="card-header py-3">
                    <p class="text-primary m-0 font-weight-bold"><?php echo $title ?> Info</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 text-nowrap">
                            <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable">
                                <label>Show&nbsp;
                                    <select class="form-control form-control-sm custom-select custom-select-sm" id="recordsPerPage">
                                        <option value="5" <?php if ($limit == 5) echo 'selected'; ?>>5</option>
                                        <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
                                        <option value="25" <?php if ($limit == 25) echo 'selected'; ?>>25</option>
                                        <option value="50" <?php if ($limit == 50) echo 'selected'; ?>>50</option>
                                    </select>&nbsp;
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-md-right dataTables_filter" id="dataTable_filter">
                                <form method="GET" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                    <input type="search" name="search" class="form-control form-control-sm" aria-controls="dataTable" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>">
                                    <input type="hidden" name="limit" value="<?php echo $limit; ?>">
                                    <input type="hidden" name="page" value="1">
                                    <button type="submit" class="btn btn-primary btn-sm">Search</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                        <table class="table my-0" id="dataTable">
                            <thead>
                                <tr>
                                    <th>Number</th>
                                    <th>Food Item</th>
                                    <th>Menu Type</th>                                 
                                    <th>Section Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Details</th>
                                    <th>Place Order</th>


                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($result)) {
                                    $num = $offset + 1;
                                    foreach ($result as $row) {
                                        $fooditem_id = htmlspecialchars($row['fooditem_id']);
                                        $type = htmlspecialchars($row['menu_type']);
                                        $section= htmlspecialchars($row['section_name']);
                                        $fooditem = htmlspecialchars($row['fooditem']);
                                        $price = htmlspecialchars($row['price']);
                                        $description = htmlspecialchars($row['itemdescription']);

                                       

                                ?>
                                        <tr>
                                            <td><?php echo $num++; ?></td>
                                            <td><h1><?php echo  $fooditem; ?></h1></td>
                                            <td><?php echo $type; ?></td>
                                            <td><?php echo  $section; ?></td>
                                            <td><?php echo   $description; ?></td>
                                            <td><?php echo  $price; ?></td> 
                                            <td><a href="../api/menus/process.displaymenu.php?id=<?php echo $fooditem_id; ?>" class="btn btn-success edit-button">Get Food Details/Edit</a></td>
                                       <td><a href="../orders/neworderprofile.php" class="btn btn-primary" >Place Order</a></td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>No data found</td></tr>";
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Number</th>
                                    <th>Item Name</th>
                                    <th>Menu Type</th>
                                    <th>Section Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Details</th>
                                    <th>Place Order</th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="row">
                            <div class="col-md-6 align-self-center">
                                <p id="dataTable_info" class="dataTables_info" role="status" aria-live="polite">
                                    Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $limit, $total_menuall); ?> of <?php echo $total_menuall; ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination">
                                        <li class="page-item <?php if ($page == 1) echo 'disabled'; ?>">
                                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&limit=<?php echo $limit; ?>&search=<?php echo htmlspecialchars($search); ?>" aria-label="Previous">
                                                <span aria-hidden="true">«</span>
                                            </a>
                                        </li>
                                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                            <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                                                <a class="page-link" href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>&search=<?php echo htmlspecialchars($search); ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?php if ($page == $total_pages) echo 'disabled'; ?>">
                                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&limit=<?php echo $limit; ?>&search=<?php echo htmlspecialchars($search); ?>" aria-label="Next">
                                                <span aria-hidden="true">»</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include("../includes/footer.php");
        include("../includes/script.php");
        ?>
    </div>
    <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    <script>
        document.getElementById('recordsPerPage').addEventListener('change', function() {
            const limit = this.value;
            window.location.href = `?limit=${limit}&page=1&search=<?php echo htmlspecialchars($search); ?>`;
        });
    </script>
</body>

</html>