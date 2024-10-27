<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['attendance'])) {
        $className = $data['className'];
        $section = $data['section'];
        $date = $data['date'];
        $attendanceData = $data['attendance'];

        // Debug: Log received data


        // Query to get ClassID based on selected ClassName and Section
        $classIdQuery = "SELECT ID FROM tblclass WHERE ClassName = :className AND Section = :section";
        $query = $dbh->prepare($classIdQuery);
        $query->bindParam(':className', $className, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->execute();
        $class = $query->fetch(PDO::FETCH_ASSOC);

        if ($class) {

            $classID = $class['ID'];
            

            // Loop through each student's attendance data
            foreach ($attendanceData as $studentId => $status) {
                // Verify StudentID exists in tblstudent
                $studentCheckQuery = "SELECT ID FROM tblstudent WHERE ID = :studentId";
                $studentCheck = $dbh->prepare($studentCheckQuery);
                $studentCheck->bindParam(':studentId', $studentId, PDO::PARAM_INT);
                $studentCheck->execute();
                $studentExists = $studentCheck->fetch(PDO::FETCH_ASSOC);
              
                if ($studentExists) {
             
                    // Insert or update attendance records in the database
                    $attendanceQuery = "INSERT INTO tblattendance (StudentID, ClassID, AttendanceDate, Status) VALUES (:studentId, :classID, :date, :status)
                                        ON DUPLICATE KEY UPDATE Status = :status"; // Assuming StudentID, ClassID, and AttendanceDate are unique together
                    
                    $query = $dbh->prepare($attendanceQuery);
                    $query->bindParam(':studentId', $studentId, PDO::PARAM_INT);
                    $query->bindParam(':classID', $classID, PDO::PARAM_INT);
                    $query->bindParam(':date', $date, PDO::PARAM_STR);
                    $query->bindParam(':status', $status, PDO::PARAM_STR);
                    
                    if (!$query->execute()) {
                        error_log("Error executing query: " . print_r($query->errorInfo(), true));
                    }
                } else {
                    error_log("StudentID $studentId does not exist in tblstudent.");
                }
            }

            echo "Attendance has been successfully submitted for date: " . htmlspecialchars($date) ;
        } else {
            echo "<p>Class not found.</p>";
        }
    } else {
        echo "<p>Invalid data received.</p>";
    }
    exit(); // End script execution after processing attendance
}





// Handle fetching students based on selected class, section, and date
if (isset($_GET['className']) && isset($_GET['section']) && isset($_GET['date'])) {
    $className = $_GET['className'];
    $section = $_GET['section'];
    $date = $_GET['date'];

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
           

            echo "<br><br>";
            echo "<table class='table table-striped table-bordered'><thead><tr><th>Student Name</th><th>Attendance</th></tr></thead><tbody>";
            foreach ($students as $student) {
                $studentID = $student['ID'];
                $studentName = htmlspecialchars($student['StudentName']);
                $attendanceQuery = "SELECT Status FROM tblattendance WHERE StudentID = :studentID AND ClassID = :classID AND AttendanceDate = :date";
                $attendanceStmt = $dbh->prepare($attendanceQuery);
                $attendanceStmt->bindParam(':studentID', $studentID, PDO::PARAM_INT);
                $attendanceStmt->bindParam(':classID', $classID, PDO::PARAM_INT);
                $attendanceStmt->bindParam(':date', $date, PDO::PARAM_STR);
                $attendanceStmt->execute();
                $attendance = $attendanceStmt->fetch(PDO::FETCH_ASSOC);
    
                $status = $attendance ? htmlspecialchars($attendance['Status']) : '';
               
                echo "<tr>";
                echo "<td>" . htmlspecialchars($studentName) . "</td>";
                echo "<td>";
                echo "<input type='radio' name=".htmlspecialchars($studentID)." value='present'" . ($status == 'present' ? ' checked' : '') . "> Present ";
                echo "<input type='radio' name=".htmlspecialchars($studentID)." value='absent'" . ($status == 'absent' ? ' checked' : '') . "> Absent ";
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
            echo "<br>";
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
