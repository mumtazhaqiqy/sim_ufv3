
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                Form Jumlah Santri
            </div>   
            <div class="box-body">
            <?php echo form_open('/ummidaerah/perkembangan_siswa/save/'.$customer_id.'?method=add',''); ?>
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
                                <input name="customer_id" value="<?=$customer_id?>">
                            </div>
                            <div class="form-group">
                                <label>periode-bulan</label>
                                <select class="form-control" name="periode_bulan" id="periode_bulan"></select>
                            </div>
                            <div class="form-group">
                                <label>tahun</label>
                                <select class="form-control" name="tahun" id="tahun"></select>
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
                                <a  class="btn btn-warning" href="<?=base_url()?>ummidaerah/perkembangan_siswa/index/<?=$customer_id ?>">Batal</a>
                            </div>

                    </div>
 
                </div>
            <?php echo form_close();?>
            </div>     


        </div>

    </div>
</div>


<script>
$(document).ready(function(){
    
    var d = new Date();
    var n = d.getMonth();
    var monthArray = new Array();
    monthArray[0] = "January";
    monthArray[1] = "February";
    monthArray[2] = "March";
    monthArray[3] = "April";
    monthArray[4] = "May";
    monthArray[5] = "June";
    monthArray[6] = "July";
    monthArray[7] = "August";
    monthArray[8] = "September";
    monthArray[9] = "October";
    monthArray[10] = "November";
    monthArray[11] = "December";
    for(m = 0; m <= 11; m++) {
        var optn = document.createElement("OPTION");
        optn.text = monthArray[m];
        // server side month start from one
        optn.value = (m+1);
    
        // if june selected
        if ( m == n) {
            optn.selected = true;
        }
    
        document.getElementById('periode_bulan').options.add(optn);
    }

});


$(document).ready(function() {

    for (i = new Date().getFullYear(); i > 2015; i--)
    {
        $('#tahun').append($('<option />').val(i).html(i));
    }

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