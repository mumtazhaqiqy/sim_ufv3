<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="folio">

    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">

        <!-- Write HTML just like a web page -->
        <div class="ml-16 mr-16">


            <div style="text-align:center">
                <img src="<?=base_url('assets/logo_ummi.png')?>" width="150px">
            </div>
            <p style="text-align:center;" >DATA LEMBAGA PENGGUNA METODE UMMI</p>
            <table width="100%">
                <tbody>
                    <tr>
                        <td><b>No Register</b></td>
                        <td colspan=2>: <?= $customer->no_register?></td>
                    </tr>
                    <tr>
                        <td><b>Nama Lembaga</b></td>
                        <td colspan=2>: <?= $customer->nama?> | <?=$customer->jenis_lembaga?></td>
                    </tr>
                    <tr>
                        <td><b>Alamat Lembaga</b></td>
                        <td colspan=2>: <?= $customer->alamat?></td>
                    </tr>
                    <tr>
                        <td><b></b></td>
                        <td colspan=2>: <?= $customer->provinsi?> | <?= $customer->kabupaten?> | <?= $customer->kecamatan?></td>
                    </tr>
                    <tr>
                        <td><b>Telp / Fax</b></td>
                        <td colspan=2>: <?= $customer->nomor_telp?></td>
                    </tr>
                    <tr>
                        <td><b>email</b></td>
                        <td colspan=2>: <?= $customer->email?></td>
                    </tr>
                    <tr>
                        <td><b>Mulai Menggunakan</b></td>
                        <td colspan=2>: <?= $customer->tanggal_mulai?></td>
                    </tr>
                    <tr>
                        <td><b>Nama Kepala Sekolah</b></td>
                        <td>: <?= $customer->kepala_lembaga?></td>
                        <td>No HP : <?= $customer->hp_kepala_lembaga?></td>
                    </tr>
                    <tr>
                        <td><b>Nama Koordinator</b></td>
                        <td>: <?= $customer->koordinator?></td>
                        <td>No HP : <?= $customer->hp_koordinator?></td>
                    </tr>
                    <tr>
                        <td><b>Pembelajaran Quran</b></td>
                        <td colspan=2>: <?= $customer->perpekan?> Hari perpekan | <?= $customer->perhari?> Sesi perhari</td>
                    </tr>
                    <tr>
                        <td><b>Jumlah Murid</b></td>
                        <td colspan=2>: <?= $customer->jumlah_siswa?> Siswa</td>
                    </tr>
                    <tr>
                        <td><b>Jumlah Guru</b></td>
                        <td colspan=2>: <?= $customer->jumlah_guru?> Guru | (<?= $customer->sudah_sertifikasi?> / <?= $customer->belum_sertifikasi?>)</td>
                    </tr>
                </tbody>
            </table>
            <p class="text-center">DATA PERKEMBANGAN SISWA</p>

            <div class="mt-8">
            <table class="bordered">
              <thead>
                    <tr>
                        <th>Jilid \ Kelas</th>
                        <?php foreach($kelas as $k):?>
                        <th><?= $k->kelas?></th>
                        <?php endforeach?>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($jilid as $j): ?>
                    <?php $jumlah = $this->db->query('SELECT '.$j->kode.' as jumlah  FROM perkembangan_siswa WHERE `unique` = "'.$unique.'"');?>
                    <?php if ($jumlah->row()->jumlah != 0):?>
                    <tr>
                        <td style="text-align:center"><?= $j->jilid?></td>
                        <?php foreach($kelas as $k):?>
                        <?php $value = db_get_row('jumlah_santri','unique = "'.$unique.'" AND kelas_id = '.$k->id.' AND jilid_id = '.$j->id)->jumlah_santri;?>
                        <?php if($value == ''):?>
                        <td style="text-align:center">-</td>
                        <?php else:?>
                        <td style="text-align:center"><?=$value?></td>
                        <?php endif?>
                        <?php endforeach?>
                        <td style="text-align:center"><?=$jumlah->row()->jumlah?></td>
                    </tr>
                    <?php endif?>
                    <?php endforeach?>
                    <tr>
                      <th>Jumlah</th>
                      <?php foreach($kelas as $k):?>
                      <?php $val = db_get_all_data('jumlah_santri', array('unique' => $unique, 'kelas_id' => $k->id)) ?>
                      <?php foreach ($val as $key => $value) {
                        $ar[$k->id][] = $value->jumlah_santri;
                      }?>
                      <?php if(!empty($ar[$k->id])):?>
                        <?php $total = array_sum($ar[$k->id])?>
                      <?php else:?>
                        <?php $total='-';?>
                      <?php endif?>
                      <th class="text-center"><?= $total?></th>
                      <?php endforeach?>
                      <?php $totalval = db_get_all_data('jumlah_santri', array('unique' => $unique))?>
                      <?php foreach ($totalval as $tv){
                        $gt[] = $tv->jumlah_santri;
                      }?>
                      <th class="text-center"><?= array_sum($gt)?></th>
                    </tr>
                </tbody>
            </table>
            </div>
            <br><br>
            <table width="100%">
              <tr>
                <td width="60%">Mengetahui</td>
                <td><?= $customer->kabupaten?>,<?= DatetoIndo($tanggal)?></td>
              </tr>
              <tr>
                <td>Kepala Lembaga</td>
                <td>Koordinator</td>
              </tr>
              <tr>
                <td height="200px"><?= $customer->kepala_lembaga?></td>
                <td><?= $customer->koordinator?></td>
              </tr>

            </table>
        </div>

    </section>

    <pagebreak>

    <section class="sheet padding-15mm">
    <p class="text-center">DAFTAR GURU</p>
    <p class="text-center"><?= $customer->nama?></p>

            <table class="bordered">
                <thead>
                    <tr>
                        <th width="20px" class="borderno">No</th>
                        <th width="200px" class="borderno">Nama Guru</th>
                        <th width="200px" class="borderno">Alamat</th>
                        <th width="100px" class="borderno">Status Sertifikat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1?>
                    <?php foreach($guru as $g):?>
                    <tr>
                        <td class="borderno"><?=$no?></td>
                        <td class="borderno">
                        <div><?= $g->nama_guru?></div>
                        <div><?= $g->nomor_hp?></div>
                        </td>
                        <td class="borderno"><?= $g->alamat?></td>
                        <td class="borderno">
                        <div><?= short_if($g->status_sertifikasi,0,'BELUM','SUDAH')?></div>
                        <div><?=$g->no_sertifikat?></div>

                        </td>
                    </tr>
                    <?php $no++?>
                    <?php endforeach?>
                </tbody>
            </table>
    </section>

</body>

</html>
