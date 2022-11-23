<?php
require "connect_db.php";

$nim2 = $_GET['nim'];

$sql1 = "UPDATE mahasiswa SET
nama='$nama1',
kelas='$class1'
WHERE nim ='$nim2'";

if (mysqli_query($conn, $sql1)) {
    header('Location: tables-mahasiswa.php');
    ob_end_flush();

} else {
    echo "gagal mengedit data: " . mysqli_error($conn);
}

mysqli_close($conn);
//test