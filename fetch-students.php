<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {
    // Get the class ID from the AJAX request
    $classid = intval($_GET['classid']);
    $year = intval($_GET['year']); // Assuming the year is passed via AJAX too
    $term = $_GET['term']; // Assuming the term is passed via AJAX too
    $subid = intval($_GET['subid']); // Assuming the subject ID is passed via AJAX too

    $sql = "SELECT s.StuID, s.StudentName, m.marks 
            FROM tblstudent s 
            LEFT JOIN tblmarks m ON s.StuID = m.stuid 
                AND m.year = :year 
                AND m.term = :term 
                AND m.subid = :subid 
            WHERE s.StudentClass = :classid";

    $query = $dbh->prepare($sql);
    $query->bindParam(':classid', $classid, PDO::PARAM_INT);
    $query->bindParam(':year', $year, PDO::PARAM_INT);
    $query->bindParam(':term', $term, PDO::PARAM_STR);
    $query->bindParam(':subid', $subid, PDO::PARAM_INT);
    $query->execute();
    $students = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        echo '<table class="table table-bordered">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Student ID</th>';
        echo '<th>Student Name</th>';
        echo '<th>Marks</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        foreach ($students as $student) {
            echo '<tr>';
            echo '<td>' . htmlentities($student->StuID) . '</td>';
            echo '<td>' . htmlentities($student->StudentName) . '</td>';
            echo '<td>';
            echo '<input type="hidden" name="stuid[]" value="' . htmlentities($student->StuID) . '">';
            echo '<input type="number" name="marks[]" class="form-control" placeholder="Enter Marks" min="0" max="100" required value="' . (isset($student->marks) ? htmlentities($student->marks) : '') . '">';
            echo '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>No students found for this class.</p>';
    }
}
?>
