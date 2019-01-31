

<div class="row">
    <?php echo form_open(); ?>
    <form action="" method="POST">
        <div class="col-sm-12">
            <div class="form-group input-group input-group-sm">
                <select class="form-control" name="provinsi" id="provinsi">
                    <?php foreach($provinsi_list as $key => $list):?>
                    <option value="<?=$list->id?>"><?=$list->provinsi?></option>
                    <?php endforeach?>
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
    
    $('#provinsi').val("<?=$provinsi_id?>");

});
</script>