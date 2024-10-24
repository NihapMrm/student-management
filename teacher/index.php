<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
error_reporting(0);
include('../includes/dbconnection.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Consider using password_hash() in production

    // Check in the teacher table
    $sqlTeacher = "SELECT ID FROM tblteacher WHERE UserName = :username AND Password = :password";
    $queryTeacher = $dbh->prepare($sqlTeacher);
    $queryTeacher->bindParam(':username', $username, PDO::PARAM_STR);
    $queryTeacher->bindParam(':password', $password, PDO::PARAM_STR);
    $queryTeacher->execute();

    if ($queryTeacher->rowCount() > 0) {
        // If found in teacher table
        $result = $queryTeacher->fetch(PDO::FETCH_OBJ);
        $_SESSION['sturecmsaid'] = $result->ID;
        $_SESSION['user_type'] = 'teacher'; // Set user type

        // Check if the "remember me" checkbox is checked
        if (!empty($_POST["remember"])) {
            // Set cookies for username and password for 10 years
            setcookie("user_login", $username, time() + (10 * 365 * 24 * 60 * 60), "/"); // Cookie for username
            setcookie("user_password", $_POST["password"], time() + (10 * 365 * 24 * 60 * 60), "/"); // Cookie for password
        } else {
            // If "remember me" is not checked, clear cookies
            if (isset($_COOKIE["user_login"])) {
                setcookie("user_login", "", time() - 3600, "/");
            }
            if (isset($_COOKIE["user_password"])) {
                setcookie("user_password", "", time() - 3600, "/");
            }
        }

        // Redirect to teacher dashboard
        echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}


?><!--  Orginal Author Name: Mayuri.K. 
 for any PHP, Codeignitor, Laravel OR Python work contact me at mdkhairnar92@gmail.com  
 Visit website : https://mayurik.com -->  
<!DOCTYPE html>
<html lang="en">
  <head>
  
    <title>Edu Authorities Student Management System|| Login Page</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
   <style>
     .content-wrapper{
          background-image: url('../assets/images/background.jpg');
          background-size: cover;
     }
   </style>
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-center p-5">
                <div class="brand-logo">
                  <img src="../assets/images/logo.png">
                </div>
                <h4>Hello! let's get started</h4>
                <h6 class="font-weight-light">Sign in to continue.</h6>
                <form class="pt-3" id="login" method="post" name="login">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" placeholder="enter your username" required="true" name="username" value="<?php if(isset($_COOKIE["user_login"])) { echo $_COOKIE["user_login"]; } ?>" >
                  </div>
                  <div class="form-group">
                    
                    <input type="password" class="form-control form-control-lg" placeholder="enter your password" name="password" required="true" value="<?php if(isset($_COOKIE["userpassword"])) { echo $_COOKIE["userpassword"]; } ?>">
                  </div>
                  <div class="mt-3">
                    <button class="btn btn-success btn-block loginbtn" name="login" type="submit">Login</button>
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" id="remember" class="form-check-input" name="remember" <?php if(isset($_COOKIE["user_login"])) { ?> checked <?php } ?> /> Keep me signed in </label>
                    </div>
                    <a href="forgot-password.php" class="auth-link text-black">Forgot password?</a>
                  </div>
         <!--          <div class="mb-2">
                    <a href="../index.php" class="btn btn-block btn-facebook auth-form-btn">
                      <i class="icon-social-home mr-2"></i>Back Home </a>
                  </div> -->
                  
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../assets/js/off-canvas.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <!-- endinject -->
  </body>
</html>