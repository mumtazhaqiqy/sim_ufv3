<style>
.h_iframe iframe {
    width:100%;
    height:100%;
}
.h_iframe {
    height: 100%;
    width:100%;
}
</style>
<div class="row">
    <div class="col-sm-4">
        <div class="bg-white px-4 py-4 rounded shadow">
            <img class="profile-user-img img-responsive img-circle" src="https://via.placeholder.com/350x350" alt="User profile picture">
            <p class="text-green text-center font-bold py-2 text-3xl">
                <?= $profile_pengguna->nama ?>
            </p>
            <p class="text-center text-grey">SD/MI</p>

            <p class="text-orange text-center py-4 font-semibold">No Register :
                <?= $profile_pengguna->no_register?>
            </p>

            <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                    <b class="px-4 text-grey-darker">Santri</b> <a class="pull-right" data-original-title="" title="">
                        <?= $profile_pengguna->jumlah_siswa ?></a>
                </li>

                <li class="list-group-item">
                    <b class="px-4 text-grey-darker">Guru Quran</b> <a class="pull-right" data-original-title="" title="">
                        <?= $profile_pengguna->jumlah_guru ?></a>
                </li>
                <li class="list-group-item">
                    <div class="progress-group pl-4 mt-5">
                        <span class="text-grey-darker progress-text">Status Sertifikasi</span>
                        <span class="progress-number text-orange"><b>
                                <?= $profile_pengguna->sudah_sertifikasi ?></b>/
                            <?= $profile_pengguna->belum_sertifikasi ?></span>
                        <div class="progress sm">
                            <div class="progress-bar progress-bar-aqua" style="width: <?= $profile_pengguna->sudah_sertifikasi / $profile_pengguna->jumlah_guru *100 ?>%"></div>
                        </div>
                    </div>
                </li>
            </ul>
            <a href="<?=base_url()?>ummidaerah/pengguna/index/edit/<?= $profile_pengguna->id_customer?>" class="btn btn-block bg-green-light text-white" data-original-title="" title=""><b>Edit Data</b></a>
        </div>
    </div>

    <div class="col-sm-8">
        <div class="row">
            <div class="col-md-12">
                <div class="bg-white rounded shadow px-4 py-4">
                    <p class="text-3xl font-bold text-blue-light mb-5 ml-4">Profil Lembaga </p>
                    <dl class="dl-horizontal mb-4">
                        <dt>Nama Lembaga</dt>
                        <dd><?= $profile_pengguna->nama ?></dd>
                        <dt>Jenis Lembaga</dt>
                        <dd><?= $profile_pengguna->jenis_lembaga ?></dd>
                        <dt>Alamat Lengkap</dt>
                        <dd><?= $profile_pengguna->alamat ?></dd>
                        <dt>Provinsi | Kota</dt>
                        <dd><?= $profile_pengguna->provinsi ?> | <?= $profile_pengguna->kabupaten ?></dd>
                        <dt>Kecamatan</dt>
                        <dd><?= $profile_pengguna->kecamatan ?></dd>
                        <dt>Nomor Telp</dt>
                        <dd><?= $profile_pengguna->nomor_telp ?></dd>
                        <dt>Email </dt>
                        <dd><?= $profile_pengguna->email ?></dd>
                        <dt>Pembelajaran Quran</dt>
                        <dd><?= $profile_pengguna->perpekan ?> Pertemuan perpekan | <?= $profile_pengguna->perhari ?> Sesi perhari</dd>
                        <dt>Nama Kepala Lembaga</dt>
                        <dd><?= $profile_pengguna->kepala_lembaga ?></dd>
                        <dt>Koordinator Quran</dt>
                        <dd><?= $profile_pengguna->koordinator ?></dd>
                        <dt>Ummi Dewasa</dt>
                        <dd><?= short_if($profile_pengguna->is_dewasa, 1, 'Ya', 'Tidak') ?></dd>
                        <dt>Turjuman</dt>
                        <dd><?= short_if($profile_pengguna->is_turjuman, 1, 'Ya', 'Tidak') ?></dd>
                        <dt>Metode Tahfidz</dt>
                        <dd><?= short_if($profile_pengguna->is_tahfidz, 1, 'Ya', 'Tidak') ?></dd>
                        <dt>Status Aktif</dt>
                        <dd>
                        <?php if ($profile_pengguna->status_aktif == 1):?>
                            <span class="label bg-green">Aktif</span>
                        <?php else:?>
                            <span class="label bg-red">Inaktif</span>
                        <?php endif?>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="row mt-12">
            <div class="col-sm-6 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                    <h3><?= $profile_pengguna->jumlah_guru ?></h3>

                    <p><?= $profile_pengguna->sudah_sertifikasi ?> Guru Bersertifikat</p>
                    </div>
                    <div class="icon">
                    <i class="fa fa-user"></i>
                    </div>
                    <a target="target_iframe" href="<?php echo base_url('ummidaerah/pengguna/guru_quran/');?><?= $profile_pengguna->id_customer?>" class="small-box-footer">Lihat daftar <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                    <h3><?= $profile_pengguna->jumlah_siswa ?></h3>

                    <p>Santri Ummi<p>
                    </div>
                    <div class="icon">
                    <i class="fa fa-bar-chart"></i>
                    </div>
                    <a target="target_iframe" href="<?php echo base_url('ummidaerah/perkembangan_siswa/index/');?><?= $profile_pengguna->id_customer?>" class="small-box-footer">Lihat daftar <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <IFRAME width="100%" name="target_iframe" id="iframe-container" SRC="<?php echo base_url('ummidaerah/pengguna/guru_quran/');?><?= $profile_pengguna->id_customer?>" frameborder="0" scrolling="no"></IFRAME>

            </div>
        </div>


    </div>

</div>

<script>
window.addEventListener('message', function(e) {
	// message passed can be accessed in "data" attribute of the event object
	var scroll_height = e.data;

	document.getElementById('iframe-container').style.height = scroll_height + 'px';
} , false);
</script>
