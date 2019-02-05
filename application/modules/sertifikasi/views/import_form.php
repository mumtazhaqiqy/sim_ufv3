<div class="row">
    <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-xs-12">

      <?= message_box('success', TRUE)?>
      <?= message_box('error', TRUE)?>

      <div class="box box-primary">
        <?php echo form_open('','enctype="multipart/form-data"')?>
        <div class="box-body">
          <p>Silahkan download template file <a href="/excel/template_peserta.xlsx">disini</a> </p>
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
        <?php echo form_open('')?>


        <div class="box-body">
              <table class="table table-responsive table-hover">
                <tr>
                  <th colspan="7"></th>
                </tr>
                <tr class="text-bold">
                  <td>	Nama Lengkap	</td>
                  <td>	Tempat Lahir	</td>
                  <td>	Tanggal Lahir	</td>
                  <td>	Alamat Lengkap	</td>
                  <td>	No Hp	</td>
                  <td>	Di Lembaga	</td>
                </tr>

                <?php $numrow = 1;
                $kosong = 0; ?>

                <?php foreach($sheet as $row):?>

                <?php $nama_lengkap	=	$row['A'];
                      $tempat_lahir	=	$row['B'];
                      $tanggal_lahir	=	date('Y-m-d',strtotime($row['C']));
                      $alamat_lengkap	=	$row['D'];
                      $no_hp	=	$row['E'];
                      $di_lembaga	=	$row['F'];


                      if(
                        empty($nama_lengkap)
                        or empty($tempat_lahir)
                        or empty($tanggal_lahir)
                        or empty($alamat_lengkap)
                        or empty($no_hp)
                        or empty($di_lembaga)
                        ){
              					$kosong++; // Tambah 1 variabel $kosong
              				}
                ?>

                  <?php if($numrow >1):?>
                  <tr>
                    <td <?php if($nama_lengkap == '' )	{ echo 'class="bg-yellow"'; }?>><?=	$nama_lengkap?></td>
                    <td <?php if($tempat_lahir == '' )	{ echo 'class="bg-yellow"'; }?>><?=	$tempat_lahir?></td>
                    <td <?php if($tanggal_lahir == '' )	{ echo 'class="bg-yellow"'; }?>><?=	$tanggal_lahir?></td>
                    <td <?php if($alamat_lengkap == '' )	{ echo 'class="bg-yellow"'; }?>><?=	$alamat_lengkap?></td>
                    <td <?php if($no_hp == '' )	{ echo 'class="bg-yellow"'; }?>><?=	$no_hp?></td>
                    <td <?php if($di_lembaga == '' )	{ echo 'class="bg-yellow"'; }?>><?=	$di_lembaga?></td>
                  </tr>
                  <?php endif?>
                  <?php $numrow++?>
                <?php endforeach?>
              </table>


        </div>

        <?php if($kosong > 1):?>
          <div class="box-footer" style='color: red;' id='kosong'>
            Semua data belum terisi dengan lengkap, Setidaknya ada <?=$kosong?> baris data yang belum terisi lengkap. lengkapi data terlebih dahulu dan lakukan import lagi.
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
