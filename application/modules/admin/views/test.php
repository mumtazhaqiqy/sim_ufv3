<table>
    <tr>
        <td>Nama</td>
        <?php foreach($kriteria as $k):?>
        <td><?= $k->display_name?></td>
        <?php endforeach?>
    </tr>
    <?php foreach($peserta as $p):?>
    <tr>
        <td><?= $p->nama_lengkap?></td>
        <?php foreach($kriteria as $k):?>
        <td><?= db_get_row('tm_rekap_nilai',array('peserta_munaqasyah_id' => $p->id_peserta_munaqasyah, 'kriteria_nilai_id' => $k->id_kriteria_nilai))->nilai?></td>
        <?php endforeach?>
    </tr>
    <?php endforeach?>
</table>