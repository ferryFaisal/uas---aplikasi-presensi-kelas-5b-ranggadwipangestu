<?php
require "connect_db.php";
session_start();
ob_start();
if (isset($_SESSION['login'])) { //jika sudah login
    //menampilkan isi session
    // echo "<h1>Selamat Datang " . $_SESSION['login'] . "</h1>";
    // echo "<h2>Halaman ini hanya bisa diakses jika Anda sudah login</h2>";
    // echo "<h2>Klik <a href='session3.php'>di sini (session03.php)</a> untuk LOGOUT</h2>";
} else {
    //session belum ada artinya belum login
    die("Anda belum login! Anda tidak berhak masuk ke halaman ini.Silahkan login <a href='login.php'>di sini</a>");
}
// define variables and set to empty values
$namaErr = $nimErr = $classErr = "";
$nama = $nim = $class = "";
$valid_nama = $valid_nim = $valid_class = false;

$sql = "SELECT * FROM mahasiswa WHERE nim = '$_GET[nim]'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        switch ($row['kelas']) {
            case "5A":
                $attrKelas = "selected";
                break;
            case "5B":
                $attrKelas = "selected";
                break;
        }
        $nim = $row['nim'];
        $nama = $row['nama'];
        $class = $row['kelas'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // nim section
    if (empty($_POST["nim"])) {
        $nimErr = "NIM is required";
        $valid_nim = false;
    } else {
        $nim1 = test_input($_POST["nim"]);
        $valid_nim = true;
    }

    //name section
    if (empty($_POST["nama"])) {
        $namaErr = "Name is Required";
        $valid_nama = false;
    } else {
        $nama1 = test_input($_POST["nama"]);
        $valid_nama = true;

    }
    //class section
    if (empty($_POST["kelas"])) {
        $classErr = "Class is required";
        $valid_class = false;
    } else {
        $class1 = test_input($_POST["kelas"]);
        $valid_class = true;
    }

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

    <title>Admin Panel - Table Mahasiswa</title>

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

        <a class="navbar-brand mr-1" href="index.php">PRESENSI MAHASISWA TEKNIK INFORMATIKA</a>

        <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Navbar Search -->
        <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari..." aria-label="Search"
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

                        <a class="dropdown-item" href='form_update_user.php?email=<?php echo $row2['email'] ?>'>
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
            <?php
if ($_SESSION['role'] == "Admin") {

    ?>
            <li class="nav-item ">
                <a class="nav-link" href="tables.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Table Users</span></a>
                <?php
}
?>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="tables-mahasiswa.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Table Mahasiswa</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="tables-customer.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Table Presensi</span></a>
            </li>

        </ul>

        <div id="content-wrapper">

            <div class="container-fluid">

                <!-- Breadcrumbs-->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Tables</li>

                </ol>


                <!-- DataTables Example -->
                <main>
                    <div class="card mb-3">

                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Add Products
                        </div>
                        <div class="card-body">

                            <p><span class="error">* required field</span></p>
                            <form method="post" ENCTYPE="multipart/form-data">

                                NIM : <input type="number" min="1" step="any" name="nim" value="<?php echo $nim; ?>"
                                    disabled>
                                <span class="error">* <?php echo $nimErr; ?></span>
                                <br><br>
                                Nama : <input type="text" name="nama" value="<?php echo $nama; ?>">
                                <span class="error">* <?php echo $namaErr; ?></span>
                                <br><br>

                                <select class="form-select" aria-label="Default select example" name="kelas"
                                    required="required">
                                    <option selected>Pilih Kelas</option>
                                    <option value="5A" <?php echo $attrKelas; ?>>5A</option>
                                    <option value="5B" <?php echo $attrKelas; ?>>5B</option>

                                </select>
                                <span class="error">* <?php echo $classErr; ?></span>
                                <br><br>
                                <input type="submit" name="submit" value="Submit" class="btn btn-primary">

                            </form>


                            <?php
if ($valid_nim && $valid_nama && $valid_class == true) {

    include 'edit_data_mahasiswa.php';

}
?>
                        </div>
                    </div>
            </div>
            </main>

            <!-- Sticky Footer -->
            <footer class="sticky-footer">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright © Rangga </span>
                    </div>
                </div>
            </footer>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
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

</body>

</html>