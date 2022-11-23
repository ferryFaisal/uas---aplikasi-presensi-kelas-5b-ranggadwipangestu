<?php
require "connect_db.php";
ob_start();

$nameErr = $emailErr = $roleErr = $passwordErr = $repeatpasswordErr = $imageErr = "";
// $name = $email = $role = $password = $repeatpassword = "";
$valid_name = $valid_email = $valid_role = $valid_password = $valid_passwordrepeat = $valid_image = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
        $valid_name = false;
    } else {
        $name = test_input($_POST["name"]);
        $valid_name = true;

        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed";

        }
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

    $nama_file = $_FILES['file']['name'];
    $dir_upload = "images/";
    $target_file = $dir_upload . basename($_FILES["file"]["name"]);

    // Select file type
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Valid file extensions
    $extensions_arr = array("jpg", "jpeg", "png", "gif");

    // Check extension
    if (in_array($imageFileType, $extensions_arr)) {
        // Upload file
        if (move_uploaded_file($_FILES['file']['tmp_name'], $dir_upload . $nama_file)) {
            // Insert record

            $valid_image = true;
        } else {
            $imageErr = "File photo is required";
            $valid_image = false;
        }
    } else {
        $imageErr = "File photo is required";
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

    <title>SB Admin - Register</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


</head>

<body class="bg-dark">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">

        <div class="container">
            <div class="card card-register mx-auto mt-5">
                <div class="card-header">Register an Account</div>
                <div class="card-body">
                    <form method="post" ENCTYPE="multipart/form-data">
                        <div class="form-group">

                            <div class="form-label-group">
                                <input type="text" id="fullName" class="form-control" placeholder="full name"
                                    required="required" autofocus="autofocus" name='name'>
                                <label for="fullName">Full Name</label>
                            </div>


                        </div>
                        <div class="form-group">
                            <div class="form-label-group">
                                <input type="email" id="inputEmail" class="form-control" placeholder="Email address"
                                    required="required" name="email">
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
                                            placeholder="Confirm password" required="required" name="repeatpassword">
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
                                    <option value="Sales">Sales</option>
                                    <option value="Finance">Finance</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlFile1">Photo</label>
                            <input type="file" class="form-control-file" id="exampleFormControlFile1" name='file'>

                            <span class="error">* <?php echo $imageErr; ?></span>

                        </div>


                        <input class="btn btn-primary btn-block" type="submit" name="submit" value="Register">
                    </form>
                    <div class="text-center">
                        <a class="d-block small mt-3" href="login.php">Login Page</a>
                        <a class="d-block small" href="forgot-password.php">Forgot Password?</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <?php
if ($valid_name && $valid_email && $valid_role && $valid_password && $valid_passwordrepeat && $valid_image = true) {
    include 'insert_data.php';
}
?>
</body>

</html>