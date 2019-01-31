<?php if(!$this->ion_auth->logged_in()){?>
    <div class="row">
        <div class="col-md-12 p-4 ml-4">
            <a href="<?=base_url()?>sertifikasi/pendaftaran" title="kembali" class="add-anchor add_button btn btn-primary btn-flat">
                <i class="fa fa-arrow-left"></i>
                <span class="add">KEMBALI</span>
            </a>            
        </div>
    </div>
<?php } ?>

<div class="row">
	<div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">SERTIFIKASI GURU AL-QURAN UMMI - ( <?=$sertifikasi->code?> )</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
                <div class="col-sm-6">
                    <dl class="dl-horizontal">
                      <dt>Jenis Sertifikasi</dt>
                      <dd>Sertifikasi Guru Qur'an Ummi</dd>
                      <dt>Waktu Pelaksanaan</dt>
                      <dd><?= date('d', strtotime($sertifikasi->hari1))?>, <?= date('d', strtotime($sertifikasi->hari2))?>, <?= date('d M Y', strtotime($sertifikasi->hari3))?></dd>
                      <dt>Tempat Pelaksanaan</dt>
                      <dd><?= $sertifikasi->tempat_pelaksanaan?></dd>
                    </dl>
                </div>
                <div class="col-sm-6">
                    <?php if($this->ion_auth->logged_in()):?>
                    <?php $order = db_get_row('order_sertifikat_guru', 'sertifikasi_id = '.$sertifikasi->id)?>
                    <?php if($order->sertifikasi_id !== $sertifikasi->id){?>
                        <a href="<?=base_url()?>sertifikasi/order_sertifikat/crud/<?=$sertifikasi->code?>/add" class="btn btn-primary">Buat Permohonan Sertifikat</a>
                        <?php } else {?>
                            <b>Permohonan order sertifikat telah dibuat</b><br><span>Status order sertifikat : <?= $order->status?></span><br>
                    <?php }?>
                    <?php endif?>

                </div>
            </div>
            
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div><?php echo $output; ?></div>
	</div>
</div>
<?php if($this->ion_auth->in_group(array('admin','admin_data'))):?>
    <?php if($order->status == "permohonan"):?>
    <script type="text/javascript">
    $(document).ready(function(){
    $('.tDiv3').append('<a href="<?=base_url("sertifikasi/peserta/generate/").$sertifikasi->code?>" class="btn btn-info btn-flat"><i class="fa fa-gear"></i> Generate</a>');
    });
    </script>
    <?php elseif($order->status == "diproses"):?>
    <script type="text/javascript">
    $(document).ready(function(){
    $('.tDiv3').append('<a href="<?=base_url("sertifikasi/peserta/pdf_sertifikat/").$sertifikasi->code?>" target="_blank" class="btn btn-info btn-flat"><i class="fa fa-print"></i> Print Sertifikat</a>');
    });
    </script>
    <?php endif?>

<?php endif?>

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
    
    $('input[name="sudah_mengajar"]').on('ifChanged', function(event){
        // alert($(this).val() + ' callback');
    
        if($(this).val() == 0 ){
            $('#di_lembaga_field_box').hide();
        }else {
            $('#di_lembaga_field_box').show();
        }
    
    });
    
    $('#addPengalamanMengajar').click(add_pengalaman_mengajar)
    
    function add_pengalaman_mengajar(){
    var pengalamanMemngajarItem=$('.form-pengalaman-mengajar:last').clone();
    pengalamanMemngajarItem.children().children().val('');
    pengalamanMemngajarItem.insertAfter('.form-pengalaman-mengajar:last');
    $('.remove-pm').click(function(){$(this).parent().parent().remove()}
    )}
    
    $('#addPengalamanKursus').click(add_pengalaman_kursus)
    
    function add_pengalaman_kursus(){
    var pengalamanMemngajarItem=$('.form-pengalaman-kursus:last').clone();
    pengalamanMemngajarItem.children().children().val('');
    pengalamanMemngajarItem.insertAfter('.form-pengalaman-kursus:last');
    $('.remove-pk').click(function(){$(this).parent().parent().remove()}
    )}   

});




</script>
