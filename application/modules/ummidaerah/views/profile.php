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
                <div class="text-green-light text-center text-4xl font-bold font-sans"><?=$j_trainer?></div>
                <div class="text-grey text-center">Trainer Daerah</div>
            </div>

            <div class="flex-1 bg-white px-4 py-2 m-2 rounded-sm shadow">
                <div class="text-green-light text-center text-4xl font-bold font-sans"><?=$j_lembaga?></div>
                <div class="text-grey text-center">Lembaga</div>
            </div>

            <div class="flex-1 bg-white px-4 py-2 m-2 rounded-sm shadow">
                <div class="text-green-light text-center text-4xl font-bold font-sans"><?=$j_santri?></div>
                <div class="text-grey text-center">Santri</div>
            </div>

            <div class="flex-1 bg-white px-4 py-2 m-2 rounded-sm shadow">
                <div class="text-green-light text-center text-4xl font-bold font-sans"><?=$j_guru?></div>
                <div class="text-grey text-center">Guru Quran</div>
            </div>
        </div>
        <div class="flex mb-4">
            <div class="flex-1 bg-white px-4 py-2 m-2 rounded-sm shadow">
                <div class="progress sm mt-4 mb-2">
                    <div class="progress-bar progress-bar-aqua" style="width: <?= round($j_guru_ss / ($j_guru_bs+$j_guru_ss) * 100)?>%"></div>
                </div>
                <div class="font-bold text-grey-darker text-center"><?= $j_guru_ss ?> Sudah sertifikasi | <?= $j_guru_bs?> Belum Sertifikasi ( <?= round($j_guru_ss / ($j_guru_bs+$j_guru_ss) * 100)?>% )</div>
            </div>
        </div>
        <div class="flex mb-2">
            <div class="flex-1 bg-white px-4 py-2 m-2 rounded-sm shadow">
                <div class="progress sm mt-4 mb-2">
                    <div class="progress-bar progress-bar-aqua" style="width: <?=round($j_lembaga_su/($j_lembaga)*100)?>%"></div>
                </div>
                <div class="font-bold text-grey-darker text-center">Status Update Lembaga <?= $j_lembaga_su ?> : <?= $j_lembaga-$j_lembaga_su ?> ( <?= round($j_lembaga_su/($j_lembaga)*100)?>% ) sudah update lembaga</div>
            </div>
        </div>

        <div class="flex p-2">
          <div class="box">
            <div class="box-heade">

            </div>
            <div class="box-body">
              <table class="table table-condensed">
                <tbody><tr>
                  <th style="width: 10px">#</th>
                  <th>Jenis Lembaga</th>
                  <th>Line graph</th>
                  <th style="width: 40px">Jumlah</th>
                </tr>
                <?php $i=1?>
                <?php foreach ($lembaga_by_jenis as $key => $l):?>
                <tr>
                  <td><?=$i?>.</td>
                  <td><?= $l->jenis_lembaga ?></td>
                  <td>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-info" style="width: <?= round($l->jumlah/($j_lembaga)*100) ?>%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-teal float-right "><?= $l->jumlah?></span></td>
                </tr>
                <?php $i++?>
              <?php endforeach?>
              </tbody>
            </table>
            </div>

          </div>
        </div>
    </div>

</div>
