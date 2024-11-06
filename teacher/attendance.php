<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!isset($_SESSION['sturecmsaid']) || $_SESSION['user_type'] !== 'teacher') {
  echo "<script>alert('You are not authorized to access this page. Please log in as a teacher.');</script>";
  echo "<script type='text/javascript'> document.location ='logout.php'; </script>";
  exit();
  } else{

?>

      
     <?php include_once('../includes/header.php');?>
      
      <div class="container-fluid page-body-wrapper">
        
        <?php include_once('../includes/sidebar.php');?>
        
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
            <div>
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


<select name="section" id="sectionSelect" class="form-control" onchange="loadStudents()">
    <option value="">Select a Section</option>
</select>

    <input type="date" id="attendanceDate" class="form-control" onchange="loadStudents()">

    </div>

<div id="studentList">
    
</div>

<button onclick="submitAttendance()" id="attendanceSubmit" class="btn btn-primary" style="display: none;">Submit Attendance</button>


<script>

function loadSections(className) {
    
    document.getElementById('sectionSelect').innerHTML = "<option value=''>Select a Section</option>";

    if (className !== "") {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "attendanceback.php?className=" + encodeURIComponent(className), true); 
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById('sectionSelect').innerHTML += xhr.responseText; 
            }
        };
        xhr.send();
    }
}


function loadStudents() {
    var className = document.getElementById('classSelect').value;
    var section = document.getElementById('sectionSelect').value;
    var date = document.getElementById('attendanceDate').value;
    var attendanceSubmit = document.getElementById('attendanceSubmit');

    
    var studentListDiv = document.getElementById('studentList');
    studentListDiv.innerHTML = ""; 

    if (className !== "" && section !== "" && date !== "") {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "attendanceback.php?className=" + encodeURIComponent(className) + "&section=" + encodeURIComponent(section) + "&date=" + encodeURIComponent(date), true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                studentListDiv.innerHTML = xhr.responseText; 
            }
        };
        xhr.send();

        attendanceSubmit.style.display = 'block';
    }
}

function submitAttendance() {
    var className = document.getElementById('classSelect').value;
    var section = document.getElementById('sectionSelect').value;
    var date = document.getElementById('attendanceDate').value;

    

    
    var attendanceData = {};
    var radios = document.querySelectorAll('input[type="radio"]');
    radios.forEach(function(radio) {
        if (radio.checked) {
            attendanceData[radio.name] = radio.value; 
        }
    });

    
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "attendanceback.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert(xhr.responseText); 
        }
    };
    xhr.send(JSON.stringify({ className: className, section: section, date: date, attendance: attendanceData }));
}


</script>



                  </div>
                </div>
              </div>
            </div>
          </div>
          
          
         <?php include_once('../includes/footer.php');?>
          
        </div>
        
      </div>
      
    </div>
    
    <?php }  ?>