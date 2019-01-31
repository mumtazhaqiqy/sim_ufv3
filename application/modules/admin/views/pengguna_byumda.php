

<div class="row">
    <?php echo form_open(); ?>
    <form action="" method="POST">
        <div class="col-sm-12">
            <div class="form-group input-group input-group-sm">
                <select class="form-control" name="ummi_daerah" id="ummi_daerah">
                    <?php foreach($ummi_daerah_list as $key => $list):?>
                    <option value="<?=$list->id_ummi_daerah?>"><?=$list->ummi_daerah?></option>
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
    
    $('#ummi_daerah').val("<?=$ummi_daerah_id?>");

});
</script>