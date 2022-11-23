<?php
require "connect_db.php";
session_start();
ob_start();
if (isset($_SESSION['login']) && $_SESSION['role'] == "Admin") { //jika sudah login
    //menampilkan isi session
    // echo "<h1>Selamat Datang " . $_SESSION['login'] . "</h1>";
    // echo "<h2>Halaman ini hanya bisa diakses jika Anda sudah login</h2>";
    // echo "<h2>Klik <a href='session3.php'>di sini (session03.php)</a> untuk LOGOUT</h2>";
} else if (isset($_SESSION['login']) && $_SESSION['role'] == "Sales") {
    header('location : index.php');

    //session belum ada artinya belum login

} else {
    die("Anda tidak punya akses! Anda tidak berhak masuk ke halaman ini.Silahkan login <a href='login.php'>di sini</a>");
}
$nameErr = $emailErr = $roleErr = $passwordErr = $repeatpasswordErr = $imageErr = "";
// $name = $email = $role = $password = $repeatpassword = "";
$valid_name = $valid_email = $valid_role = $valid_password = $valid_passwordrepeat = $valid_image = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["name"])) {

        $valid_name = false;
    } else {
        $name = test_input($_POST["name"]);
        $valid_name = true;

    }

    if (empty($_POST["email"])) {

        $emailErr = "Email is required";
        $valid_email = false;

    } else {
        $email = test_input($_POST["email"]);
        $valid_email = true;
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $valid_email = false;

        } else {
            require 'connect_db.php';

            $sql = 'SELECT email FROM user';
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['email'] == $email) {
                        $emailErr = "Email already exist!";
                        $valid_email = false;
                        break;
                    } else {

                        $valid_email = true;
                    }
                }
            } else {
                echo "0 result!";
            }
            mysqli_close($conn);
        }
    }

    if (empty($_POST["password"])) {

        $valid_password = false;
        $valid_passwordrepeat = false;
    } else {
        $password = test_input($_POST["password"]);
        if ($_POST['password'] == $_POST['repeatpassword']) {

            $valid_password = true;
            $valid_passwordrepeat = true;
        } else {

            $repeatpassword = test_input($_POST["repeatpassword"]);
        }

    }

    if (empty($_POST["role"])) {

        $valid_role = false;

    } else {
        $role = test_input($_POST["role"]);
        $valid_role = true;
    }

    // $nama_file = $_FILES['file']['name'];
    // $dir_upload = "images/";
    // $target_file = $dir_upload . basename($_FILES["file"]["name"]);

    // // Select file type
    // $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // // Valid file extensions
    // $extensions_arr = array("jpg", "jpeg", "png", "gif");

    // // Check extension
    // if (in_array($imageFileType, $extensions_arr)) {
    //     // Upload file
    //     move_uploaded_file($_FILES['file']['tmp_name'], $dir_upload . $nama_file);
    //     // Insert record
    //     $valid_image = true;
    // } else {
    //     $imageErr = "File photo is required";
    //     $valid_image = false;

    // }
    // $dir_upload = "images/";
    // $nama_file = $_FILES['file']['name'];

    // if (is_uploaded_file($_FILES['file']['tmp_name'])) {
    //     $cek = move_uploaded_file($_FILES['file']['tmp_name'], //source
    //         // tujuan

    //         $dir_upload . $nama_file);

    // }

}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Panel - Table Users</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

</head>

<body id="page-top">

    <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

        <a class="navbar-brand mr-1" href="index.php">Start Bootstrap</a>

        <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Navbar Search -->
        <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for..." aria-label="Search"
                    aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>

        <!-- Navbar -->
        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <?php
require 'connect_db.php';
$sql2 = "SELECT * FROM user where email= '$_SESSION[login]'";
$result2 = mysqli_query($conn, $sql2);
$cek2 = mysqli_num_rows($result2);

