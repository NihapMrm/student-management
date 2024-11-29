

<?php

$base_url = 'http://localhost/royalsgrid/Student management system/'; 
?>
    <script src="<?php echo $base_url; ?>assets/vendors/js/vendor.bundle.base.js"></script>
    
    
    <script src="<?php echo $base_url; ?>assets/vendors/select2/select2.min.js"></script>
    <script src="<?php echo $base_url; ?>assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
    
    
    <script src="<?php echo $base_url; ?>assets/vendors/moment/moment.min.js"></script>
    <script src="<?php echo $base_url; ?>assets/vendors/daterangepicker/daterangepicker.js"></script>

    <script src="<?php echo $base_url; ?>assets/js/off-canvas.js"></script>
    <script src="<?php echo $base_url; ?>assets/js/bootstrap.min.js"></script>
    
    
    <script src="<?php echo $base_url; ?>assets/js/typeahead.js"></script>
    <script src="<?php echo $base_url; ?>assets/js/select2.js"></script>
    
    
    <script>
      function onReady(callback) {
    var intervalID = window.setInterval(checkReady, 1000);
    function checkReady() {
        if (document.getElementsByTagName('body')[0] !== undefined) {
            window.clearInterval(intervalID);
            callback.call(this);
        }
    }
}

function show(id, value) {
    document.getElementById(id).style.display = value ? 'block' : 'none';
}

onReady(function () {
    show('page', true);
    show('loading', false);
});
    </script>
  </body>

</html>