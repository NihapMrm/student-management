<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['login'])) 
  {
    $username=$_POST['username'];
    $password=md5($_POST['password']);
    $sql ="SELECT ID FROM tbladmin WHERE UserName=:username and Password=:password";
    $query=$dbh->prepare($sql);
    $query-> bindParam(':username', $username, PDO::PARAM_STR);
$query-> bindParam(':password', $password, PDO::PARAM_STR);
    $query-> execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    if($query->rowCount() > 0)
{
foreach ($results as $result) {
$_SESSION['sturecmsaid']=$result->ID;
$_SESSION['user_type'] = 'admin';
}


  if(!empty($_POST["remember"])) {
setcookie ("user_login",$_POST["username"],time()+ (10 * 365 * 24 * 60 * 60));

setcookie ("userpassword",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60));
} else {
if(isset($_COOKIE["user_login"])) {
setcookie ("user_login","");
if(isset($_COOKIE["userpassword"])) {
setcookie ("userpassword","");
        }
      }
}
$_SESSION['login']=$_POST['username'];
echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
} else{
echo "<script>alert('Invalid Details');</script>";
}
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
  
    <title>Edu Authorities Student Management System|| Login Page</title>
    
    <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    
    
    
    
    
    
    <link rel="stylesheet" href="assets/css/style.css">
   <style>
     .content-wrapper{
          background-image: url('assets/images/background.jpg');
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
                  <img src="assets/images/logo.png">
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
                  <div class="mt-3 d-flex gap-3 justify-content-between">
                    <a href="teacher" class="btn btn-success">Teacher</a>
                    <a href="student" class="btn btn-success">Student</a>

                  </div>
        
                  </div>
     
                </form>
              </div>
            </div>
          </div>
        </div>
        
      </div>
      
    </div>
    
    
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    
    
    
    
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    
  </body>
</html>