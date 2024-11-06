<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!isset($_SESSION['sturecmsaid']) || $_SESSION['user_type'] !== 'student') {
    echo "<script>alert('You are not authorized to access this page. Please log in as a student.');</script>";
    echo "<script type='text/javascript'> document.location ='logout.php'; </script>";
    exit();
}else {
    $year = '';
    $term = '';
    $stuid = '';
    $marks = [];
    $studentName = '';
    $studentClass = '';

    if (isset($_POST['submit'])) {
        $year = $_POST['year'];
        $term = $_POST['term'];
        $stuid = $_POST['stuid'];

        
        $sql = "SELECT sub.sub_name, m.marks, s.StudentName, c.ClassName, c.Section 
                FROM tblmarks m 
                JOIN tblsubject sub ON m.subid = sub.sub_id 
                JOIN tblstudent s ON m.stuid = s.StuID 
                JOIN tblclass c ON s.StudentClass = c.ID
                WHERE m.year = :year AND m.term = :term AND m.stuid = :stuid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':year', $year, PDO::PARAM_STR);
        $query->bindParam(':term', $term, PDO::PARAM_STR);
        $query->bindParam(':stuid', $stuid, PDO::PARAM_INT);
        $query->execute();
        $marks = $query->fetchAll(PDO::FETCH_ASSOC);
        
        
        if (!empty($marks)) {
            $studentName = htmlentities($marks[0]['StudentName']);
            $studentClass = htmlentities($marks[0]['ClassName'] . " " . $marks[0]['Section']);
        }
    }
?>

<?php include_once('../includes/header.php'); ?>
<div class="container-fluid page-body-wrapper">
    <?php include_once('../includes/sidebar.php'); ?>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> View Marks </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> View Marks</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                        <form class="forms-sample" method="post" oninput="checkFields()">
    <div class="form-group">
        <label for="year">Year</label>
        <select name="year" id="year" class="form-control" required>
            <option value="">Select Year</option>
            <?php 
            $currentYear = date("Y");
            for ($yearOption = $currentYear; $yearOption >= 2000; $yearOption--) {
                echo "<option value='$yearOption' " . ($yearOption == $year ? "selected" : "") . ">$yearOption</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="term">Term</label>
        <select name="term" id="term" class="form-control" required>
            <option value="">Choose Term</option>
            <option value="first-term" <?php echo ($term == 'first-term' ? "selected" : ""); ?>>First Term</option>
            <option value="second-term" <?php echo ($term == 'second-term' ? "selected" : ""); ?>>Second Term</option>
            <option value="third-term" <?php echo ($term == 'third-term' ? "selected" : ""); ?>>Third Term</option>
        </select>
    </div>

    <div class="form-group">
        <label for="stuid">Student ID</label>
        <input type="text" name="stuid" id="stuid" class="form-control" value="<?php echo htmlentities($stuid); ?>" required placeholder="Enter Student ID">
    </div>

    <button type="submit" id="viewMarksBtn" class="btn btn-primary mr-2" name="submit" disabled>View Marks</button>
</form>

                            <?php if (!empty($marks)): ?>
                                <div class="d-flex justify-content-between mt-4">
    <div class="flex-fill">
        <p>Name: <?php echo $studentName; ?></p>
    </div>
    <div class="flex-fill">
        <p>Class: <?php echo $studentClass; ?></p>
    </div>
    <div class="flex-fill">
        <p>Year: <?php echo htmlentities($year); ?></p>
    </div>
    <div class="flex-fill">
    <p>Term: <?php echo ucwords(str_replace('-', ' ', htmlentities($term))); ?></p>
    </div>
</div>

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Subject Name</th>
                                            <th>Marks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($marks as $mark): ?>
                                            <tr>
                                                <td><?php echo htmlentities($mark['sub_name']); ?></td>
                                                <td><?php echo htmlentities($mark['marks']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                                <div class="d-flex justify-content-between mt-4">
    <div class="flex-fill">
        <p>Name: <?php echo $studentName; ?></p>
    </div>
    <div class="flex-fill">
        <p>Class: <?php echo $studentClass; ?></p>
    </div>
    <div class="flex-fill">
        <p>Year: <?php echo htmlentities($year); ?></p>
    </div>
    <div class="flex-fill">
    <p>Term: <?php echo ucwords(str_replace('-', ' ', htmlentities($term))); ?></p>
    </div>
</div>
                                <p>No marks found for the selected student.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('../includes/footer.php'); ?>
    </div>
</div>
<script>
    function checkFields() {
    const year = document.getElementById('year').value;
    const term = document.getElementById('term').value;
    const stuid = document.getElementById('stuid').value;
    const button = document.getElementById('viewMarksBtn');

    
    button.disabled = !(year && term && stuid);
}
</script>
<?php } ?>
