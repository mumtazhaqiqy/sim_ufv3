<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
</head>
<body>

<?php $i=0?>
<?php foreach($peserta as $p):?>
<?= short_if($i, 0, '' , '<pagebreak>')?>

    <div style="position: absolute; top: 143mm; left: 135mm; width: 100mm;">                
       <img src="<?=base_url('assets/uploads/image/').$p->foto?>" style="object-fit= cover; width:110px; height: 150px;" width="110px" alt="">
    </div>

<div style="text-align:center" class="no-sertifikat"><b>No. : <?=$p->no_sertifikat?>SU/UF - A/<?=$bulan_tahun?></b></div>
<div class="ml-cm mt-4">
    <table class="with-padding" width="100%">
        <tr>
            <td width="18%">Diberikan kepada andanda</td>
            <td width="1%"> : </td>
            <td width="76%"> </td>
        </tr>
        <tr>
            <td>Nama</td>
            <td> : </td>
            <td><?=$p->nama_lengkap?></td>
        </tr>
        <tr>
            <td>Tempat/Tanggal Lahir</td>
            <td> : </td>
            <td><?=$p->tempat_lahir?>, <?=date("d F Y",strtotime($p->tanggal_lahir))?> </td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td> : </td>
            <td><?=$p->alamat?></td>
        </tr>
        <tr>
            <td>Lembaga</td>
            <td> : </td>
            <td><?=$nama_lembaga?></td>
        </tr>
        <tr>
            <td colspan=3>Telah <span class="font-bold">Lulus</span> mengikuti <span class="font-bold"><?=$jenis_munaqasyah?> Al Qur’an Metode Ummi</span> pada tanggal <?=$tanggal_munaqasyah?>.</td>
        </tr>
        <tr>
            <td colspan=3>Semoga Allah SWT senantiasa meridloi dan memberikan ilmu yang bermanfaat. Amin.</td>
        </tr>
        
    </table>
    <table width="100%">
        <tr>
            <td width="60%"></td>
            <td>
                <div>
                    <div>Surabaya, <span style="text-decoration: underline;"><?=$tanggal_hijri?>.</span></div>
                    <div style="padding-left:73px"><?=$tanggal_masehi?>.</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>Kepala <?=$nama_lembaga?></td>
            <td>Direktur Ummi Foundation</td>
        </tr>
        <tr>
            <td height="200"><?=$kepala_lembaga?></td>
            <td>Drs. H. Masruri, M.Pd.</td>
        </tr>
    </table>
    
</div>

<pagebreak>

<?php $a = 1 ?>
<div class="font-bold text-center text-xl mt-8">DAFTAR NILAI MUNAQASYAH TARTIL AL-QURAN</div>
        <div class="font-bold text-center text-xl mb-4">METODE UMMI</div>
        
        <div class="" style="margin-left:200px">
            <table>
                <tr>
                    <td>Nama</td>
                    <td>: <?=$p->nama_lengkap?></td>
                </tr>
                <tr>
                    <td>No. Sertifikat</td>
                    <td>: <?=$p->no_sertifikat?></td>
                </tr>
            </table>
        </div>
        <br>
        <?php $nilai = db_get_all_data('view_rekap_nilai_munaqasyah', array('peserta_munaqasyah_id'=>$p->id_peserta_munaqasyah))?>

        <div class="" style="margin-left:270px">
            <table class="bordered">
            <tr style="border-bottom:5px solid black;" class="font-bold">
                <td>No</td>
                <td width="400">Materi</td>
                <td class="text-center" width="70">Nilai</td>
            </tr>
            <?php $i = 1?>
            <?php $count_nilai = count($nilai)?>
            <?php foreach ($nilai as $n): ?>
            <?php $sum[$a][$i] = $n->nilai;?>
            <tr>
                <td><?= $i?>.</td>
                <td><?=$n->display_name?></td>
                <td class="text-center"><?=$n->nilai?></td>
            </tr>
            <?php $i++?>
            <?php endforeach?>
            <tr style="border-top:5px solid black;">
                <td colspan="2" class="text-center">JUMLAH</td>
                <td class="text-center"><?= array_sum($sum[$a])?></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">RATA-RATA</td>
                <td class="text-center"><?= round(array_sum($sum[$a]) / $count_nilai,2)?></td>
            </tr>
        </table>
        </div>
        
        <div style="margin-left:200px">   
            <table>
                <tr>
                    <td width="450"></td>
                    <td>
                        <div>
                            <div>Surabaya, <span style="text-decoration: underline;"><?=$tanggal_hijri?>.</span></div>
                            <div style="padding-left:73px"><?=$tanggal_masehi?>.</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Ketua Ummi Daerah</td>
                    <td>Pentashih Al Qur’an Ummi Foundation</td>
                </tr>
                <tr>
                    <td height="200"><?=$ketua_umda?></td>
                    <td>H. A. Yusuf MS, M.Pd.</td>
                </tr>
            </table>
        </div>


<?php $a++?>
<?php endforeach?>

</body>
</html>