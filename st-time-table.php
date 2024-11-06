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
 
      
     <?php include_once('includes/header.php');?>
      
      <div class="container-fluid page-body-wrapper">
        
      <?php include_once('includes/sidebar.php');?>
        
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Students Time Table </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> Students Time Table</li>
                </ol>
              </nav>
            </div>
            <div >
            <?php




$classes = "SELECT DISTINCT ClassName FROM tblclass";
$query = $dbh->prepare($classes);
$query->execute();
$results = $query->fetchAll();

?>
<div class="d-flex flex-row gap">
    
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

    
    <select name="section" id="sectionSelect" class="form-control" onchange="loadTimeTable()">
        <option value="">Select a Section</option>
    </select>
</div>
<style>
    #uploadedImage img{
        width: 400px;
    }
</style>

<div id="timetableImageSection" style="display: none;">
<div id="uploadedImage" class="pt-3"></div>
<div class="d-flex w-100 justify-content-center align-items-center">
<form id="uploadForm" enctype="multipart/form-data" class="forms-sample d-flex flex-column align-items-center gap">
        <input type="hidden" name="classID" id="classIDInput" class="form-control">
        <input type="file" name="timetableImage" accept="image/*" class="form-control">
        <button type="button" onclick="uploadImage()" class="btn btn-primary">Upload</button>
    </form>
</div>
    
   
</div>

<script>
    function loadSections(className) {
        document.getElementById('sectionSelect').innerHTML = "<option value=''>Select a Section</option>";
        document.getElementById('timetableImageSection').style.display = 'none';

        if (className !== "") {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "time-table-back.php?className=" + encodeURIComponent(className), true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('sectionSelect').innerHTML += xhr.responseText;
                }
            };
            xhr.send();
        }
    }

    function loadTimeTable() {
        var className = document.getElementById('classSelect').value;
        var sectionName = document.getElementById('sectionSelect').value;

        if (className && sectionName) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "time-table-back.php?className=" + encodeURIComponent(className) + "&sectionName=" + encodeURIComponent(sectionName), true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    document.getElementById('classIDInput').value = response.classID;
                    if (response.exists) {
                        document.getElementById('uploadedImage').innerHTML = `
                            <img src="${response.imagePath}" alt="Timetable Image">
                            <form method="post" action="time-table-back.php">
                                <input type="hidden" name="classID" value="${response.classID}">
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
          
          
         <?php include_once('includes/footer.php');?>
          
        </div>
        
      </div>
      
    </div>
    
