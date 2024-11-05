<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']==0)) {
  header('location:logout.php');
  } else{
   if(isset($_POST['submit']))
  {
 $subname=$_POST['subname'];
 $level=$_POST['level'];
 $medium=$_POST['medium'];
 $type=$_POST['type'];
 $subid=$_POST['subid'];


$sql="update tblsubject set :sub_name=subname,:Level=level,:Medium=medium,:Type=type,:sub_id=subid";
$query=$dbh->prepare($sql);
$query->bindParam(':subname',$subname,PDO::PARAM_STR);
$query->bindParam(':level',$level,PDO::PARAM_STR);
$query->bindParam(':medium',$medium,PDO::PARAM_STR);
$query->bindParam(':type',$type,PDO::PARAM_STR);
$query->bindParam(':subid',$subid,PDO::PARAM_STR);

 $query->execute();
  echo '<script>alert("Subject has been updated")</script>';
}
  }
  ?>

   
      
     <?php include_once('includes/header.php');?>
      
      <div class="container-fluid page-body-wrapper">
        
      <?php include_once('includes/sidebar.php');?>
        
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
$sql="SELECT * from tblsubject where sub_id=:eid";
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
                        <input type="text" name="subname" value="<?php  echo htmlentities($row->subname);?>" class="form-control" required='true'>
                      </div>

                         <?php 

$sql2 = "SELECT * from    tblsubject ";
$query2 = $dbh -> prepare($sql2);
$query2->execute();
$result2=$query2->fetchAll(PDO::FETCH_OBJ);

foreach($result2 as $row1)
{ 
          
    ?>  
<option value="<?php echo htmlentities($row1->SubjectName);?><?php echo htmlentities($row1->Level);?>"><?php echo htmlentities($row1->SubjectName);?> <?php echo htmlentities($row1->Level);?></option>
 <?php } ?> 
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Level</label>
                        <select name="level" value="" class="form-control" required='true'>
                          <option value="<?php  echo htmlentities($row->Level);?>"><?php  echo htmlentities($row->Level);?></option>
                          <option value="ordinary">Ordinary Level</option>
                          <option value="secondary">Secondary level</option>
                          <option value="advance">Advance Level</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Medium</label>
                        <select name="medium" value="" class="form-control" required='true'>
                          <option value="<?php  echo htmlentities($row->Medium);?>"><?php  echo htmlentities($row->Medium);?></option>
                          <option value="english">Engkish</option>
                          <option value="tamil">Tamil</option>
                          <option value="both">Both</option>
                        </select>
                      </div> <div class="form-group">
                        <label for="exampleInputName1">Type</label>
                        <select name="type" value="" class="form-control" required='true'>
                          <option value="<?php  echo htmlentities($row->Type);?>"><?php  echo htmlentities($row->Type);?></option>
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
          
          
         <?php include_once('includes/footer.php');?>
          
        </div>
        
      </div>
      
    </div>
    
   <?php }}  ?>