<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-header">
                Header
            </div>
            <div class="box-body">
                <div class="col-sm-12">
                    <dl class="dl-horizontal">
                        <dt>Jenis Sertifikasi</dt>
                        <dd><?= $jenis_sertifikasi?></dd>
                        <dt>Nama Lembaga</dt>
                        <dd><?= $nama_lembaga?></dd>
                        <dd><?= $alamat_lembaga?></dd>
                        <dd>Kepala Sekolah : <?= $nama_ks?></dd>
                        <dt>Tanggal Pelaksanaan</dt>
                        <dd><?=$tanggal_pelaksanaan?></dd>
                        <dt>Trainer</dt>
                        <dd>trainer 1, Trainer 2
                        </dd>
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