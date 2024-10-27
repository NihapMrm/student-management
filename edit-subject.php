<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']==0)) {
  header('location:logout.php');
  } else{
   if(isset($_POST['submit']))
  {
 $stuname=$_POST['subname'];
 $gender=$_POST['level'];

 $gender=$_POST['medium'];
 $gender=$_POST['type'];



$sql="update tblstudent set StudentName=:tblsubject.SubjectName,,tblsubject.Level,tblsubject.SubID,tblsubject.Medium,tblsubject.Type";
$query=$dbh->prepare($sql);
$query->bindParam(':subname',$subname,PDO::PARAM_STR);
$query->bindParam(':level',$level,PDO::PARAM_STR);
$query->bindParam(':medium',$medium,PDO::PARAM_STR);
$query->bindParam(':type',$type,PDO::PARAM_STR);
$query->bindParam(':subid',$subid,PDO::PARAM_STR);

 $query->execute();
  echo '<script>alert("Student has been updated")</script>';
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
              <h3 class="page-title"> Update Subject </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> Update subject</li>
                </ol>
              </nav>
            </div>
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title" style="text-align: center;">Update Subject</h4>
                   
                    <form class="forms-sample" method="post" enctype="multipart/form-data">
                      <?php
$eid=$_GET['editid'];
$sql="SELECT tblsubject.SubjectName,,tblsubject.Level,tblsubject.SubID,tblsubject.Medium,tblsubject.Type ";
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
                        <label for="exampleInputName1">Subject Name</label>
                        <input type="text" name="subname" value="<?php  echo htmlentities($row->StudentName);?>" class="form-control" required='true'>
                      </div>

                         <?php 

$sql2 = "SELECT * from    tblsub ";
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
                        <label for="exampleInputName1">Level</label>
                        <select name="level" value="" class="form-control" required='true'>
                          <option value="<?php  echo htmlentities($row->Gender);?>"><?php  echo htmlentities($row->Gender);?></option>
                          <option value="ordinary">Ordinary Level</option>
                          <option value="secondary">Secondary level</option>
                          <option value="advance">Advance Level</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Medium</label>
                        <select name="medium" value="" class="form-control" required='true'>
                          <option value="<?php  echo htmlentities($row->Gender);?>"><?php  echo htmlentities($row->Gender);?></option>
                          <option value="english">Engkish</option>
                          <option value="tamil">Tamil</option>
                          <option value="both">Both</option>
                        </select>
                      </div> <div class="form-group">
                        <label for="exampleInputName1">Typer</label>
                        <select name="type" value="" class="form-control" required='true'>
                          <option value="<?php  echo htmlentities($row->Gender);?>"><?php  echo htmlentities($row->Gender);?></option>
                          <option value="compulsory">compulsory</option>
                          <option value="b1">basket 1</option>
                          <option value="b2">basket 2</option>
                          <option value="b3">basket 3</option>
                          
                        </select>
                      </div>
                       <div class="form-group">
                        <label for="exampleInputName1">Subject ID</label>
                        <input type="text" name="stuid" value="<?php  echo htmlentities($row->StuID);?>" class="form-control" readonly='true'>
                      </div>
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