<?php
include('includes/dbconnection.php');

if (isset($_GET['classid'])) {
    $classid = intval($_GET['classid']);

    // Get the class name from tblclass
    $sqlClass = "SELECT ClassName FROM tblclass WHERE ID = :classid";
    $queryClass = $dbh->prepare($sqlClass);
    $queryClass->bindParam(':classid', $classid, PDO::PARAM_INT);
    $queryClass->execute();
    $class = $queryClass->fetch(PDO::FETCH_OBJ);

    if ($class) {
        // Determine the level based on the class name
        $className = $class->ClassName;
        $level = '';

        if (preg_match('/Grade ([0-9]+)/', $className, $matches)) {
            $grade = intval($matches[1]);
            if ($grade >= 6 && $grade <= 9) {
                $level = 'ordinary';
            } elseif ($grade >= 10 && $grade <= 11) {
                $level = 'secondary';
            } elseif ($grade >= 12 && $grade <= 13) {
                $level = 'advanced';
            }
        }

        // Fetch subjects based on the determined level
        $sqlSubjects = "SELECT * FROM tblsubject WHERE Level = :level";
        $querySubjects = $dbh->prepare($sqlSubjects);
        $querySubjects->bindParam(':level', $level, PDO::PARAM_STR);
        $querySubjects->execute();
        $subjects = $querySubjects->fetchAll(PDO::FETCH_OBJ);

        // Generate the options for the subject select
        foreach ($subjects as $subject) {
            echo "<option value='" . htmlentities($subject->sub_id) . "'>" . htmlentities($subject->sub_name) . "</option>";
        }
    }
}
?>
