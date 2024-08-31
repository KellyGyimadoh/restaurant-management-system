<?php
include("../includes/sessions.php");
$title = "Employees";
require_once '../includes/configdb/Dbconnection.php';
require_once '../includes/configdb/Selectusers.php';
include("../includes/head.php");

if (!isloggedin() && !isset($_SESSION['accounttype']) && $_SESSION['accounttype'] !== "director") {
    header("Location:../auth/index.php");
    die();
}

if (isset($_SESSION['deleted']) && $_SESSION['deleted'] == true) {
    echo "<script>alert('User Successfully deleted')</script>";
    unset($_SESSION['deleted']);
}
if (isset($_SESSION['errordelete']) && $_SESSION['errordelete'] == true) {
    echo "<script>alert('Failed to delete!')</script>";
    unset($_SESSION['errordelete']);
}

// Fetch current page number from request, default to 1 if not provided
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$accounttype = htmlspecialchars("director");
$viewuser = new Selectusers();

$total_users = $viewuser->getTotalWorkerTypeCount(htmlspecialchars($accounttype));
$total_pages = ceil($total_users / $limit);
$result = $viewuser->viewWorkerTypeWithLimit($limit, $offset, $search, $accounttype);


?>

<body id="page-top">
    <div id="wrapper">
        <?php
        include("../includes/auth.sidebar.php");
        include("../includes/auth.header.php");
        ?>
        <div class="container-fluid">
            <h3 class="text-dark mb-4">Admins</h3>
            <div class="card shadow">
                <div class="card-header py-3">
                    <p class="text-primary m-0 font-weight-bold">Employee Info</p>
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
                                <form method="GET" action="">
                                    <input type="search" name="search" class="form-control form-control-sm" aria-controls="dataTable" placeholder="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                    <input type="hidden" name="limit" value="<?php echo $limit; ?>">
                                    <input type="hidden" name="page" value="<?php echo $page; ?>">
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
                                    <th>Profile</th>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Email</th>
                                    <th>Account Type</th>
                                    <th>Phone</th>
                                    <th>Date Registered</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($result)) {
                                    $num = $offset + 1;
                                    foreach ($result as $row) {
                                        $id = htmlspecialchars($row['id']);
                                        $fname = htmlspecialchars($row['firstname']);
                                        $lname = htmlspecialchars($row['lastname']);
                                        $email = htmlspecialchars($row['email']);
                                        $phone = htmlspecialchars($row['phone']);
                                        $date = htmlspecialchars($row['register_date']);
                                        $accounttype = htmlspecialchars($row['account_type']);
                                ?>
                                        <tr>
                                            <td><?php echo $num++; ?></td>
                                            <td><img class="rounded-circle mr-2" width="30" height="30" src="../assets/img/avatars/avatar1.jpeg"></td>
                                            <td><?php echo $fname; ?></td>
                                            <td><?php echo $lname; ?></td>
                                            <td><?php echo $email; ?></td>
                                            <td><?php echo $accounttype; ?></td>
                                            <td><?php echo $phone; ?></td>
                                            <td><?php echo $date; ?></td>
                                            <td><a href="../api/users/process.viewuser.php?id=<?php echo $id; ?>" class="btn btn-success edit-button">Edit</a></td>
                                            <td><a href="../api/users/process.delete.php?id=<?php echo $id; ?>" class="btn btn-danger">Delete</a></td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='10'>No data found</td></tr>";
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><strong>Number</strong></td>
                                    <td><strong>Profile</strong></td>
                                    <td><strong>FirstName</strong></td>
                                    <td><strong>Lastname</strong></td>
                                    <td><strong>Email</strong></td>
                                    <td><strong>Account Type</strong></td>
                                    <td><strong>Phone</strong></td>
                                    <td><strong>Date Registered</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="row">
                            <div class="col-md-6 align-self-center">
                                <p id="dataTable_info" class="dataTables_info" role="status" aria-live="polite">
                                    Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $limit, $total_users); ?> of <?php echo $total_users; ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination">
                                        <li class="page-item <?php if ($page == 1) echo 'disabled'; ?>">
                                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&limit=<?php echo $limit; ?>" aria-label="Previous">
                                                <span aria-hidden="true">«</span>
                                            </a>
                                        </li>
                                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                            <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                                                <a class="page-link" href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?php if ($page == $total_pages) echo 'disabled'; ?>">
                                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&limit=<?php echo $limit; ?>" aria-label="Next">
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
            window.location.href = `?limit=${limit}&page=1`;
        });
    </script>
</body>

</html>