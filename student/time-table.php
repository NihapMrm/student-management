<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
error_reporting(0);
include('../includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
}
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
                <h3 class="page-title">Student Time Table</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Student Time Table</li>
                    </ol>
                </nav>
            </div>
            <div>
    <?php
   $studentId = $_SESSION['sturecmsaid'];
   $sql = "SELECT StudentClass FROM tblstudent WHERE ID = :studentId";
   $query = $dbh->prepare($sql);
   $query->bindParam(':studentId', $studentId, PDO::PARAM_INT);
   $query->execute();
   $result = $query->fetch(PDO::FETCH_ASSOC);

   if ($result) {
       $classId = $result['StudentClass'];}
       $timetableSql = "SELECT ImagePath FROM tbltimetable WHERE ClassId = :classId";
       $timetableQuery = $dbh->prepare($timetableSql);
       $timetableQuery->bindParam(':classId', $classId, PDO::PARAM_INT);
       $timetableQuery->execute();
       $timetableResult = $timetableQuery->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="d-flex flex-row gap">
        <!-- Display the timetable image -->
        <?php if ($timetableResult && !empty($timetableResult['ImagePath'])): ?>
            <div id="uploadedImage" class="pt-3">
                <img src="../<?php echo htmlspecialchars($timetableResult['ImagePath']); ?>" alt="Timetable Image">
            </div>
        <?php else: ?>
            <p>No timetable image to display.</p>
        <?php endif; ?>
    </div>
    <style>
        #uploadedImage img {
            width: 400px;
        }
    </style>
    <!-- Image upload form -->
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
</div>
<!-- container-scroller -->
