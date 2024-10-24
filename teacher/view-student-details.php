<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['sturecmsaid']) || $_SESSION['user_type'] !== 'teacher') {
    echo "<script>alert('You are not authorized to access this page. Please log in as a teacher.');</script>";
    echo "<script type='text/javascript'> document.location ='index.php'; </script>";
    exit();
} else {
    $eid = $_GET['viewid'];

    // Fetch student details based on ID
    $sql = "SELECT tblstudent.StudentName, tblstudent.StudentEmail, tblstudent.StudentClass, tblstudent.Gender, tblstudent.DOB, 
            tblstudent.StuID, tblstudent.FatherName, tblstudent.MotherName, tblstudent.ContactNumber, tblstudent.AltenateNumber, 
            tblstudent.Address, tblstudent.Image, tblclass.ClassName, tblclass.Section 
            FROM tblstudent 
            JOIN tblclass ON tblclass.ID = tblstudent.StudentClass 
            WHERE tblstudent.ID = :eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if ($result) {
        // Display the student details
        ?>
        <!-- partial:partials/_navbar.html -->
        <?php include_once('../includes/header.php'); ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <?php include_once('../includes/sidebar.php'); ?>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title">View Student Details</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">View Student Details</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title" style="text-align: center;">Student Details</h4>

                                    <div class="form-group">
                                        <label>Student Name:</label>
                                        <p><?php echo htmlentities($result->StudentName); ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Student Email:</label>
                                        <p><?php echo htmlentities($result->StudentEmail); ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Student Class:</label>
                                        <p><?php echo htmlentities($result->ClassName . ' ' . $result->Section); ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Gender:</label>
                                        <p><?php echo htmlentities($result->Gender); ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Date of Birth:</label>
                                        <p><?php echo htmlentities($result->DOB); ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Student ID:</label>
                                        <p><?php echo htmlentities($result->StuID); ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Student Photo:</label><br>
                                        <img src="../assets/images/<?php echo htmlentities($result->Image); ?>" width="100" height="100">
                                    </div>

                                    <h3>Parents/Guardian's details</h3>
                                    <div class="form-group">
                                        <label>Father's Name:</label>
                                        <p><?php echo htmlentities($result->FatherName); ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Mother's Name:</label>
                                        <p><?php echo htmlentities($result->MotherName); ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Contact Number:</label>
                                        <p><?php echo htmlentities($result->ContactNumber); ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Alternate Contact Number:</label>
                                        <p><?php echo htmlentities($result->AltenateNumber); ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Address:</label>
                                        <p><?php echo htmlentities($result->Address); ?></p>
                                    </div>

                                   

                                    <a href="manage-students.php" class="btn btn-primary">Back to Student List</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <?php include_once('../includes/footer.php'); ?>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
        <?php
    } else {
        echo '<h3>No record found!</h3>';
    }
}
?>
