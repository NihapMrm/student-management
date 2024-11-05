<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $year = $_POST['year'];
        $term = $_POST['term'];
        $subid = $_POST['subid'];
        $classid = $_POST['classid'];
        $marks = $_POST['marks'];
        $stuid = $_POST['stuid'];

        // Loop through each student and insert marks
        for ($i = 0; $i < count($stuid); $i++) {
            $sql = "INSERT INTO tblmarks (year, term, subid, classid, stuid, marks) VALUES (:year, :term, :subid, :classid, :stuid, :marks)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':year', $year, PDO::PARAM_STR);
            $query->bindParam(':term', $term, PDO::PARAM_STR);
            $query->bindParam(':subid', $subid, PDO::PARAM_INT);
            $query->bindParam(':classid', $classid, PDO::PARAM_INT);
            $query->bindParam(':stuid', $stuid[$i], PDO::PARAM_INT);
            $query->bindParam(':marks', $marks[$i], PDO::PARAM_INT);
            $query->execute();
        }

        echo '<script>alert("Marks have been added.")</script>';
        echo "<script>window.location.href ='add-marks.php'</script>";
    }
?>

<?php include_once('includes/header.php'); ?>
<div class="container-fluid page-body-wrapper">
    <?php include_once('includes/sidebar.php'); ?>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> Add Marks </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Add Marks</li>
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
                                    <select name="year" class="form-control" id="year" required onchange="fetchStudents(document.getElementById('classid').value)">
                                        <option value="">Select Year</option>
                                        <?php
                                        $currentYear = date("Y");
                                        for ($year = $currentYear; $year >= 2000; $year--) {
                                            echo "<option value='$year'>$year</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="term">Term</label>
                                    <select name="term" class="form-control" id="term" required onchange="fetchStudents(document.getElementById('classid').value)">
                                        <option value="">Choose Term</option>
                                        <option value="first-term">First Term</option>
                                        <option value="second-term">Second Term</option>
                                        <option value="third-term">Third Term</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="classid">Class</label>
                                    <select name="classid" id="classid" class="form-control" required onchange="fetchStudents(this.value)">
                                        <option value="">Select Class</option>
                                        <?php
                                        $sql3 = "SELECT * FROM tblclass";
                                        $query3 = $dbh->prepare($sql3);
                                        $query3->execute();
                                        $classes = $query3->fetchAll(PDO::FETCH_OBJ);
                                        foreach ($classes as $class) { ?>
                                            <option value="<?php echo htmlentities($class->ID); ?>"><?php echo htmlentities($class->ClassName); ?> <?php echo htmlentities($class->Section); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="subid">Subject</label>
                                    <select name="subid" id="subid" class="form-control" required>
                                        <option value="">Select Subject</option>
                                    </select>
                                </div>

                                <div id="students-container"></div>

                                <button type="submit" class="btn btn-primary mr-2" name="submit" id="addMarksBtn" disabled>Add Marks</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('includes/footer.php'); ?>
    </div>
</div>

<script>
function fetchStudents(classid) {
    var year = document.querySelector('select[name="year"]').value; // Get the selected year
    var term = document.querySelector('select[name="term"]').value; // Get the selected term

    // Check if any value is empty
    if (classid === "" || year === "" || term === "") {
        document.getElementById("students-container").innerHTML = ""; // Clear students container
        document.getElementById("subid").innerHTML = "<option value=''>Select Subject</option>"; // Clear subjects
        document.getElementById("addMarksBtn").disabled = true; // Disable button
        return; // Exit function if any required field is not selected
    }

    // Fetch students for the selected class
    var xhrStudents = new XMLHttpRequest();
    xhrStudents.open("GET", "fetch-students.php?classid=" + classid + "&year=" + year + "&term=" + term, true);
    xhrStudents.onreadystatechange = function () {
        if (xhrStudents.readyState == 4 && xhrStudents.status == 200) {
            const response = xhrStudents.responseText;
            document.getElementById("students-container").innerHTML = response;
         

            // Check if no students were found
            if (response.trim() === "<p>No students found for this class.</p>") {
                document.getElementById("addMarksBtn").disabled = true; // Disable button if no students
            } else {
                document.getElementById("addMarksBtn").disabled = false; // Enable button if students are found
            }
        }
    };
    xhrStudents.send();

    // Fetch subjects based on selected class level
    var xhrSubjects = new XMLHttpRequest();
    xhrSubjects.open("GET", "fetch-subjects.php?classid=" + classid, true);
    xhrSubjects.onreadystatechange = function () {
        if (xhrSubjects.readyState == 4 && xhrSubjects.status == 200) {
            document.getElementById("subid").innerHTML = xhrSubjects.responseText;
        }
    };
    xhrSubjects.send();
}

function checkFields() {
    const year = document.getElementById('year').value;
    const term = document.getElementById('term').value;
    const classid = document.getElementById('classid').value;
    const subject = document.getElementById('subid').value; // Corrected this line to refer to 'subid'
    const button = document.getElementById('addMarksBtn');

    // Enable the button only if all fields are filled
    button.disabled = !(year && term && classid && subject);
}
</script>

<?php } ?>
