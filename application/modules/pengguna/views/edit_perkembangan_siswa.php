
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                Form Jumlah Santri
            </div>   
            <div class="box-body">
            <?php echo form_open('/pengguna/perkembangan_siswa/save?method=edit',''); ?>
            <form action="" method="POST">
                <div class="row">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                            <input type="checkbox" id="dewasa">
                            Menggunakan Ummi Dewasa
                            </label>
                        </div>

                        <div class="checkbox" >
                            <label>
                            <input type="checkbox" id="tahfidz">
                            Menggunakan Tahfidz Ummi
                            </label>
                        </div>

                        <div class="checkbox">
                            <label>
                            <input type="checkbox" id="turjuman">
                            Menggunakan Turjuman
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                            <div class="form-group hidden">
                                <label>periode-bulan</label>
                                <input type="text" name="periode_bulan" class="form-control" id="periode-bulan" placeholder="" value="<?=$periode_bulan?>" >
                            </div>
                            <div class="form-group hidden">
                                <label>tahun</label>
                                <input type="text" name="tahun" class="form-control" id="tahun" placeholder="" value="<?=$tahun?>" >
                            </div>
                            <div class="form-group hidden">
                                <label>unique</label>
                                <input type="text" name="unique" class="form-control" id="unique" placeholder="" value="<?=$unique?>" >
                            </div>

                            <table class="table table-responsive table-condensed table-hover">
                                <thead class="bg-black text-white">
                                    <tr>
                                        <td class="w-32">Kelas \ Jilid</td>
                                        <?php foreach($kelas as $k):?>
                                        <td><?= $k->kelas?></td>
                                        <?php endforeach?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($jilid as $j):?>
                                    <tr class="<?=$j->jenis ?>">
                                        <td><?=$j->jilid?></td>
                                        <?php foreach($kelas as $k):?>
                                        <?php $value = db_get_row('jumlah_santri','unique = "'.$unique.'" AND kelas_id = '.$k->id.' AND jilid_id = '.$j->id)->jumlah_santri;?>
                                        <td><input value="<?=$value?>" type="text" class="form-control zero" name="<?= $k->id?>[<?=$j->id?>]" placeholder="" /></td>
                                        <?php endforeach?>
                                    </tr>
                                    <?php endforeach?>
                                </tbody>
                            
                            </table>

                            <div> 
                                <button type="submit" class="btn btn-primary ">Submit</button>
                                <a  class="btn btn-warning  " href="/pengguna/perkembangan_siswa">Batal</a>
                            </div>

                    </div>
 
                </div>
            <?php echo form_close();?>
            </div>     


        </div>

    </div>
</div>


<script>
$(document).ready(function() {
    if ($('#dewasa').is(':checked')) {
        $('.dewasa').show();
    } else {
        $('.dewasa').hide();
    }
    if ($('#tahfidz').is(':checked')) {
        $('.tahfidz').show();
    } else {
        $('.tahfidz').hide();
    }
    if ($('#turjuman').is(':checked')) {
        $('.turjuman').show();
    } else {
        $('.turjuman').hide();
    }
});

$(function () {
    $("#dewasa").on("ifChecked", function(event){
        $(".dewasa").show();
    })

    $("#dewasa").on("ifUnchecked", function(event){
        $(".dewasa").hide();
    })
    $("#tahfidz").on("ifChecked", function(event){
        $(".tahfidz").show();
    })

    $("#tahfidz").on("ifUnchecked", function(event){
        $(".tahfidz").hide();
    })
    $("#turjuman").on("ifChecked", function(event){
        $(".turjuman").show();
    })

    $("#turjuman").on("ifUnchecked", function(event){
        $(".turjuman").hide();
    })
});


$(".zero").on("blur", function () {
    if ($(this).val().length == 0) {
        $(this).val("0");
    }
});
//trigger blur once for the initial setting:
$(".zero").trigger("blur");
</script>