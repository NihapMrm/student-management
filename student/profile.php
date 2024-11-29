<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!isset($_SESSION['sturecmsaid']) || $_SESSION['user_type'] !== 'student') {
    echo "<script>alert('You are not authorized to access this page. Please log in as a student.');</script>";
    echo "<script type='text/javascript'> document.location ='logout.php'; </script>";
    exit();
} else{
    if(isset($_POST['submit']))
  {
    $stid = $_SESSION['sturecmsaid'];
    $name=$_POST['name'];
  $mobno=$_POST['mobilenumber'];
  $email=$_POST['email'];
  $sql="update tblstudent set StudentName=:name,ContactNumber=:mobilenumber,StudentEmail=:email where ID=:stid";
     $query = $dbh->prepare($sql);
     $query->bindParam(':name',$name,PDO::PARAM_STR);
     $query->bindParam(':email',$email,PDO::PARAM_STR);
     $query->bindParam(':mobilenumber',$mobno,PDO::PARAM_STR);
     $query->bindParam(':stid',$stid,PDO::PARAM_STR);
$query->execute();

    echo '<script>alert("Your profile has been updated")</script>';
    echo "<script>window.location.href ='profile.php'</script>";

  }
  ?>

      
     <?php include_once('../includes/header.php');?>
      
      <div class="container-fluid page-body-wrapper">
        
      <?php include_once('../includes/sidebar.php');?>
        
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Profile </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Profile</li>
                </ol>
              </nav>
            </div>
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h1 class="pb-4 " style="text-align: center;">Profile</h1>
                   
                    <form class="forms-sample" method="post">
                      <?php
                          $stid = $_SESSION['sturecmsaid'];


$sql="SELECT * from tblstudent where ID=:stid";
$query = $dbh -> prepare($sql);
$query-> bindParam(':stid', $stid, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>
                      <div class="form-group">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" name="name" value="<?php  echo $row->StudentName;?>" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail3">User Name</label>
                        <input type="text" name="username" value="<?php  echo $row->UserName;?>" class="form-control" readonly="">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword4">Contact Number</label>
                        <input type="text" name="mobilenumber" value="<?php  echo $row->ContactNumber;?>"  class="form-control" maxlength='10' required='true' pattern="[0-9]+">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputCity1">Email</label>
                         <input type="email" name="email" value="<?php  echo $row->StudentEmail;?>" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputCity1">Admition      Date</label>
                         <input type="text" name="" value="<?php  echo $row->DateofAdmission;?>" readonly="" class="form-control">
                      </div><?php $cnt=$cnt+1;}} ?> 
                      <button type="submit" class="btn btn-primary mr-2" name="submit">Update</button>
                     
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          
         <?php include_once('../includes/footer.php');?>
          
        </div>
        
      </div>
      
   
  <?php }  ?>