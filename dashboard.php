<?php
session_start();

include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']==0)) {
  header('location:logout.php');
  } else{
   
  ?> 

      
     <?php include_once('includes/header.php');?>
      
      <div class="container-fluid page-body-wrapper">
        
        <?php include_once('includes/sidebar.php');?>
        
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12">
                       <div style="background-image: url();"></div>
                      </div>
                    </div>
                  
                   
                    
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
           
            
          </div>
          
          
         <?php include_once('includes/footer.php');?>
          
        </div>
        
      </div>
      
    </div>

    
    
   <?php }  ?>