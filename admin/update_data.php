<?php
require "connect_db.php";

$email1 = $_GET['email'];

$sql1 = "UPDATE user SET name='$name',
password='$password',
role='$role',
photo='$nama_file',
date_modified = sysdate()
WHERE email = '$email1'";

if (mysqli_query($conn, $sql1)) {
    if (isset($_SESSION['login']) && $_SESSION['role'] == "Admin") {
        header('Location: tables.php');
        ob_end_flush();
    } else if (isset($_SESSION['login']) && $_SESSION['role'] == "Sales") {
        header('Location: index.php');
        ob_end_flush();
    }

} else {
    echo "gagal mengedit data: " . mysqli_error($conn);
}

mysqli_close($conn);