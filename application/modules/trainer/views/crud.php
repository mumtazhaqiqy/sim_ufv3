<div class="row">
	<div class="col-xl-8 col-lg-10 col-md-12 col-sm-12 col-xs-12">
		<?= message_box('success', TRUE)?>
		<?= message_box('warning', TRUE)?>
	</div>
	<div class="col-xl-8 col-lg-10 col-md-12 col-sm-12 col-xs-12">
		<div><?php echo $output; ?></div>
	</div>
</div>

<script>
$(document).ready(function(){
    $('input[name="hafal_quran"]').on('ifChanged', function(event){
        // alert($(this).val() + ' callback');
    
        if($(this).val() == 0 ){
            $('#jumlah_hafalan_field_box').hide();
        }else {
            $('#jumlah_hafalan_field_box').show();
        }
    
	});
	
	$('#addSkTrainer').click(add_sk_trainer)    
    function add_sk_trainer(){
    var sktrainerItem=$('.form-add-sk-trainer:last').clone();
    sktrainerItem.children().children().val('');
    sktrainerItem.insertAfter('.form-add-sk-trainer:last');
    $('.remove-pm').click(function(){$(this).parent().parent().remove()}
    )}
    
});



</script>