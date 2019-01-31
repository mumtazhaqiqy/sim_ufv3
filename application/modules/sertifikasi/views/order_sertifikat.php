<div class="row">
	<div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">PERMOHONAN SERTIFIKATpengguna - ( <?=$sertifikasi->code?> )</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
                <div class="col-sm-6">
                    <dl class="dl-horizontal">
                      <dt>Jenis Sertifikasi</dt>
                      <dd>Sertifikasi Guru Qur'an Ummi</dd>
                      <dt>Waktu Pelaksanaan</dt>
                      <dd><?= $sertifikasi->tanggal_pelaksanaan?></dd>
                      <dt>Tempat Pelaksanaan</dt>
                      <dd><?= $sertifikasi->tempat_pelaksanaan?></dd>
                    </dl>
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

