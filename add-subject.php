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

 $ret="select sub_name from tblsubject where sub_id=:subid";
 $query= $dbh -> prepare($ret);

$query->bindParam(':subid',$subid,PDO::PARAM_STR);
$query-> execute();
     $results = $query -> fetchAll(PDO::FETCH_OBJ);
if($query -> rowCount() == 0){
  $sql="insert into tblsubject(sub_name,Level,Medium,Type,sub_id)values(:sub_name,:Level,:Medium,:Type,:sub_id)" ;
  $query=$dbh->prepare($sql);
 $query->bindParam(':sub_name',$subname,PDO::PARAM_STR);
 $query->bindParam(':Level',$level,PDO::PARAM_STR);
 $query->bindParam(':Medium',$medium,PDO::PARAM_STR);
 $query->bindParam(':Type',$type,PDO::PARAM_STR);
 $query->bindParam(':sub_id',$subid,PDO::PARAM_STR);
 $query->execute();
   $LastInsertId=$dbh->lastInsertId();
   if ($LastInsertId>0) {
    echo '<script>alert("Subject has been added.")</script>';
echo "<script>window.location.href ='add-subject.php'</script>";
  }
  else
    {
         echo '<script>alert("Something Went Wrong. Please try again")</script>';
    }
}

else
{

echo "<script>alert('Subject Id already exist. Please try again');</script>";
}


}
  ?>

     
     <?php include_once('includes/header.php');?>
      
      <div class="container-fluid page-body-wrapper">
       
      <?php include_once('includes/sidebar.php');?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Add Teachers </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> Add Subject</li>
                </ol>
              </nav>
            </div>
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                   
                   
                    <form class="forms-sample row" method="post" enctype="multipart/form-data" >
                    <div class="form-group col-md-6">
                        <label for="exampleInputName1">Subject Id</label>
                        <input type="text" name="subid" value="" class="form-control" required='true'>
                      </div>
                     
                      <div class="form-group col-md-6">
                        <label for="exampleInputName1">Subject Name</label>
                        <input type="text" name="subname" value="" class="form-control" required='true'>
                      </div>
                      
                     
                      
                       <div class="form-group col-md-6">
                        <label for="exampleInputName1">Level</label>
                        <select name="level" value="" class="form-control" required='true'>
                          <option value="">Choose Level </option>
                          <option value="ordinary">Ordinary Level</option>
                          <option value="secondary">Secondary Level</option>
                          <option value="advance">Adavend Level</option>
                           </select>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="exampleInputName1">Medium</label>
                        <select name="medium" value="" class="form-control" required='true'>
                          <option value="both">Both</option>
                          <option value="tamil">Tamil</option>
                          <option value="english">Engish</option>
                          
                           </select>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="exampleInputName1">Type</label>
                        <select name="type" value="" class="form-control" required='true'>
                          <option value="">Choose Type </option>
                          <option value="compulsory">compulsory</option>
                          <option value="Basket 1">Basket 1</option>
                          <option value="Basket 2">Basket 2</option>
                          <option value="Basket 3">Basket 3</option>
                           </select>
                      </div>
                     
                       
                      <div class="form-group col-md-6">
                         <button type="submit" class="btn btn-primary mr-2" name="submit">Add</button>
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