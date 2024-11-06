<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!isset($_SESSION['sturecmsaid']) || $_SESSION['user_type'] !== 'student') {
    echo "<script>alert('You are not authorized to access this page. Please log in as a student.');</script>";
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
                <h3 class="page-title">Class</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Class</li>
                    </ol>
                </nav>
            </div>
            <div>
                <?php
                $studentId = $_SESSION['sturecmsaid'];
                // Query to get the ClassID from the student's record
                $sql = "SELECT StudentClass FROM tblstudent WHERE ID = :studentId";
                $query = $dbh->prepare($sql);
                $query->bindParam(':studentId', $studentId, PDO::PARAM_INT);
                $query->execute();
                $result = $query->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    $classId = $result['StudentClass'];
                    // Now, get the class name and section using the ClassID
                    $classSql = "SELECT ClassName, Section FROM tblclass WHERE ID = :classId";
                    $classQuery = $dbh->prepare($classSql);
                    $classQuery->bindParam(':classId', $classId, PDO::PARAM_INT);
                    $classQuery->execute();
                    $classResult = $classQuery->fetch(PDO::FETCH_ASSOC);
                    
                    if ($classResult) {
                        $className = $classResult['ClassName'];
                        $section = $classResult['Section'];
                        echo "<div class='d-flex flex-row gap justify-content-center'>
                                <h1>" . htmlspecialchars($className) . " - " . htmlspecialchars($section) . "</h1>
                              </div><br><br>";
                        
                        // Query to get all students in the same class
                        $studentsSql = "SELECT StudentName, StuID FROM tblstudent WHERE StudentClass = :classId";
                        $studentsQuery = $dbh->prepare($studentsSql);
                        $studentsQuery->bindParam(':classId', $classId, PDO::PARAM_INT);
                        $studentsQuery->execute();
                        $students = $studentsQuery->fetchAll(PDO::FETCH_ASSOC);
                        
                        if ($students) {
                            echo "<table class='table table-striped'>
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                            foreach ($students as $student) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($student['StuID']) . "</td>
                                        <td>" . htmlspecialchars($student['StudentName']) . "</td>
                                      </tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "No students found in this class.";
                        }
                    } else {
                        echo "Class details not found.";
                    }
                } else {
                    echo "Class ID not found.";
                }
                ?>
            </div>
        </div>
        
        
        <?php include_once('../includes/footer.php'); ?>
        
    </div>
    
</div>

</div>

