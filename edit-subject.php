<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (!isset($_SESSION['sturecmsaid']) || $_SESSION['user_type'] !== 'admin') {
    echo "<script>alert('You are not authorized to access this page. Please log in as a admin.');</script>";
    echo "<script type='text/javascript'> document.location ='logout.php'; </script>";
    exit();
} else {
    if (isset($_POST['submit'])) {
        $sub_name = $_POST['sub_name'];
        $level = $_POST['level'];
        $medium = $_POST['medium'];
        $type = $_POST['type'];
        $subid = $_POST['subid'];

        $sql = "UPDATE tblsubject SET sub_name = :sub_name, level = :level, medium = :medium, type = :type WHERE sub_id = :subid";
        $query = $dbh->prepare($sql);

        
        $query->bindParam(':sub_name', $sub_name, PDO::PARAM_STR);
        $query->bindParam(':level', $level, PDO::PARAM_STR);
        $query->bindParam(':medium', $medium, PDO::PARAM_STR);
        $query->bindParam(':type', $type, PDO::PARAM_STR);
        $query->bindParam(':subid', $subid, PDO::PARAM_STR);

        
        $query->execute();
        echo '<script>alert("Subject has been updated")</script>';
    }
}
?>

<?php include_once('includes/header.php'); ?>

<div class="container-fluid page-body-wrapper">
    <?php include_once('includes/sidebar.php'); ?>

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> Update Subject </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Update subject</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title" style="text-align: center;">Update Subject</h4>

                            <form class="forms-sample" method="post" enctype="multipart/form-data">
                                <?php
                                $eid = $_GET['editid'];
                                $sql = "SELECT * FROM tblsubject WHERE sub_id = :eid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);

                                if ($query->rowCount() > 0) {
                                    foreach ($results as $row) { ?>
                                        <div class="form-group">
                                            <label for="exampleInputName1">Subject Name</label>
                                            <input type="text" name="sub_name" value="<?php echo htmlentities($row->sub_name); ?>"
                                                class="form-control" required='true'>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputName1">Level</label>
                                            <select name="level" class="form-control" required='true'>
                                                <option value="ordinary" <?php echo ($row->level == 'ordinary') ? 'selected' : ''; ?>>Ordinary Level</option>
                                                <option value="secondary" <?php echo ($row->level == 'secondary') ? 'selected' : ''; ?>>Secondary level</option>
                                                <option value="advance" <?php echo ($row->level == 'advance') ? 'selected' : ''; ?>>Advance Level</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputName1">Medium</label>
                                            <select name="medium" class="form-control" required='true'>
                                                <option value="english" <?php echo ($row->medium == 'english') ? 'selected' : ''; ?>>English</option>
                                                <option value="tamil" <?php echo ($row->medium == 'tamil') ? 'selected' : ''; ?>>Tamil</option>
                                                <option value="both" <?php echo ($row->medium == 'both') ? 'selected' : ''; ?>>Both</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputName1">Type</label>
                                            <select name="type" class="form-control" required='true'>
                                                <option value="compulsory" <?php echo ($row->type == 'compulsory') ? 'selected' : ''; ?>>Compulsory</option>
                                                <option value="b1" <?php echo ($row->type == 'b1') ? 'selected' : ''; ?>>Basket 1</option>
                                                <option value="b2" <?php echo ($row->type == 'b2') ? 'selected' : ''; ?>>Basket 2</option>
                                                <option value="b3" <?php echo ($row->type == 'b3') ? 'selected' : ''; ?>>Basket 3</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputName1">Subject ID</label>
                                            <input type="text" name="subid" value="<?php echo htmlentities($row->sub_id); ?>" class="form-control" readonly='true'>
                                        </div>

                                        <button type="submit" name="submit" class="btn btn-primary">Update Subject</button>
                                    <?php }
                                } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </div>
</div>
