<nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
        
            
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php">
              <i class="icon-home menu-icon"></i>
                <span class="menu-title">Dashboard</span>

              </a>
            </li>

            <?php if ($_SESSION['user_type'] === 'student'): ?>
              <li class="nav-item">
              <a class="nav-link" href="class.php">
              <i class="icon-layers menu-icon"></i>
              <span class="menu-title">Class</span>
              </a>
            </li>
            <?php endif; ?>


            <?php if ($_SESSION['user_type'] !== 'student'): ?>

            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <i class="icon-layers menu-icon"></i>
                <span class="menu-title">Class</span>
              </a>
              <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                <?php if ($_SESSION['user_type'] === 'admin'): ?>
                 <li class="nav-item"> <a class="nav-link" href="add-class.php">Add Class</a></li>
                  <?php endif; ?>

                  <li class="nav-item"> <a class="nav-link" href="manage-class.php">Manage Class</a></li>
                </ul>
              </div>
            </li>
            <?php endif; ?>
            <?php if ($_SESSION['user_type'] === 'admin'): ?>

<li class="nav-item">
  <a class="nav-link" data-toggle="collapse" href="#ui-subject" aria-expanded="false" aria-controls="ui-basic">
    <i class="icon-layers menu-icon"></i>
    <span class="menu-title">Subject</span>
  </a>
  <div class="collapse" id="ui-subject">
    <ul class="nav flex-column sub-menu">
    
     <li class="nav-item"> <a class="nav-link" href="add-subject.php">Add Subject</a></li>
     

      <li class="nav-item"> <a class="nav-link" href="manage-subject.php">Manage Subject </a></li>
    </ul>
  </div>
</li>
<?php endif; ?>

            <?php if ($_SESSION['user_type'] !== 'student'): ?>
            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#ui-basic1" aria-expanded="false" aria-controls="ui-basic1">
                <i class="icon-people menu-icon"></i>
                <span class="menu-title">Students</span>
              </a>
              <div class="collapse" id="ui-basic1">
                <ul class="nav flex-column sub-menu">
                <?php if ($_SESSION['user_type'] === 'admin'): ?>

                  <li class="nav-item"> <a class="nav-link" href="add-students.php">Add Students</a></li>
                  <?php endif; ?>

                  <li class="nav-item"> <a class="nav-link" href="manage-students.php">Manage Students</a></li>
                </ul>
              </div>
            </li>
            <?php endif; ?>

            <?php if ($_SESSION['user_type'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#teachers" aria-expanded="false" aria-controls="ui-basic1">
                <i class="icon-people menu-icon"></i>
                <span class="menu-title">Teachers</span>
              </a>
              <div class="collapse" id="teachers">
                <ul class="nav flex-column sub-menu">
                <?php if ($_SESSION['user_type'] === 'admin'): ?>

                  <li class="nav-item"> <a class="nav-link" href="add-teachers.php">Add Teachers</a></li>
                  <?php endif; ?>

                  <li class="nav-item"> <a class="nav-link" href="manage-teachers.php">Manage Teachers</a></li>
                </ul>
              </div>
            </li>
            <?php endif; ?>
            <?php if ($_SESSION['user_type'] !== 'admin' ): ?>
            <li class="nav-item">
              <a class="nav-link" href="teachers.php">
              <i class="icon-flag menu-icon"></i>
              <span class="menu-title">Teachers</span>
              </a>
            </li>
            <?php endif; ?>

            <?php if ($_SESSION['user_type'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#marks" aria-expanded="false" aria-controls="ui-basic1">
                <i class="icon-people menu-icon"></i>
                <span class="menu-title">Marks</span>
              </a>
              <div class="collapse" id="marks">
                <ul class="nav flex-column sub-menu">
                <?php if ($_SESSION['user_type'] === 'admin'): ?>

                  <li class="nav-item"> <a class="nav-link" href="add-marks.php">Add Marks</a></li>
                  <?php endif; ?>

                  <li class="nav-item"> <a class="nav-link" href="view-marks.php">View Marks</a></li>
                </ul>
              </div>
            </li>
            <?php endif; ?>

            <?php if ($_SESSION['user_type'] !== 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="view-marks.php">
              <i class="icon-flag menu-icon"></i>
              <span class="menu-title">Results</span>
              </a>
            </li>
            <?php endif; ?>

            <?php if ($_SESSION['user_type'] !== 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="attendance.php">
              <i class="icon-flag menu-icon"></i>
              <span class="menu-title">Attendance</span>
              </a>
            </li>
            <?php endif; ?>

            <?php if ($_SESSION['user_type'] !== 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="time-table.php">
              <i class="icon-flag menu-icon"></i>
              <span class="menu-title">Time Table</span>
              </a>
            </li>
            <?php endif; ?>

            <?php if ($_SESSION['user_type'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#timetable" aria-expanded="false" aria-controls="timetable">
                <i class="icon-doc menu-icon"></i>
                <span class="menu-title">Time Table</span>
                
              </a>
              <div class="collapse" id="timetable">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="st-time-table.php"> Student Time Table </a></li>
                  <li class="nav-item"> <a class="nav-link" href="tr-time-table.php"> Teacher Time Table </a></li>
                </ul>
              </div>
            </li>
            <?php endif; ?>
            <?php if ($_SESSION['user_type'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#auth1" aria-expanded="false" aria-controls="auth">
                <i class="icon-doc menu-icon"></i>
                <span class="menu-title">Public Notice</span>
              </a>
              <div class="collapse" id="auth1">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="add-public-notice.php"> Add Public Notice </a></li>
                  <li class="nav-item"> <a class="nav-link" href="manage-public-notice.php"> Manage Public Notice </a></li>
                </ul>
              </div>

            </li> 
            <?php endif; ?>

            <?php if ($_SESSION['user_type'] !== 'admin'): ?>
              <li class="nav-item">
              <a class="nav-link" href="notice.php">
              <i class="icon-doc menu-icon"></i>
              <span class="menu-title">Notice</span>
              </a>
            </li>
              <?php endif; ?>
            <?php if ($_SESSION['user_type'] !== 'student'): ?>
            <li class="nav-item">
              <a class="nav-link" href="search.php">
                <i class="icon-magnifier menu-icon"></i>
                <span class="menu-title">Search Student</span>
              </a>
            </li>
            <?php endif; ?>
            
            </li>
          </ul>
        </nav>