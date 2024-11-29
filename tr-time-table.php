<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (!isset($_SESSION['sturecmsaid']) || $_SESSION['user_type'] !== 'admin') {
  echo "<script>alert('You are not authorized to access this page. Please log in as a admin.');</script>";
  echo "<script type='text/javascript'> document.location ='logout.php'; </script>";
  exit();
  }
?>


<?php include_once('includes/header.php'); ?>

<div class="container-fluid page-body-wrapper">
    
    <?php include_once('includes/sidebar.php'); ?>
    
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">Teacher Time Table</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Teacher Time Table</li>
                    </ol>
                </nav>
            </div>
            <div>
                <?php
                
                $teachers = "SELECT * FROM tblteacher";
                $query = $dbh->prepare($teachers);
                $query->execute();
                $results = $query->fetchAll();
                ?>
                 <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                <div class="d-flex flex-row gap">
                    
                    <?php if ($results): ?>
                        <select name='teacher' id='teacherSelect' class='form-control' onchange='loadTimeTable()'>
                            <option value=''>Select a Teacher</option>
                            <?php foreach ($results as $teacher): ?>
                                <option value="<?php echo htmlspecialchars($teacher['id']); ?>">
                                    <?php echo htmlspecialchars($teacher['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <p>No teachers to display.</p>
                    <?php endif; ?>
                </div>
                <style>
                    #uploadedImage img {
                        width: 400px;
                    }
                </style>
                
                <div id="timetableImageSection" style="display: none;">
                    <div id="uploadedImage" class="pt-3"></div>
                    <div class="d-flex w-100 justify-content-center align-items-center">
                        <form id="uploadForm" enctype="multipart/form-data" class="forms-sample d-flex flex-column align-items-center gap">
                            <input type="hidden" name="teacherId" id="teacherIdInput" class="form-control">
                            <input type="file" name="timetableImage" accept="image/*" class="form-control">
                            <button type="button" onclick="uploadImage()" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div></div></div></div>
                <script>
                    function loadTimeTable() {
                        var teacherId = document.getElementById('teacherSelect').value;
                        if (teacherId) {
                            var xhr = new XMLHttpRequest();
                            xhr.open("GET", "time-table-back.php?teacherId=" + encodeURIComponent(teacherId), true);
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState == 4 && xhr.status == 200) {
                                    var response = JSON.parse(xhr.responseText);
                                    document.getElementById('teacherIdInput').value = response.teacherId;
                                    if (response.exists) {
                                        document.getElementById('uploadedImage').innerHTML = `
                                            <img src="${response.imagePath}" alt="Timetable Image">
                                            <form method="post" action="time-table-back.php">
                                                <input type="hidden" name="teacherId" value="${response.teacherId}">
                                            </form>
                                        `;
                                    } else {
                                        document.getElementById('uploadedImage').innerHTML = '';
                                    }
                                    document.getElementById('timetableImageSection').style.display = 'flex';
                                }
                            };
                            xhr.send();
                        }
                    }

                    function uploadImage() {
                        var formData = new FormData(document.getElementById('uploadForm'));
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "time-table-back.php", true);
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                alert(xhr.responseText);
                                loadTimeTable();
                            }
                        };
                        xhr.send(formData);
                    }

                    function changeImage() {
                        document.getElementById('uploadForm').reset();
                    }
                </script>
            </div>
        </div>
        
        
        <?php include_once('includes/footer.php'); ?>
        
    </div>
    
</div>

</div>

