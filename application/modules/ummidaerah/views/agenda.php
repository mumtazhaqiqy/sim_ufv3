

<div class="row">
    <?php echo form_open(); ?>
    <form action="" method="POST">
        <div class="col-sm-12">
            <div class="form-group input-group input-group-sm">
                <select class="form-control" name="date" id="date">
                    <option value="all">Tampilkan Semua</option>
                </select>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-default btn-flat" ><i class="fa fa-search"></i></button>
                </span>
            </div>
    </div>
</form> 
<br>
</div>
<div class="row">
	<div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<?= message_box('success', TRUE)?>
		<?= message_box('warning', TRUE)?>
	</div>
	<div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
		<div><?php echo $output; ?></div>
	</div>
</div>


<script>
$(document).ready(function(){
    
    var d = new Date();
    var n = d.getMonth();

    var monthArray = new Array();
    monthArray[0] = "January";
    monthArray[1] = "February";
    monthArray[2] = "March";
    monthArray[3] = "April";
    monthArray[4] = "May";
    monthArray[5] = "June";
    monthArray[6] = "July";
    monthArray[7] = "August";
    monthArray[8] = "September";
    monthArray[9] = "October";
    monthArray[10] = "November";
    monthArray[11] = "December";
    for(m = 0; m <= 11; m++) {
        var optn = document.createElement("OPTION");
        optn.text = monthArray[m];
        // server side month start from one
        optn.value = (m+1);
    
        // if june selected
        if ( m+1 == <?=$date?>) {
            optn.selected = true;
        }
    
        document.getElementById('date').options.add(optn);
    }

});
</script>