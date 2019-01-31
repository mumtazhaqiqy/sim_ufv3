<div class="row" >
    <div class="col-sm-4">
            <!-- Profile Image -->
        <div class="box box-primary">
            <div class="box-body">
                <img class="profile-user-img img-responsive img-circle" src="<?=base_url('assets/img/logo/logo.png')?>"
                    alt="User profile picture">

                <p class="text-green text-center font-bold text-3xl">
                    <?= $ummi_daerah->ummi_daerah?>
                </p>

                <p class="text-orange text-center py-4 font-semibold">SK UMDA :
                    <?= $ummi_daerah->sk_umda?>
                </p>
                <dl class="mb-4 px-4 mt-2 text-center">
                    <dt>Alamat Lengkap</dt>
                    <dd class="text text-grey-darker">
                        <?= $ummi_daerah->alamat_lengkap?>
                    </dd>
                    <dt>Provinsi | Kota | Kecamatan</dt>
                    <dd class="text text-grey-darker">
                        <?= $ummi_daerah->provinsi?> |
                        <?= $ummi_daerah->kabupaten?> | <?= $ummi_daerah->kecamatan?>
                    </dd>   
                    <dt>Nomor Telp</dt>
                    <dd class="text text-grey-darker">
                        <?= $ummi_daerah->nomor_telp?>
                    </dd>
                    <dt>Email </dt>
                    <dd class="text text-grey-darker">
                        <?= $ummi_daerah->email?>
                    </dd>
                    
                    <dt>Ketua </dt>
                    <dd class="text text-grey-darker">
                    <?= $ummi_daerah->ketua_umda?>
                    </dd>
                    <dt>Manager Buku </dt>
                    <dd class="text text-grey-darker">
                    <?= $ummi_daerah->manajer_buku?>
                    </dd>
                    <dt>Admin </dt>
                    <dd class="text text-grey-darker">
                    <?= $ummi_daerah->admin?>
                    </dd> 
                </dl>
            </div>
            <div class="box-footer">
                <a href="<?=BASE_URL()?>ummidaerah/crud/edit/<?=$ummi_daerah->id_ummi_daerah?>" class="btn btn-block bg-green-light text-white" data-original-title="" title=""><b>Edit Data</b></a>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    
    <div class="col-md-8">
        <div class="flex">
            
            <div class="flex-1 bg-white px-4 py-2 m-2 rounded-sm shadow">
                <div class="text-green-light text-center text-4xl font-bold font-sans"><?=$kondisi_umda->jumlah_trainer?></div>
                <div class="text-grey text-center">Trainer Daerah</div>
            </div>
            
            <div class="flex-1 bg-white px-4 py-2 m-2 rounded-sm shadow">
                <div class="text-green-light text-center text-4xl font-bold font-sans"><?=$kondisi_siswa_umda->jumlah_lembaga?></div>
                <div class="text-grey text-center">Lembaga</div>
            </div>
            
            <div class="flex-1 bg-white px-4 py-2 m-2 rounded-sm shadow">
                <div class="text-green-light text-center text-4xl font-bold font-sans"><?=$kondisi_siswa_umda->jumlah_siswa?></div>
                <div class="text-grey text-center">Santri</div>
            </div>
            
            <div class="flex-1 bg-white px-4 py-2 m-2 rounded-sm shadow">
                <div class="text-green-light text-center text-4xl font-bold font-sans"><?=$kondisi_guru_umda->jml_guru?></div>
                <div class="text-grey text-center">Guru Quran</div>
            </div>
        </div>
        <div class="flex mb-4">
            <div class="flex-1 bg-white px-4 py-2 m-2 rounded-sm shadow">
                <div class="progress sm mt-4 mb-2">
                    <div class="progress-bar progress-bar-aqua" style="width: <?= round($kondisi_guru_umda->sdh_sertifikasi / ($kondisi_guru_umda->blm_sertifikasi+$kondisi_guru_umda->sdh_sertifikasi) * 100)?>%"></div>
                </div>
                <div class="font-bold text-grey-darker text-center"><?= $kondisi_guru_umda->sdh_sertifikasi ?> Sudah sertifikasi | <?= $kondisi_guru_umda->blm_sertifikasi ?> Belum Sertifikasi ( <?= round($kondisi_guru_umda->sdh_sertifikasi / ($kondisi_guru_umda->blm_sertifikasi+$kondisi_guru_umda->sdh_sertifikasi) * 100)?>% )</div>
            </div>
        </div>
        <div class="flex mb-2">
            <div class="flex-1 bg-white px-4 py-2 m-2 rounded-sm shadow">
                <div class="progress sm mt-4 mb-2">
                    <div class="progress-bar progress-bar-aqua" style="width: <?=$percent_update?>%"></div>
                </div>
                <div class="font-bold text-grey-darker text-center">Status Update Lembaga <?= $sudah_update ?> : <?= $kondisi_umda->jumlah_lembaga ?> ( <?= round($percent_update)?>% ) sudah update lembaga</div>
            </div>
        </div>
        <div class="flex px-2">
                
        </div>
    </div>

</div>


