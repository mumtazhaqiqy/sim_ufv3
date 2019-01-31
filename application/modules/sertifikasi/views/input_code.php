<!-- View massage -->
<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>

<?php $baseJs = ['assets/plugins/jquery/dist/jquery.min.js']; ?>
<?php $this->layout->set_assets($baseJs, 'scripts') ?>
<?php echo $this->layout->get_assets('scripts') ?>
<style>

    #wrapper {
    font-size: 1.5rem;
    text-align: center;
    box-sizing: border-box;
    color: #333;
    }
    #wrapper #dialog {
    border: solid 1px #ccc;
    margin: 10px auto;
    padding: 20px 30px;
    display: inline-block;
    box-shadow: 0 0 4px #ccc;
    background-color: #FAF8F8;
    overflow: hidden;
    position: relative;
    max-width: 600px;
    }
    #wrapper #dialog h3 {
    margin: 0 0 10px;
    padding: 0;
    line-height: 1.25;
    }
    #wrapper #dialog span {
    font-size: 90%;
    }
    #wrapper #dialog #form {
    max-width: 400px;
    margin: 25px auto 0;
    }
    #wrapper #dialog #form input {
    margin: 0 2px;
    text-align: center;
    line-height: 60px;
    font-size: 50px;
    border: solid 1px #ccc;
    box-shadow: 0 0 5px #ccc inset;
    outline: none;
    width: 80%;
    transition: all 0.2s ease-in-out;
    border-radius: 3px;
    }
    #wrapper #dialog #form input:focus {
    border-color: purple;
    box-shadow: 0 0 5px purple inset;
    }
    #wrapper #dialog #form input::-moz-selection {
    background: transparent;
    }
    #wrapper #dialog #form input::selection {
    background: transparent;
    }
    #wrapper #dialog #form button {
    margin: 30px 0 50px;
    width: 100%;
    padding: 6px;
    background-color: #B85FC6;
    border: none;
    text-transform: uppercase;
    }
    #wrapper #dialog button.close {
    border: solid 2px;
    border-radius: 30px;
    line-height: 19px;
    font-size: 120%;
    width: 22px;
    position: absolute;
    right: 5px;
    top: 5px;
    }
    #wrapper #dialog div {
    position: relative;
    z-index: 1;
    }
    #wrapper #dialog img {
    position: absolute;
    bottom: -70px;
    right: -63px;
    }

</style>

<?php echo form_open('sertifikasi/pendaftaran','','id="loginForm"'); ?>
<form>
<div id="wrapper">
  <div id="dialog">
    <h3>Masukkan 6 Digit Kode Sertifikasi</h3>
    <span>(untuk melakukan pendaftaran peserta sertifikasi)</span>
    <input name="code" id='hdnData' class="form-control hidden" type="text" placeholder="">
    <div id="form">
      <input type="number" maxLength="6" size="6" pattern="{1}"/>
      <button type="submit" id="btnSubmit" class="btn btn-primary btn-embossed">VERIFIKASI</button>
      <p id="print"></p>
    </div>
     
  </div>
</div>
</form>

<script>
$(document).ready(function() {
    $("#btnSubmit").click(function(e) {
        var txtData = [];
        $("form :input[type=number]").each(function() {
            txtData.push($(this).val());
        });
        $("#hdnData").val(txtData.join(""));
        // $('#print').html($("#hdnData").val());
        // e.preventDefault();
    });
});
</script>