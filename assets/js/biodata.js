$(document).ready(function(){
    $('input[name="hafal_quran"]').on('ifChanged', function(event){
        // alert($(this).val() + ' callback');
    
        if($(this).val() == 0 ){
            $('#jumlah_hafalan_field_box').hide();
        }else {
            $('#jumlah_hafalan_field_box').show();
        }
    
    });
    
    $('input[name="sudah_mengajar"]').on('ifChanged', function(event){
        // alert($(this).val() + ' callback');
    
        if($(this).val() == 0 ){
            $('#di_lembaga_field_box').hide();
        }else {
            $('#di_lembaga_field_box').show();
        }
    
    });
    
    $('#addPengalamanMengajar').click(add_pengalaman_mengajar)
    
    function add_pengalaman_mengajar(){
    var pengalamanMemngajarItem=$('.form-pengalaman-mengajar:last').clone();
    pengalamanMemngajarItem.children().children().val('');
    pengalamanMemngajarItem.insertAfter('.form-pengalaman-mengajar:last');
    $('.remove-pm').click(function(){$(this).parent().parent().remove()}
    )}
    
    $('#addPengalamanKursus').click(add_pengalaman_kursus)
    
    function add_pengalaman_kursus(){
    var pengalamanMemngajarItem=$('.form-pengalaman-kursus:last').clone();
    pengalamanMemngajarItem.children().children().val('');
    pengalamanMemngajarItem.insertAfter('.form-pengalaman-kursus:last');
    $('.remove-pk').click(function(){$(this).parent().parent().remove()}
    )}
    
    $("td:last-child,th:last-child").hide();
	$(document).ajaxComplete(function(){$("td:last-child,th:last-child").hide();});
    

});



