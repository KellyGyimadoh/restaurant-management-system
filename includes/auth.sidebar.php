<nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
    <div class="container-fluid d-flex flex-column p-0">
        <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
            <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-laugh-wink"></i></div>
            <div class="sidebar-brand-text mx-3"><span>RATATOUILLE</span></div>
        </a>
        <hr class="sidebar-divider my-0">
        
        <ul class="nav navbar-nav text-light" id="accordionSidebar">
            <li class="nav-item" role="presentation"><a class="nav-link" href="../manager/home.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="manageEmployeesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-tasks"></i><span>Manage Employees</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="manageEmployeesDropdown">
                    <a class="dropdown-item" href="../users/table.php">All Employees Table</a>
                    <a class="dropdown-item" href="../users/admintable.php">Admins</a>
                    <a class="dropdown-item" href="../users/stafftable.php">Staff</a>
                    <a class="dropdown-item" href="../users/signup.php">Register Worker</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="manageCustomersDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-tasks"></i><span>Manage Customers</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="manageCustomersDropdown">
                    <a class="dropdown-item" href="../customers/tablecust.php"> Booking Customers </a>
                    <a class="dropdown-item" href="../customers/tableordercustomers.php">Order Customers Table</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="manageMenuDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-tasks"></i><span>Manage Menu</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="manageMenuDropdown">
                    <a class="dropdown-item" href="../menus/newmenuprofile.php"><i class="fas"></i><span> Add New Menu Profile</span></a>
                    <a class="dropdown-item" href="../menus/tablemenutype.php">Menu Types</a>
                    <a class="dropdown-item" href="../menus/tablemenusection.php">Menu Sections</a>
                    <a class="dropdown-item" href="../menus/tablemenuitems.php">Menu Items</a>
                    <a class="dropdown-item" href="../menus/tableallmenus.php">View All Menus</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="manageOrdersDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-tasks"></i><span>Manage Orders</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="manageOrdersDropdown">
                    <a class="dropdown-item" href="../customers/customerprofile.php">Place Order</a>
                    <a class="dropdown-item" href="../orders/neworderprofile.php">Submit Order</a>
                    <a class="dropdown-item" href="../orders/orderstable.php">View Orders</a>
                </div>
            </li>
            <!--mail part-->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="manageMailDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-tasks"></i><span>Manage Mail</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="manageMailDropdown">
                    <a class="dropdown-item" href="../mail/sendmail.php">Send Mail/Sms To Workers</a>
                    <a class="dropdown-item" href="../mail/customermail.php">Send Mail/Sms to Customers</a>
                    <a class="dropdown-item" href="../mail/customerbookingmail.php">Send Mail/Sms to Booking Customers</a>
                    <a class="dropdown-item" href="../mail/readmail.php">Read Mail</a>
                </div>
            </li>
            <!--view sales-->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="manageMailDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-tasks"></i><span>Manage Sales</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="manageMailDropdown">
                    <a class="dropdown-item" href="../sales/todaysales.php">View Today sales</a>
                     </div>
            </li>
            <li class="nav-item" role="presentation"><a class="nav-link active" href="../users/profile.php"><i class="fas fa-user"></i><span>Profile</span></a></li>
            <li class="nav-item" role="presentation"><a class="nav-link active" href="../shop/shopsettings.php"><i class="fas fa-cogs fa-sm fa-fw"></i></i><span>Shop Settings</span></a></li>
            <li class="nav-item" role="presentation"><a class="nav-link" href="forgot-password.html"><i class="fas fa-key"></i><span>Forgotten Password</span></a></li>
            <li class="nav-item" role="presentation"><a class="nav-link" href="404.html"><i class="fas fa-exclamation-circle"></i><span>Page Not Found</span></a></li>
            <li class="nav-item" role="presentation"><a class="nav-link" href="../user/home.php"><i class="fas fa-window-maximize"></i><span>Workers Page</span></a></li>
        </ul>
        <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
    </div>
</nav>