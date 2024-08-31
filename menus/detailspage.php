<?php
include("../includes/sessions.php");
if(isset($_SESSION['fooddetails']['fooditem'])){
    $title = "Food Details of ".$_SESSION['fooddetails']['fooditem'];
}else{
    $title="Food Details";
}
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isloggedin() || !isset($_SESSION['accounttype']) || ($_SESSION['accounttype'] !== "director" && $_SESSION['accounttype'] !== "staff")) {
    header("Location: ../auth/index.php");
    die();
}




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
                    <p class="text-primary m-0 font-weight-bold">Customers Info</p>
                </div>
                <div class="card-body">                  
                    <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                        <table class="table my-0" id="dataTable">
                            <thead>
                                <tr>
                                    <th>Food Item</th>
                                    <th>Menu Type</th>
                                    <th>Section Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Details</th>
                                    <th>Action</th>
                                    <th>Image</th>



                                </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($_SESSION['fooddetails'])&& isset($_SESSION['fooddetails'])){
                                ?>
               <tr>
                                           
                                            <td><?php echo htmlspecialchars($_SESSION['fooddetails']['typeid']); ?></td>
                                            <td><?php echo htmlspecialchars($_SESSION['fooddetails']['sectionid']); ?></td>
                                            <td><?php echo htmlspecialchars($_SESSION['fooddetails']['id']); ?></td>
                                            <td><h1><?php echo htmlspecialchars($_SESSION['fooddetails']['fooditem']); ?></h1></td>
                                            <td> <?php echo htmlspecialchars($_SESSION['fooddetails']['menu_type']); ?></td>
                                            <td> <?php echo htmlspecialchars($_SESSION['fooddetails']['section_name']); ?></td>
                                            <td> <?php echo htmlspecialchars($_SESSION['fooddetails']['itemdescription']); ?></td>
                                            <td> <?php echo htmlspecialchars($_SESSION['fooddetails']['price']); ?></td> 
                                            
                                            <td><a href="tableallmenus.php" class="btn btn-success edit-button">Back to Menus</a></td>
                                            <td><a href="editmenuprofile.php?id=<?php echo htmlspecialchars($_SESSION['fooddetails']['typeid']); ?>" class="btn btn-success edit-button">Edit</a>
                                           
                                             <a href="../api/menus/process.deletealldetails.php?id=<?php echo htmlspecialchars($id); ?>" class="btn btn-danger">Delete</a>
                                            </td>
                                            <?php if (!empty($_SESSION['fooddetails']['image'])): ?>
            <td><img src="path/to/images/<?php echo htmlspecialchars($_SESSION['fooddetails']['image']); ?>" alt="<?php echo htmlspecialchars($_SESSION['fooddetails']['fooditem']); ?>">imagee</td>
            
        <?php endif; ?>
                                        </tr>
                                <?php
                                    }
                                    
                             else {
                                    echo "<tr><td colspan='8'>No food Found</td></tr>";
                                }
                                unset($_SESSION['fooddetails']);
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                <th>Food Item</th>
                                    <th>Menu Type</th>
                                    <th>Section Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Image</th>
                                    <th>Details</th>

                                </tr>
                            </tfoot>
                        </table>
                       
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