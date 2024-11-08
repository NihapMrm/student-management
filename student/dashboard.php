<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!isset($_SESSION['sturecmsaid']) || $_SESSION['user_type'] !== 'student') {
  echo "<script>alert('You are not authorized to access this page. Please log in as a student.');</script>";
  echo "<script type='text/javascript'> document.location ='logout.php'; </script>";
  exit();
  }
  ?>

   
      
     <?php include_once('../includes/header.php');?>
      
      <div class="container-fluid page-body-wrapper">
        
      <?php include_once('../includes/sidebar.php');?>
      <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/dashboard.css" />

        <div class="main-panel">
          <div class="content-wrapper">
           
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                  <div class="banner d-flex flex-column flex-md-row justify-content-around align-items-center">
  <div class="w-100 align-items-center justify-content-center d-flex flex-column text-center p-4 order-2 order-md-1">
    <h3>Vision</h3>
    <p>To create a peaceful, disciplined, and respectful environment that promotes good character, knowledge, and respect towards others.</p>
  </div>
  
  <div class="w-100 align-items-center justify-content-center d-flex order-1 order-md-2 mb-3 mb-md-0">
    <img src="<?php echo $base_url; ?>assets/images/logo-icon.png" alt="">
  </div>
  
  <div class="w-100 align-items-center justify-content-center d-flex flex-column text-center p-4 order-3">
    <h3>Mission</h3>
    <p>To provide high-quality education that nurtures students with ethical values, discipline, and a sense of responsibility, helping them to become useful and respectable members of society.</p>
  </div>
</div>

                    <h2 class="text-center p-4">Gallery</h2>                
                    <div class="container">
              <div class="masonry-grid">
                <?php for ($i = 0; $i <= 10; $i++): ?>
                  <div class="masonry-item">
                    <img src="<?php echo $base_url; ?>assets/images/gallery-<?php echo $i; ?>.jpg" class="img-fluid" alt="Gallery Image <?php echo $i; ?>">
                  </div>
                <?php endfor; ?>
              </div>
            </div>
                  </div>
                </div>
              </div>
            
            </div>
           



          </div>
          
          
         <?php include_once('../includes/footer.php');?>
          
        </div>
        
      </div>
      
    </div>
    
