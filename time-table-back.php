<?php

session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (!isset($_SESSION['sturecmsaid']) || $_SESSION['user_type'] !== 'admin') {
  echo "<script>alert('You are not authorized to access this page. Please log in as a admin.');</script>";
  echo "<script type='text/javascript'> document.location ='logout.php'; </script>";
  exit();
  }
  
if (isset($_GET['className']) && !isset($_GET['sectionName'])) {
    // Load sections based on the selected class name
    $className = $_GET['className'];
    $sections = "SELECT Section FROM tblclass WHERE ClassName = :className ORDER BY Section ASC";
    $query = $dbh->prepare($sections);
    $query->bindParam(':className', $className, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll();

    if ($results) {
        foreach ($results as $section) {
            echo "<option value='" . htmlspecialchars($section['Section']) . "'>" 
                . htmlspecialchars($section['Section']) 
                . "</option>";
        }
    } else {
        echo "<option value=''>No sections available</option>";
    }
    exit();
}

if (isset($_GET['className']) && isset($_GET['sectionName'])) {
    // Find ClassID based on class name and section
    $className = $_GET['className'];
    $sectionName = $_GET['sectionName'];

    $classIdQuery = "SELECT ID FROM tblclass WHERE ClassName = :className AND Section = :section";
    $query = $dbh->prepare($classIdQuery);
    $query->bindParam(':className', $className, PDO::PARAM_STR);
    $query->bindParam(':section', $sectionName, PDO::PARAM_STR);
    $query->execute();
    $class = $query->fetch(PDO::FETCH_ASSOC);

    if ($class) {
        $classID = $class['ID'];

        // Check if a timetable image exists for the ClassID
        $sql = "SELECT ImagePath FROM tbltimetable WHERE ClassID = :classID";
        $query = $dbh->prepare($sql);
        $query->bindParam(':classID', $classID, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode(['exists' => true, 'imagePath' => $result['ImagePath'],'classID' => $classID]);
        } else {
            echo json_encode(['exists' => false, 'classID' => $classID]);
        }
    } else {
        echo json_encode(['exists' => false]);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['timetableImage']) && isset($_POST['classID'])) {
    // Handle image upload
    $classID = $_POST['classID'];
    $targetDir = "assets/uploads/StTimeTables/";
    $randomName = uniqid() . '.' . strtolower(pathinfo($_FILES['timetableImage']['name'], PATHINFO_EXTENSION));
    $targetFile = $targetDir . $randomName;

    $check = getimagesize($_FILES['timetableImage']['tmp_name']);
    if ($check !== false) {
        if (move_uploaded_file($_FILES['timetableImage']['tmp_name'], $targetFile)) {
            $sql = "INSERT INTO tbltimetable (ClassID, ImagePath) VALUES (:classID, :imagePath)
                    ON DUPLICATE KEY UPDATE ImagePath = :imagePath";
            $query = $dbh->prepare($sql);
            $query->bindParam(':classID', $classID, PDO::PARAM_INT);
            $query->bindParam(':imagePath', $targetFile, PDO::PARAM_STR);
            $query->execute();
            echo "Time Table has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
    exit();
}





if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteImage'])) {
    // Handle image deletion
    $classID = $_POST['classID'];

    $sql = "DELETE FROM tbltimetable WHERE ClassID = :classID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':classID', $classID, PDO::PARAM_INT);
    $query->execute();

    // Optionally, delete the file from the server
    // unlink($result['ImagePath']);
    echo "Image deleted.";
    exit();
}


if (isset($_GET['teacherId'])) {
    // Find Teacher ID and handle timetable operations
    $teacherId = $_GET['teacherId'];
    
    // Check if a timetable image exists for the Teacher ID
    $sql = "SELECT ImagePath FROM tbltrtimetable WHERE TeacherId = :teacherId";
    $query = $dbh->prepare($sql);
    $query->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo json_encode(['exists' => true, 'imagePath' => $result['ImagePath'], 'teacherId' => $teacherId]);
    } else {
        echo json_encode(['exists' => false, 'teacherId' => $teacherId]);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['timetableImage']) && isset($_POST['teacherId'])) {
    // Handle image upload
    $teacherId = $_POST['teacherId'];
    $targetDir = "assets/uploads/TrTimeTables/";
    $randomName = uniqid() . '.' . strtolower(pathinfo($_FILES['timetableImage']['name'], PATHINFO_EXTENSION));
    $targetFile = $targetDir . $randomName;
    $check = getimagesize($_FILES['timetableImage']['tmp_name']);
    
    if ($check !== false) {
        if (move_uploaded_file($_FILES['timetableImage']['tmp_name'], $targetFile)) {
            $sql = "INSERT INTO tbltrtimetable (TeacherId, ImagePath) VALUES (:teacherId, :imagePath)
                    ON DUPLICATE KEY UPDATE ImagePath = :imagePath";
            $query = $dbh->prepare($sql);
            $query->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
            $query->bindParam(':imagePath', $targetFile, PDO::PARAM_STR);
            $query->execute();
            echo "Time Table has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteImage'])) {
    // Handle image deletion
    $teacherId = $_POST['teacherId'];
    $sql = "DELETE FROM tbltrtimetable WHERE TeacherId = :teacherId";
    $query = $dbh->prepare($sql);
    $query->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
    $query->execute();
    echo "Image deleted.";
    exit();
}




?>
