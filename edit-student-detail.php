<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (!isset($_SESSION['sturecmsaid']) || $_SESSION['user_type'] !== 'admin') {
  echo "<script>alert('You are not authorized to access this page. Please log in as a admin.');</script>";
  echo "<script type='text/javascript'> document.location ='logout.php'; </script>";
  exit();
  } else{
   if(isset($_POST['submit']))
  {
 $stuname=$_POST['stuname'];
 $stuemail=$_POST['stuemail'];
 $stuclass=$_POST['stuclass'];
 $dob=$_POST['dob'];
 $stuid=$_POST['stuid'];
 $fname=$_POST['fname'];
 $mname=$_POST['mname'];
 $connum=$_POST['connum'];
 $altconnum=$_POST['altconnum'];
 $address=$_POST['address'];
 $eid=$_GET['editid'];
 if (!empty($_FILES['image']['name'])) {
  $image = $_FILES['image']['name'];
  $targetDir = "assets/uploads/Students/";
  $randomName = uniqid() . '.' . strtolower(pathinfo($image, PATHINFO_EXTENSION));
  $targetFile = $targetDir . $randomName;

  
  move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
  $sql = "UPDATE tblstudent SET StudentName=:stuname, StudentEmail=:stuemail, StudentClass=:stuclass, DOB=:dob, StuID=:stuid, FatherName=:fname, MotherName=:mname, ContactNumber=:connum, AltenateNumber=:altconnum, Address=:address, Image=:image WHERE ID=:eid";
} else {
    
    $sql = "UPDATE tblstudent SET StudentName=:stuname, StudentEmail=:stuemail, StudentClass=:stuclass, DOB=:dob, StuID=:stuid, FatherName=:fname, MotherName=:mname, ContactNumber=:connum, AltenateNumber=:altconnum, Address=:address WHERE ID=:eid";
}
$query=$dbh->prepare($sql);
$query->bindParam(':stuname',$stuname,PDO::PARAM_STR);
$query->bindParam(':stuemail',$stuemail,PDO::PARAM_STR);
$query->bindParam(':stuclass',$stuclass,PDO::PARAM_STR);
$query->bindParam(':dob',$dob,PDO::PARAM_STR);
$query->bindParam(':stuid',$stuid,PDO::PARAM_STR);
$query->bindParam(':fname',$fname,PDO::PARAM_STR);
$query->bindParam(':mname',$mname,PDO::PARAM_STR);
$query->bindParam(':connum',$connum,PDO::PARAM_STR);
$query->bindParam(':altconnum',$altconnum,PDO::PARAM_STR);
$query->bindParam(':address',$address,PDO::PARAM_STR);
$query->bindParam(':eid',$eid,PDO::PARAM_STR);
if (!empty($_FILES['image']['name'])) {
  $query->bindParam(':image', $targetFile, PDO::PARAM_STR);
}
 $query->execute();
  echo '<script>alert("Student has been updated")</script>';
}

  ?>

   
      
     <?php include_once('includes/header.php');?>
      
      <div class="container-fluid page-body-wrapper">
        
      <?php include_once('includes/sidebar.php');?>
        
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Update Students </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> Update Students</li>
                </ol>
              </nav>
            </div>
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title" style="text-align: center;">Update Students</h4>
                   
                    <form class="forms-sample" method="post" enctype="multipart/form-data">
                      <?php
$eid=$_GET['editid'];
$sql="SELECT tblstudent.StudentName,tblstudent.StudentEmail,tblstudent.StudentClass,tblstudent.DOB,tblstudent.StuID,tblstudent.FatherName,tblstudent.MotherName,tblstudent.ContactNumber,tblstudent.AltenateNumber,tblstudent.Address,tblstudent.UserName,tblstudent.Password,tblstudent.Image,tblstudent.DateofAdmission,tblclass.ClassName,tblclass.Section from tblstudent join tblclass on tblclass.ID=tblstudent.StudentClass where tblstudent.ID=:eid";
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
                        <label for="exampleInputName1">Student Name</label>
                        <input type="text" name="stuname" value="<?php  echo htmlentities($row->StudentName);?>" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Student Email</label>
                        <input type="text" name="stuemail" value="<?php  echo htmlentities($row->StudentEmail);?>" class="form-control" >
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail3">Student Class</label>
                        <select  name="stuclass" class="form-control" required='true'>
                          <option value="<?php  echo htmlentities($row->StudentClass);?>"><?php  echo htmlentities($row->ClassName);?> <?php  echo htmlentities($row->Section);?></option>
                         <?php 

$sql2 = "SELECT * from    tblclass ";
$query2 = $dbh -> prepare($sql2);
$query2->execute();
$result2=$query2->fetchAll(PDO::FETCH_OBJ);

foreach($result2 as $row1)
{          
    ?>  
<option value="<?php echo htmlentities($row1->ClassName);?><?php echo htmlentities($row1->Section);?>"><?php echo htmlentities($row1->ClassName);?> <?php echo htmlentities($row1->Section);?></option>
 <?php } ?> 
                        </select>
                      </div>
                   
                      <div class="form-group">
                        <label for="exampleInputName1">Date of Birth</label>
                        <input type="date" name="dob" value="<?php  echo htmlentities($row->DOB);?>" class="form-control" >
                      </div>
                     
                      <div class="form-group">
                        <label for="exampleInputName1">Student ID</label>
                        <input type="number" name="stuid" value="<?php  echo htmlentities($row->StuID);?>" class="form-control" readonly='true'>
                      </div>
                      <div class="form-group">
    <img src="<?php echo !empty($row->Image) ? htmlentities($row->Image) : 'assets/images/default.webp'; ?>" 
         alt="Student Photo" 
         style="width: 100px; height: auto;">
</div>

                                        <div class="form-group">
                                            <label for="exampleInputName1">Change Student Photo</label>
                                            <input type="file" name="image" class="form-control">
                                        </div>
                      <h3>Parents/Guardian's details</h3>
                      <div class="form-group">
                        <label for="exampleInputName1">Father's Name</label>
                        <input type="text" name="fname" value="<?php  echo htmlentities($row->FatherName);?>" class="form-control" >
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Mother's Name</label>
                        <input type="text" name="mname" value="<?php  echo htmlentities($row->MotherName);?>" class="form-control" >
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Contact Number</label>
                        <input type="text" name="connum" value="<?php  echo htmlentities($row->ContactNumber);?>" class="form-control"  maxlength="10" pattern="[0-9]+">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Alternate Contact Number</label>
                        <input type="text" name="altconnum" value="<?php  echo htmlentities($row->AltenateNumber);?>" class="form-control"  maxlength="10" pattern="[0-9]+">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Address</label>
                        <textarea name="address" class="form-control" ><?php  echo htmlentities($row->Address);?></textarea>
                      </div>
<h3>Login details</h3>
<div class="form-group">
                        <label for="exampleInputName1">User Name</label>
                        <input type="text" name="uname" value="<?php  echo htmlentities($row->UserName);?>" class="form-control" readonly='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Password</label>
                        <input type="Password" name="password" value="<?php  echo htmlentities($row->Password);?>" class="form-control" readonly='true'>
                      </div><?php $cnt=$cnt+1;}} ?>
                      <button type="submit" class="btn btn-primary mr-2" name="submit">Update</button>
                     
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          
         <?php include_once('includes/footer.php');?>
          
        </div>
        
      </div>
      
    </div>
    
   <?php }  ?>