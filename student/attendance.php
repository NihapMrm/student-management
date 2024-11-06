<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!isset($_SESSION['sturecmsaid']) || $_SESSION['user_type'] !== 'student') {
    echo "<script>alert('You are not authorized to access this page. Please log in as a student.');</script>";
    echo "<script type='text/javascript'> document.location ='logout.php'; </script>";
    exit();
} else {
    $studentId = $_SESSION['sturecmsaid'];
    $startDate = isset($_POST['startDate']) ? $_POST['startDate'] : '';
    $endDate = isset($_POST['endDate']) ? $_POST['endDate'] : '';
?>


<?php include_once('../includes/header.php'); ?>
<div class="container-fluid page-body-wrapper">
    <?php include_once('../includes/sidebar.php'); ?>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">My Attendance Records</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Attendance</li>
                    </ol>
                </nav>
            </div>
            
            
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="attendance.php">
                        <div class="form-group">
                            <label for="startDate">Start Date:</label>
                            <input type="date" id="startDate" name="startDate" class="form-control" value="<?php echo htmlspecialchars($startDate); ?>">
                        </div>
                        <div class="form-group">
                            <label for="endDate">End Date:</label>
                            <input type="date" id="endDate" name="endDate" class="form-control" value="<?php echo htmlspecialchars($endDate); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                </div>
            </div>

            
            <div class="card mt-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                $sql = "SELECT a.AttendanceDate, c.ClassName, c.Section, a.Status 
                                        FROM tblattendance AS a
                                        JOIN tblclass AS c ON a.ClassID = c.ID
                                        WHERE a.StudentID = :studentId";

                                
                                if (!empty($startDate) && !empty($endDate)) {
                                    $sql .= " AND a.AttendanceDate BETWEEN :startDate AND :endDate";
                                }
                                
                                $sql .= " ORDER BY a.AttendanceDate DESC";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':studentId', $studentId, PDO::PARAM_INT);
                                
                                
                                if (!empty($startDate) && !empty($endDate)) {
                                    $query->bindParam(':startDate', $startDate);
                                    $query->bindParam(':endDate', $endDate);
                                }

                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_ASSOC);
                                
                                
                                if ($results) {
                                    foreach ($results as $record) {
                                        echo "<tr>
                                                <td>" . htmlspecialchars($record['AttendanceDate']) . "</td>
                                                <td>" . htmlspecialchars($record['ClassName']) . "</td>
                                                <td>" . htmlspecialchars($record['Section']) . "</td>
                                                <td>" . htmlspecialchars($record['Status']) . "</td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No attendance records found for the selected dates.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('../includes/footer.php'); ?>
<?php } ?>
