<?php
include('../includes/dbconnection.php');

// Handle class and section selection to load sections based on class
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

// Handle fetching students based on selected class, section, and date
if (isset($_GET['className']) && isset($_GET['section']) && isset($_GET['date'])) {
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

        // Output the students for the selected class and section with radio buttons for attendance
        if ($students) {
            echo "<table class='table'><thead><tr><th>Student Name</th><th>Attendance</th></tr></thead><tbody>";
            foreach ($students as $student) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($student['StudentName']) . "</td>";
                echo "<td>";
                echo "<input type='radio' name='attendance_" . $student['ID'] . "' value='present'> Present ";
                echo "<input type='radio' name='attendance_" . $student['ID'] . "' value='absent'> Absent ";
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
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
