<?php
include("../includes/sessions.php");
require_once '../includes/configdb/Dbconnection.php';
require_once '../includes/configmenu/Menu.php';
require_once '../includes/mainmenucontroller/Selectmenu.php';
$title = "Menu Types";
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isloggedin() || !isset($_SESSION['accounttype']) || ($_SESSION['accounttype'] !== "director" && $_SESSION['accounttype'] !== "staff")) {
    header("Location: ../auth/index.php");
    die();
}

if (isset($_SESSION['deletedmenu']) && $_SESSION['deletedmenu'] == true) {
    echo "<script>
    alert('Menu Type Successfully deleted')</script>";
    unset($_SESSION['deletedmenu']);
}
if (isset($_SESSION['errormenudelete']) && $_SESSION['errormenudelete'] == true) {
    echo "<script>alert('Failed to delete!')</script>";
    unset($_SESSION['errormenudelete']);
}
if (isset($_SESSION['updatedmenu']) && $_SESSION['updatedmenu'] == true) {
    echo "<script>alert('Menu Type Successfully updated')</script>";
    unset($_SESSION['updatedmenu']);
}

// Fetch current page number from request, default to 1 if not provided
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$selectMenutype = new Selectmenu($limit, $offset, $search);

$total_menutype = $selectMenutype->totalMenuCount();
$total_pages = ceil($total_menutype / $limit);
$result = $selectMenutype->selectMenuWithLimit();
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
                                    <th>Type</th>
                                    <th>Description</th>

                                    <th>Time created</th>
                                    <th>Time Last Updated</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($result)) {
                                    $num = $offset + 1;
                                    foreach ($result as $row) {
                                        $id = htmlspecialchars($row['id']);
                                        $type = htmlspecialchars($row['type']);
                                        $description = htmlspecialchars($row['description']);

                                        $timecreated = htmlspecialchars($row['timecreated']);
                                        $timelastupdate = htmlspecialchars($row['timeupdated']);

                                ?>
                                        <tr>
                                            <td><?php echo $num++; ?></td>
                                            <td><?php echo $type; ?></td>
                                            <td><?php echo $description; ?></td>
                                            <td><?php echo $timecreated; ?></td>
                                            <td><?php echo $timelastupdate; ?></td>


                                            <td><a href="../api/menus/process.displaymenutype.php?id=<?php echo htmlspecialchars($id); ?>" class="btn btn-success edit-button">Edit</a></td>
                                            <td> <a href="../api/menus/process.deletemainmenu.php?id=<?php echo htmlspecialchars($id); ?>" class="btn btn-danger">Delete</a>
                                            </td>
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
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Time created</th>
                                    <th>Time Last Updated</th>
                                    <th>Action</th>

                                </tr>
                            </tfoot>
                        </table>
                        <div class="row">
                            <div class="col-md-6 align-self-center">
                                <p id="dataTable_info" class="dataTables_info" role="status" aria-live="polite">
                                    Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $limit, $total_menutype); ?> of <?php echo $total_menutype; ?>
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