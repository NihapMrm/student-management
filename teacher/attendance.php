<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!isset($_SESSION['sturecmsaid']) || $_SESSION['user_type'] !== 'teacher') {
  echo "<script>alert('You are not authorized to access this page. Please log in as a teacher.');</script>";
  echo "<script type='text/javascript'> document.location ='index.php'; </script>";
  exit();
  } else{

?>

      <!-- partial:partials/_navbar.html -->
     <?php include_once('../includes/header.php');?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <?php include_once('../includes/sidebar.php');?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
             <div class="page-header">
              <h3 class="page-title"> Attendance </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> Attendance</li>
                </ol>
              </nav>
            </div>
            <div class="row">
            <?php
// Include your database connection here
// Example: $dbh = new PDO('mysql:host=localhost;dbname=your_db', 'username', 'password');

// Fetch sections and students based on selected class name and section
if (isset($_GET['className']) && !isset($_GET['section'])) {
    $className = $_GET['className'];

    // Query to get sections based on the selected class name
    $sections = "SELECT Section FROM tblclass WHERE ClassName = :className ORDER BY Section ASC";
    $query = $dbh->prepare($sections);
    $query->bindParam(':className', $className, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll();

    // Output the sections for the selected class
    if ($results) {
        foreach ($results as $section) {
            echo "<option value='" . htmlspecialchars($section['Section']) . "'>" 
                . htmlspecialchars($section['Section']) 
                . "</option>";
        }
    } else {
        echo "<option value=''>No sections available</option>";
    }
    exit(); // End script execution after outputting the sections
}

if (isset($_GET['className']) && isset($_GET['section'])) {
    $className = $_GET['className'];
    $section = $_GET['section'];

    // Query to get ClassID based on selected ClassName and Section
    $classIdQuery = "SELECT ID FROM tblclass WHERE ClassName = :className AND Section = :section";
    
    $query = $dbh->prepare($classIdQuery);
    $query->bindParam(':className', $className, PDO::PARAM_STR);
    $query->bindParam(':section', $section, PDO::PARAM_STR);
    $query->execute();
    $class = $query->fetch(PDO::FETCH_ASSOC);
   
    if ($class) {
        // Now use ClassID to get students
        $classID = $class['ID'];
        $studentsQuery = "SELECT ID, StudentName FROM tblstudent WHERE StudentClass = :ID";
        
        $query = $dbh->prepare($studentsQuery);
        $query->bindParam(':ID', $classID, PDO::PARAM_INT);
        $query->execute();
        $students = $query->fetchAll();

        // Output the students for the selected class and section
        if ($students) {
            foreach ($students as $student) {
                echo "<p>ID: " . htmlspecialchars($student['ID']) . " - Name: " . htmlspecialchars($student['StudentName']) . "</p>";
            }
        } else {
            echo "<p>No students found for this class and section.</p>";
        }
    } else {
        echo "<p>Class not found.</p>";
    }
    exit(); // End script execution after outputting the students
}

// Query to load unique class names for the first dropdown
$classes = "SELECT DISTINCT ClassName FROM tblclass";
$query = $dbh->prepare($classes);
$query->execute();
$results = $query->fetchAll();

?>

<!-- Class dropdown -->
<?php if ($results): ?>
    <select name='class' id='classSelect' class='form-control' onchange='loadSections(this.value)'>
        <option value=''>Select a Class</option>
        <?php foreach ($results as $class): ?>
            <option value="<?php echo htmlspecialchars($class['ClassName']); ?>">
                <?php echo htmlspecialchars($class['ClassName']); ?>
            </option>
        <?php endforeach; ?>
    </select>
<?php else: ?>
    <p>No classes to display.</p>
<?php endif; ?>

<!-- Section dropdown (initially empty) -->
<select name="section" id="sectionSelect" class="form-control" onchange="loadStudents()">
    <option value="">Select a Section</option>
</select>
<div>
    <label for="attendanceDate">Select Date:</label>
    <input type="date" id="attendanceDate" class="form-control" onchange="loadStudents()">
</div>

<!-- Div to display students -->
<div id="studentList"></div>

<script>
// Function to dynamically load sections based on the selected class name
function loadSections(className) {
    // Clear the section dropdown first
    document.getElementById('sectionSelect').innerHTML = "<option value=''>Select a Section</option>";

    if (className !== "") {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "attendanceback.php?className=" + encodeURIComponent(className), true); // Adjust PHP filename here
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById('sectionSelect').innerHTML += xhr.responseText; // Append new options
            }
        };
        xhr.send();
    }
}

// Function to load students based on the selected class and section
function loadStudents() {
    var className = document.getElementById('classSelect').value;
    var section = document.getElementById('sectionSelect').value;
    var date = document.getElementById('attendanceDate').value;

    // Clear existing student list before loading new students
    var studentListDiv = document.getElementById('studentList');
    studentListDiv.innerHTML = ""; // Clear previous student entries

    if (className !== "" && section !== "" && date !== "") {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "attendanceback.php?className=" + encodeURIComponent(className) + "&section=" + encodeURIComponent(section) + "&date=" + encodeURIComponent(date), true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                studentListDiv.innerHTML = xhr.responseText; // Replace with new student entries
            }
        };
        xhr.send();
    }
}
</script>



                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
         <?php include_once('../includes/footer.php');?>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <?php }  ?>