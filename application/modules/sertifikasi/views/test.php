<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />

</head>
<body style="font-size:14px;">

    <?php $i=0?>
    <?php foreach($data_peserta as $key => $p):?>
    <?= short_if($i, 0, '' , '<pagebreak>')?>

    <div style="position: absolute; top: 155mm; left: 160mm; width: 100mm;">                
       <img src="<?=base_url('assets/uploads/image/').$p->photo?>" style="object-fit= cover; width:110px; height: 150px;" width="110px" alt="">
    </div>
    


    <div style="text-align:center"><b>No. <?= $p->no_sertifikat ?>/SQ/UF - IC/<?=$bulan_tahun_sertifikat?></b></div>
    
    <table style="font-size:15px; line-height:24px" width="100%">
        <tr>
            <td>Diberikan kepada :</td>
            <td></td>
        </tr>
        <tr>
            <td width="25%">Nama</td>
            <td width="75%">: <?=$p->nama_lengkap?></td>
        </tr>
        <tr>
            <td>Tempat, Tanggal Lahir</td>
            <td>: <?= $p->tempat_lahir?>, <?=$p->tanggal_lahir?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: <?=$p->alamat_lengkap?></td>
        </tr>
        <tr>
            <td colspan="2">
                Sebagai <b>GURU PENGAJAR AL-QURAN METODE UMMI</b> 
            </td>
        </tr>
        <tr>
            <td colspan="2">
                Yang bersangkutan telah <b>Lulus Tashih</b> dan mengikuti <b>Sertitikasi Guru Al-Quran Metode Ummi</b> di <?=$kabupaten?> pada tanggal <?=$tanggal_kegiatan?> dengan pola 40 jam pelatihan yang diselenggarakan oleh Ummi Foundation.<br>
                Sertifikat ini berlaku sampai dengan tanggal <?=$sampai ?>.<br>
                Semoga Allah SWT memberkahi yang bersangkutan dengan Al-Qur'an. Amiin.
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="70%"></td>
            <td  width="30%">Surabaya, <u><?= $tanggal_hijri?></u><br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span><?=$tanggal_proses?></span>
        
        </td>
        </tr>
        <tr>
            <td>Pembina Ummi Foundation</td>
            <td>Direktur Ummi Foundation</td>
        </tr>
        <tr>
            <td  height="70px">
            
            </td>
            <td></td>
        </tr>
        <tr>
            <td>Prof. DR. H. M. Roem Rowi, M.A</td>
            <td>Drs. H. Masruri, M.Pd</td>
        </tr>
    </table>
    <div style="position: absolute; top: 167mm; left: 35mm; width: 100mm;z-index:-1;">                
       <img src="<?=base_url('assets/img/ttd_roem.png')?>" style="" width="200px" style="z-index:-1;" alt="">
    </div>
    
    <?php $i++?>
    <?php endforeach?>
    



</body>
</html>
