
<!-- Javascript -->
<script src="<?php echo base_url ?>assets/plugins/popper.min.js"></script>
<script src="<?php echo base_url ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>

<!-- Charts JS -->
<!-- <script src="<?php echo base_url ?>assets/plugins/chart.js/chart.min.js"></script> 
<script src="<?php echo base_url ?>assets/js/index-charts.js"></script> -->

<!-- Page Specific JS -->
<script src="<?php echo base_url ?>assets/js/app.js"></script>

<!-- preview pic -->
<script>
    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };
</script>

