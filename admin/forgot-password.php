<?php
$emailErr = "";
$valid_email = false;
require 'connect_db.php';
if (isset($_POST['submit'])) {

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

            $sql = "SELECT * FROM user where email ='$email'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['email'] == $email) {
                        $valid_email = true;
                        $emailErr = $row['password'];

                        break;
                    }
                }
            } else {
                echo "0 result!";
            }
            mysqli_close($conn);
        }
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

    <title>SB Admin - Forgot Password</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">

</head>

<body class="bg-dark">

    <div class="container">
        <div class="card card-login mx-auto mt-5">
            <div class="card-header">Reset Password</div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h4>Forgot your password?</h4>
                    <p>Enter your email address and we will show you the password</p>
                </div>
                <form method="post">
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="email" id="inputEmail" class="form-control" placeholder="Enter email address"
                                required="required" autofocus="autofocus" name="email">
                            <label for="inputEmail">Enter email address</label>
                            <span>* <?php echo $emailErr; ?></span>

                        </div>
                    </div>
                    <input class="btn btn-primary btn-block" type="submit" name="submit" value="Show Password">
                </form>
                <div class="text-center">
                    <a class="d-block small mt-3" href="register.php">Register an Account</a>
                    <a class="d-block small" href="login.php">Login Page</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

</body>

</html>