if ($cek2 > 0) {
    $row2 = mysqli_fetch_assoc($result2);

    ?>
                    <img src="photo_user/<?php echo $row2['photo'] ?>" alt="" width="32" height="32"
                        class="rounded-circle me-2">

                    <strong><?php echo $_SESSION['name'] ?></strong>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">

                        <a class="dropdown-item" href='form_edit_user.php?email=<?php echo $row2['email'] ?>'>
                            <?php echo $_SESSION['role'] ?>
                        </a>
                        <?php
}
?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
                    </div>
            </li>
        </ul>

    </nav>

    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="sidebar navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>


            <li class="nav-item active">
                <a class="nav-link" href="tables.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Table Users</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="tables-product.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Table Products</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="tables-customer.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Table Customers</span></a>
            </li>

        </ul>

        <div id="content-wrapper">

            <div class="container-fluid">

                <!-- Breadcrumbs-->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Add Account</li>
                </ol>

                <!-- DataTables Example -->
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                    <div class="container">
                        <div class="card card-register mx-auto mt-5">
                            <div class="card-header">Register an Account</div>
                            <div class="card-body">
                                <form method="post" ENCTYPE="multipart/form-data">
                                    <div class="form-group">

                                        <div class="form-label-group">
                                            <input type="text" id="fullName" class="form-control"
                                                placeholder="full name" required="required" autofocus="autofocus"
                                                name='name'>
                                            <label for="fullName">Full Name</label>
                                        </div>


                                    </div>
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <input type="email" id="inputEmail" class="form-control"
                                                placeholder="Email address" required="required" name="email">
                                            <label for="inputEmail">Email address</label>
                                            <span class="error">* <?php echo $emailErr; ?></span>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-label-group">
                                                    <input type="password" id="inputPassword" class="form-control"
                                                        placeholder="Password" required="required" name="password">
                                                    <label for="inputPassword">Password</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-label-group">
                                                    <input type="password" id="confirmPassword" class="form-control"
                                                        placeholder="Confirm password" required="required"
                                                        name="repeatpassword">
                                                    <label for="confirmPassword">Confirm password</label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <select class="form-select" aria-label="Default select example" name="role"
                                                required="required">
                                                <option selected>Select Role</option>
                                                <option value="Admin">Admin</option>
                                                <option value="Dosen">Dosen</option>
                                                <!-- <option value="Finance">Finance</option> -->
                                            </select>
                                        </div>
                                    </div>

                                    <!-- <div class="form-group">
                    <label for="exampleFormControlFile1">Photo</label>
                    <input type="file" class="form-control-file" id="exampleFormControlFile1" name='file'>


                </div> -->


                                    <input class="btn btn-primary btn-block" type="submit" name="submit" value="Add">

                                </form>


                            </div>

                            <!-- /.container-fluid -->
                            <!-- /#wrapper -->
                        </div>
                        <footer class="sticky-footer">
                            <div class="container my-auto">
                                <div class="copyright text-center my-auto">
                                    <span>Copyright © Rangga</span>
                                </div>
                            </div>
                        </footer>
                        <!-- /.content-wrapper -->
                        <!-- Scroll to Top Button-->
                        <a class="scroll-to-top rounded" href="#page-top">
                            <i class="fas fa-angle-up"></i>
                        </a>

                        <!-- Logout Modal-->
                        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">Select "Logout" below if you are ready to end your current
                                        session.
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button"
                                            data-dismiss="modal">Cancel</button>
                                        <a class="btn btn-primary" href="logout.php">Logout</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bootstrap core JavaScript-->
                        <script src="vendor/jquery/jquery.min.js"></script>
                        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

                        <!-- Core plugin JavaScript-->
                        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

                        <!-- Page level plugin JavaScript-->
                        <script src="vendor/datatables/jquery.dataTables.js"></script>
                        <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

                        <!-- Custom scripts for all pages-->
                        <script src="js/sb-admin.min.js"></script>

                        <!-- Demo scripts for this page-->
                        <script src="js/demo/datatables-demo.js"></script>
                        <?php
if ($valid_name && $valid_email && $valid_role && $valid_password && $valid_passwordrepeat = true) {

    require 'connect_db.php';

    $encryptPassword = sha1($_POST['password']);

    $sql = "INSERT INTO user (email, name, password, role, date_created, date_modified)
VALUES ('$email',
'$name',
'$encryptPassword',
'$role',
sysdate(),
sysdate())";

    if (mysqli_query($conn, $sql)) {
        echo "data berhasil dimasukkan ke database";
        header('Location: tables.php');
        ob_end_flush();
    } else {
        echo "gagal memasukkan data: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

</body>

</html>