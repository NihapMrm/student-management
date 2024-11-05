<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $trid = $_POST['trid'];
        $trname = $_POST['trname'];
        $tremail = $_POST['tremail'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $subid = $_POST['subid'];
        $connum = $_POST['connum'];
        $address = $_POST['address'];
        $uname = $_POST['uname'];
        $password = md5($_POST['password']);
        $image = $_FILES["image"]["name"];

        $ret = "SELECT username FROM tblteacher WHERE username=:uname";
        $query = $dbh->prepare($ret);
        $query->bindParam(':uname', $uname, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() == 0) {
            $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
            $allowed_extensions = array("jpg", "jpeg", "png", "gif", "webp");
            $targetDir = "assets/uploads/Teachers/";
            $randomName = uniqid() . '.' . $extension;
            $targetFile = $targetDir . $randomName;

            // Move the uploaded file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $sql = "INSERT INTO tblteacher (id, name, email, gender, dob, sub_id, mobile_number, address, username, password, image) VALUES (:trid, :trname, :tremail, :gender, :dob, :subid, :connum, :address, :uname, :password, :image)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':trid', $trid, PDO::PARAM_STR);
                $query->bindParam(':trname', $trname, PDO::PARAM_STR);
                $query->bindParam(':tremail', $tremail, PDO::PARAM_STR);
                $query->bindParam(':gender', $gender, PDO::PARAM_STR);
                $query->bindParam(':dob', $dob, PDO::PARAM_STR);
                $query->bindParam(':subid', $subid, PDO::PARAM_STR);
                $query->bindParam(':connum', $connum, PDO::PARAM_STR);
                $query->bindParam(':address', $address, PDO::PARAM_STR);
                $query->bindParam(':uname', $uname, PDO::PARAM_STR);
                $query->bindParam(':password', $password, PDO::PARAM_STR);
                $query->bindParam(':image', $targetFile, PDO::PARAM_STR);
                $query->execute();

                $LastInsertId = $dbh->lastInsertId();
                if ($LastInsertId > 0) {
                    echo '<script>alert("Teacher has been added.")</script>';
                    echo "<script>window.location.href ='add-teachers.php'</script>";
                } else {
                    echo '<script>alert("Something Went Wrong. Please try again")</script>';
                }
            } else {
                echo '<script>alert("Error uploading the image. Please try again.");</script>';
            }
        } else {
            echo "<script>alert('Username or Teacher ID already exists. Please try again');</script>";
        }
    }
?>


<?php include_once('includes/header.php'); ?>

<div class="container-fluid page-body-wrapper">
    
    <?php include_once('includes/sidebar.php'); ?>
    
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> Add Teachers </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Add Teacher</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample row" method="post" enctype="multipart/form-data">
                                
                            <div class="form-group col-md-6">
                                    <label for="exampleInputName1">Teacher ID <span style='color:red;'>*</span></label>
                                    <input type="text" name="trid" class="form-control" required>
                                </div>
                            <div class="form-group col-md-6">
                                    <label for="exampleInputName1">Teacher Name <span style='color:red;'>*</span></label>
                                    <input type="text" name="trname" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputName1">Teacher Email</label>
                                    <input type="text" name="tremail" class="form-control">
                                </div>
                           
                                <div class="form-group col-md-6">
                                    <label for="exampleInputName1">Gender <span style='color:red;'>*</span></label>
                                    <select name="gender" class="form-control" required>
                                        <option value="">Choose Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputName1">Date of Birth</label>
                                    <input type="date" name="dob" class="form-control">
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
                                        <option value="<?php echo htmlentities($row1->sub_id); ?>"><?php echo htmlentities($row1->sub_name); ?></option>
                                        <?php } ?> 
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputName1">Teacher Photo</label>
                                    <input type="file" name="image" class="form-control" required>
                                </div>
                               
                                <div class="form-group col-md-6">
                                    <label for="exampleInputName1">Contact Number</label>
                                    <input type="text" name="connum" class="form-control" maxlength="10" pattern="[0-9]+">
                                </div>
                              
                                <div class="form-group col-md-12">
                                    <label for="exampleInputName1">Address</label>
                                    <textarea name="address" class="form-control"></textarea>
                                </div>
                                <h3 class="col-md-12">Login details</h3>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputName1">User Name <span style='color:red;'>*</span></label>
                                    <input type="text" name="uname" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputName1">Password <span style='color:red;'>*</span></label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary mr-2" name="submit">Add</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <?php include_once('includes/footer.php'); ?>
        
    </div>
    
</div>

</div>

<?php } ?>
