$(document).ready(function(){
    $('input[name="status_sertifikasi"]').on('ifChanged', function(event){
        // alert($(this).val() + ' callback');
    
        if($(this).val() == 0 ){
            $('#no_sertifikat_field_box').hide();
            $('#tahun_sertifikasi_field_box').hide();
        }else {
            $('#no_sertifikat_field_box').show();
            $('#tahun_sertifikasi_field_box').show();
        }
    
    });
    
});



