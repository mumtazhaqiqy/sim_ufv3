<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header">
                <i class="fa fa-user"></i> DATA PESERTA  
            </div>
            <div class="box-body">
                <div class="col-sm-12">
                    <dl class="dl-horizontal">
                        <dt>Nama Peserta</dt>
                        <dd><?= $nama_peserta?></dd>
                        <dt>Alamat</dt>
                        <dd><?= $alamat_peserta?></dd>
                        <dd></dd>
                        <dt>Sekolah / Lembaga</dt>
                        <dd><?= $nama_lembaga?></dd>
                        <dt>Kelas</dt>
                        <dd><?= $kelas?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-12">
    <div class="col-sm-12">
        <a class="btn btn-default btn-flat " href="../rekap_nilai/<?= $code?>"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row">
        
    <?php foreach($kriteria as $k):?>
    <?php $is_done = $this->db->query('
                SELECT t.peserta_munaqasyah_id, a.kriteria_nilai_id
                FROM tm_nilai_munaqasyah AS t
                LEFT JOIN tm_aspek_nilai AS a ON a.id_aspek_nilai = t.aspek_nilai_id
                WHERE kriteria_nilai_id = '.$k->id_kriteria_nilai.' and peserta_munaqasyah_id = '.$peserta_id.'
                GROUP BY kriteria_nilai_id
                ')->row();?>

    <?php if(count($is_done) == 1):?>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
            <div class="inner">
                <h3> <i class="fa fa-check"></i> </h3>

                <p><?= $k->display_name?></p>
            </div>
            <div class="icon">
                <i class="fa fa-certificate"></i>
            </div>
            <a href="../input_nilai/<?= $peserta_id ?>/<?= $k->id_kriteria_nilai?>/edit" class="small-box-footer">
                Input Nilai <i class="fa fa-arrow-circle-right"></i>
            </a>
            </div>
        </div>
        <?php else:?>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
            <div class="inner">
                <h3> <i class="fa fa-close"></i> </h3>

                <p><?= $k->display_name?></p>
            </div>
            <div class="icon">
                <i class="fa fa-certificate"></i>
            </div>
            <a href="../input_nilai/<?= $peserta_id ?>/<?= $k->id_kriteria_nilai?>/add" class="small-box-footer">
                Input Nilai <i class="fa fa-arrow-circle-right"></i>
            </a>
            </div>
        </div>

    <?php endif?>

    <?php endforeach?>

</div>


