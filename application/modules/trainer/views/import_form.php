<div class="row">
    <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-xs-12">

      <?= message_box('success', TRUE)?>
      <?= message_box('error', TRUE)?>

      <div class="box box-primary">
        <?php echo form_open('trainer/import/form','enctype="multipart/form-data"')?>
        <div class="box-body">
          <p>Silahkan download template file <a href="/excel/template_trainer.xlsx">disini</a> </p>
          <div class="form-group">
            <label for="input_fime">File input</label>
            <input type="file" id="file" name="file">
            <p class="help-block">FIle yang dapat diimport hanya file dengan extension .xlsx.</p>
          </div>
          <div class="">
            <b>PERHATIAN : KETIKA MENGISI TANGGAL_LAHIR FORMAT TANGGAL YANG BENAR ADALAH YYYY-MM-DD contoh 1988-05-03</b><br>
            <b>PERHATIKAN DAN JANGAN SAMPAI SALAH INPUT DATA TANGGAL LAHIR YA</b>
          </div>
        </div>
        <div class="box-footer">
          <button type="submit" name="preview" class="btn btn-primary">Preview</button>
        </div>

        <?php echo form_close()?>
      </div>

      <?php if(isset($_POST['preview'])):?>
      <div class="box box-primary" id="preview_content">
        <div class="box-header">
          <b>Preview Content</b>
        </div>
        <?php echo form_open('trainer/import/save')?>


        <div class="box-body">
              <table class="table table-responsive table-hover">
                <tr>
                  <th colspan="7"></th>
                </tr>
                <tr>
                  <th>umda id</th>
                  <th>Nama Lengkap</th>
                  <th>Panggilan</th>
                  <th>Alamat Lengkap</th>
                  <th>No HP</th>
                  <th>Email</th>
                  <th>Tanggal Lahir</th>
                </tr>

                <?php $numrow = 1;
                $kosong = 0; ?>

                <?php foreach($sheet as $row):?>

                <?php $umda_id = $row['A'];
                      $nama_lengkap = $row['B'];
                      $panggilan = $row['C'];
                      $alamat = $row['D'];
                      $nohp = $row['E'];
                      $email = $row['F'];
                      $tanggal_lahir = $row['G'];

                      if(empty($umda_id) or empty($nama_lengkap) or empty($panggilan) or empty($alamat) or empty($nohp) or empty($email) or empty($tanggal_lahir)){
              					$kosong++; // Tambah 1 variabel $kosong
              				}
                ?>

                  <?php if($numrow >1):?>
                  <tr>
                    <td <?php if($umda_id == ''){ echo 'class="bg-yellow"'; }?>><?=$umda_id?></td>
                    <td <?php if($nama_lengkap == ''){ echo 'class="bg-yellow"'; }?>><?=$nama_lengkap?></td>
                    <td <?php if($panggilan == ''){ echo 'class="bg-yellow"'; }?>><?=$panggilan?></td>
                    <td <?php if($alamat == ''){ echo 'class="bg-yellow"'; }?>><?=$alamat?></td>
                    <td <?php if($nohp == ''){ echo 'class="bg-yellow"'; }?>><?=$nohp?></td>
                    <td <?php if($email == ''){ echo 'class="bg-yellow"'; }?>><?=$email?></td>
                    <td <?php if($tanggal_lahir == ''){ echo 'class="bg-yellow"'; }?>><?=$tanggal_lahir?></td>
                  </tr>
                  <?php endif?>
                  <?php $numrow++?>
                <?php endforeach?>
              </table>


        </div>

        <?php if($kosong > 1):?>
          <div class="box-footer" style='color: red;' id='kosong'>
            Semua data belum terisi dengan lengkap, Setidaknya ada <?=$kosong?> baris data yang belum terisi lengkap.
          </div>
        <?php else:?>
          <div class="box-footer" style='color: red;' id='kosong'>
            <button type="submit" name="import" class="btn btn-primary">Import</button>
          </div>

        <?php endif?>
        <?php echo form_close()?>

      </div>
    <?php endif?>



    </div>

</div>
