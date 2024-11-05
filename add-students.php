<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $stuname = $_POST['stuname'];
        $stuemail = $_POST['stuemail'];
        $stuclass = $_POST['stuclass'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $stuid = $_POST['stuid'];
        $fname = $_POST['fname'];
        $mname = $_POST['mname'];
        $connum = $_POST['connum'];
        $altconnum = $_POST['altconnum'];
        $address = $_POST['address'];
        $uname = $_POST['uname'];
        $password = md5($_POST['password']);
        $image = $_FILES["image"]["name"];

        $ret = "SELECT UserName FROM tblstudent WHERE UserName=:uname OR StuID=:stuid";
        $query = $dbh->prepare($ret);
        $query->bindParam(':uname', $uname, PDO::PARAM_STR);
        $query->bindParam(':stuid', $stuid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() == 0) {
            $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
            $allowed_extensions = array("jpg", "jpeg", "png", "gif", "webp");
            $targetDir = "assets/uploads/Students/";
            $randomName = uniqid() . '.' . $extension;
            $targetFile = $targetDir . $randomName;

            // Move the uploaded file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $sql = "INSERT INTO tblstudent (StudentName, StudentEmail, StudentClass, Gender, DOB, StuID, FatherName, MotherName, ContactNumber, AltenateNumber, Address, UserName, Password, Image) VALUES (:stuname, :stuemail, :stuclass, :gender, :dob, :stuid, :fname, :mname, :connum, :altconnum, :address, :uname, :password, :image)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':stuname', $stuname, PDO::PARAM_STR);
                $query->bindParam(':stuemail', $stuemail, PDO::PARAM_STR);
                $query->bindParam(':stuclass', $stuclass, PDO::PARAM_STR);
                $query->bindParam(':gender', $gender, PDO::PARAM_STR);
                $query->bindParam(':dob', $dob, PDO::PARAM_STR);
                $query->bindParam(':stuid', $stuid, PDO::PARAM_STR);
                $query->bindParam(':fname', $fname, PDO::PARAM_STR);
                $query->bindParam(':mname', $mname, PDO::PARAM_STR);
                $query->bindParam(':connum', $connum, PDO::PARAM_STR);
                $query->bindParam(':altconnum', $altconnum, PDO::PARAM_STR);
                $query->bindParam(':address', $address, PDO::PARAM_STR);
                $query->bindParam(':uname', $uname, PDO::PARAM_STR);
                $query->bindParam(':password', $password, PDO::PARAM_STR);
                $query->bindParam(':image', $targetFile, PDO::PARAM_STR);
                $query->execute();

                $LastInsertId = $dbh->lastInsertId();
                if ($LastInsertId > 0) {
                    echo '<script>alert("Student has been added.")</script>';
                    echo "<script>window.location.href ='add-students.php'</script>";
                } else {
                    echo '<script>alert("Something Went Wrong. Please try again")</script>';
                }
            } else {
                echo '<script>alert("Error uploading the image. Please try again.");</script>';
            }
        } else {
            echo "<script>alert('Username or Student ID already exists. Please try again');</script>";
        }
    }
?>

<!-- partial:partials/_navbar.html -->
<?php include_once('includes/header.php'); ?>
<!-- partial -->
<div class="container-fluid page-body-wrapper">
    <!-- partial:partials/_sidebar.html -->
    <?php include_once('includes/sidebar.php'); ?>
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> Add Students </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Add Students</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample row" method="post" enctype="multipart/form-data">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputName1">Student Name <span style='color:red;'>*</span></label>
                                    <input type="text" name="stuname" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputName1">Student Email</label>
                                    <input type="text" name="stuemail" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail3">Student Class <span style='color:red;'>*</span></label>
                                    <select name="stuclass" class="form-control" required>
                                        <option value="">Select Class</option>
                                        <?php 
                                        $sql2 = "SELECT * FROM tblclass";
                                        $query2 = $dbh->prepare($sql2);
                                        $query2->execute();
                                        $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                                        foreach ($result2 as $row1) {          
                                        ?>  
                                        <option value="<?php echo htmlentities($row1->ID); ?>"><?php echo htmlentities($row1->ClassName); ?> <?php echo htmlentities($row1->Section); ?></option>
                                        <?php } ?> 
                                    </select>
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
                                    <label for="exampleInputName1">Student ID <span style='color:red;'>*</span></label>
                                    <input type="text" name="stuid" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputName1">Student Photo</label>
                                    <input type="file" name="image" class="form-control" required>
                                </div>
                                <h3 class="col-md-12">Parents/Guardian's details</h3>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputName1">Father's Name</label>
                                    <input type="text" name="fname" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputName1">Mother's Name</label>
                                    <input type="text" name="mname" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputName1">Contact Number</label>
                                    <input type="text" name="connum" class="form-control" maxlength="10" pattern="[0-9]+">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputName1">Alternate Contact Number</label>
                                    <input type="text" name="altconnum" class="form-control" maxlength="10" pattern="[0-9]+">
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
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <?php include_once('includes/footer.php'); ?>
        <!-- partial -->
    </div>
    <!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
<?php } ?>
