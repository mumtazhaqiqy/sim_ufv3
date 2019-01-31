<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <?php if($munaqasyah->status == 'terlaksana'):?>
            <div class="ribbon ribbon-top-right"><span>TERLAKSANA</span></div>
            <?php endif?>
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
                <?php if($munaqasyah->status == 'disetujui'):?>
                <div class="col-sm-12">
                    <a href="../berita_acara/<?=$munaqasyah->code?>/add" class="btn btn-app"><i class="fa fa-edit"></i> Buat berita acara</a>
                </div>
                <?php elseif($munaqasyah->status == 'terlaksana'):?>
                <div class="col-sm-12">
                    <a href="../berita_acara/<?=$munaqasyah->code?>/edit/<?=$munaqasyah->berita_acara_id?>" class="btn btn-app"><i class="fa fa-edit"></i> Edit berita acara</a>
                </div>
                <?php endif?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div><?php echo $output; ?></div>
	</div>
</div>