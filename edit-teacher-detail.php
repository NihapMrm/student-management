<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']==0)) {
  header('location:logout.php');
  } else{
   if(isset($_POST['submit']))
  {
    $trid = $_POST['trid'];
    $trname = $_POST['trname'];
    $tremail = $_POST['tremail'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $subid = $_POST['subid'];
    $connum = $_POST['connum'];
    $address = $_POST['address'];
 $eid=$_GET['editid'];
 if (!empty($_FILES['image']['name'])) {
  $image = $_FILES['image']['name'];
  $targetDir = "assets/uploads/Teachers/";
  $randomName = uniqid() . '.' . strtolower(pathinfo($image, PATHINFO_EXTENSION));
  $targetFile = $targetDir . $randomName;

  // Move the uploaded file
  move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
  $sql = "UPDATE tblteacher SET id=:trid, name=:trname, email=:tremail, gender=:gender, dob=:dob, sub_id=:subid, mobile_number=:connum, address=:address, image=:image WHERE id=:eid";
} else {
    // If no new image, just update other fields
    $sql = "UPDATE tblteacher SET id=:trid, name=:trname, email=:tremail, gender=:gender, dob=:dob, sub_id=:subid, mobile_number=:connum, address=:address WHERE id=:eid";
  }
$query=$dbh->prepare($sql);
$query->bindParam(':trid', $trid, PDO::PARAM_STR);
$query->bindParam(':trname', $trname, PDO::PARAM_STR);
$query->bindParam(':tremail', $tremail, PDO::PARAM_STR);
$query->bindParam(':gender', $gender, PDO::PARAM_STR);
$query->bindParam(':dob', $dob, PDO::PARAM_STR);
$query->bindParam(':subid', $subid, PDO::PARAM_STR);
$query->bindParam(':connum', $connum, PDO::PARAM_STR);
$query->bindParam(':address', $address, PDO::PARAM_STR);
$query->bindParam(':eid',$eid,PDO::PARAM_STR);
if (!empty($_FILES['image']['name'])) {
  $query->bindParam(':image', $targetFile, PDO::PARAM_STR);
}
 $query->execute();
  echo '<script>alert("Teacher has been updated")</script>';
}

  ?>

   
      <!-- partial:partials/_navbar.html -->
     <?php include_once('includes/header.php');?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
      <?php include_once('includes/sidebar.php');?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Update Teachers </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> Update Teachers</li>
                </ol>
              </nav>
            </div>
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title" style="text-align: center;">Update Teachers</h4>
                   
                    <form class="forms-sample" method="post" enctype="multipart/form-data">
                      <?php
$eid=$_GET['editid'];
$sql="SELECT * from tblteacher where tblteacher.id=:eid";
$query = $dbh -> prepare($sql);
$query->bindParam(':eid',$eid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>
                               <div class="form-group">
                                    <label for="exampleInputName1">Teacher ID <span style='color:red;'>*</span></label>
                                    <input type="text" name="trid" value="<?php  echo htmlentities($row->id);?>" class="form-control" required>
                                </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Teacher Name</label>
                        <input type="text" name="trname" value="<?php  echo htmlentities($row->name);?>" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Teacher Email</label>
                        <input type="text" name="tremail" value="<?php  echo htmlentities($row->email);?>" class="form-control" >
                      </div>
                     
                      <div class="form-group">
                        <label for="exampleInputName1">Gender</label>
                        <select name="gender" class="form-control" required>
    <option value="Male" <?php if ($row->gender == 'Male') echo 'selected'; ?>>Male</option>
    <option value="Female" <?php if ($row->gender == 'Female') echo 'selected'; ?>>Female</option>
</select>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Date of Birth</label>
                        <input type="date" name="dob" value="<?php  echo htmlentities($row->dob);?>" class="form-control" >
                      </div>
                     
                      <div class="form-group col-md-6">
    <label for="exampleInputEmail3">Subject <span style='color:red;'>*</span></label>
    <select name="subid" class="form-control" required>
        <option value="">Select Subject</option>
        <?php 
        $sql2 = "SELECT * FROM tblsubject";
        $query2 = $dbh->prepare($sql2);
        $query2->execute();
        $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
        foreach ($result2 as $row1) {          
        ?>  
        <option value="<?php echo htmlentities($row1->sub_id); ?>" 
            <?php if (isset($row->sub_id) && $row->sub_id == $row1->sub_id) echo 'selected'; ?>>
            <?php echo htmlentities($row1->sub_name); ?>
        </option>
        <?php } ?> 
    </select>
</div>
<div class="form-group">

    <img src="<?php echo !empty($row->image) ? htmlentities($row->image) : 'assets/images/default.webp'; ?>" 
         alt="Teacher Photo" 
         style="width: 100px; height: auto;">
</div>

                                        <div class="form-group">
                                            <label for="exampleInputName1">Change Teacher Photo</label>
                                            <input type="file" name="image" class="form-control">
                                        </div>
      
                      <div class="form-group">
                        <label for="exampleInputName1">Contact Number</label>
                        <input type="text" name="connum" value="<?php  echo htmlentities($row->mobile_number);?>" class="form-control"  maxlength="10" pattern="[0-9]+">
                      </div>
                     
                      <div class="form-group">
                        <label for="exampleInputName1">Address</label>
                        <textarea name="address" class="form-control" ><?php  echo htmlentities($row->address);?></textarea>
                      </div>
<h3>Login details</h3>
<div class="form-group">
                        <label for="exampleInputName1">User Name</label>
                        <input type="text" name="uname" value="<?php  echo htmlentities($row->username);?>" class="form-control" readonly='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Password</label>
                        <input type="Password" name="password" value="<?php  echo htmlentities($row->password);?>" class="form-control" readonly='true'>
                      </div><?php $cnt=$cnt+1;}} ?>
                      <button type="submit" class="btn btn-primary mr-2" name="submit">Update</button>
                     
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
         <?php include_once('includes/footer.php');?>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
   <?php }  ?>