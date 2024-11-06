<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!isset($_SESSION['sturecmsaid']) || $_SESSION['user_type'] !== 'teacher') {
  echo "<script>alert('You are not authorized to access this page. Please log in as a teacher.');</script>";
  echo "<script type='text/javascript'> document.location ='logout.php'; </script>";
  exit();
  }
?>


<?php include_once('../includes/header.php'); ?>

<div class="container-fluid page-body-wrapper">
    
    <?php include_once('../includes/sidebar.php'); ?>
    
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">Teacher Time Table</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Teacher Time Table</li>
                    </ol>
                </nav>
            </div>
            <div>
    <?php
    $teacherId = $_SESSION['sturecmsaid'];
    $teachers = "SELECT ImagePath FROM tbltrtimetable WHERE TeacherId = :teacherId";
    $query = $dbh->prepare($teachers);
    $query->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
    $query->execute();
    $results = $query->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="d-flex flex-row gap">
        
        <?php if ($results && !empty($results['ImagePath'])): ?>
            <div id="uploadedImage" class="pt-3">
                <img src="../<?php echo htmlspecialchars($results['ImagePath']); ?>" alt="Timetable Image">
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
    
</div>

        </div>
        
        
        <?php include_once('../includes/footer.php'); ?>
        
    </div>
    
</div>

</div>

