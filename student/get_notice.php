<?php
include('../includes/dbconnection.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tblpublicnotice WHERE ID = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo "<h3>" . htmlentities($result['NoticeTitle']) . "</h3>";
        echo $result['NoticeMessage'];
    } else {
        echo "<p>Notice not found.</p>";
    }
}
?>
