<div class="row">
  <div class="col-lg-3 col-sm-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3><?php echo $jumlah_lembaga ?></h3>

        <p>Semua Lembaga</p>
      </div>
      <div class="icon">
        <i class="fa fa-university"></i>
      </div>
      <a href="<?=base_url('admin/pengguna/crud')?>" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-sm-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-green">
      <div class="inner">
        <h3><?php echo $lembaga_aktif ?></h3>

        <p>Aktif</p>
      </div>
      <div class="icon">
        <i class="fa fa-check "></i>
      </div>
      <a href="<?=base_url('admin/pengguna/crud/aktif')?>" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-sm-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-yellow">
      <div class="inner">
        <h3><?= $lembaga_not_aktif ?></h3>

        <p>Not Aktif</p>
      </div>
      <div class="icon">
        <i class="fa fa-close"></i>
      </div>
      <a href="<?=base_url('admin/pengguna/crud/nonaktif')?>" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-sm-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-red">
      <div class="inner">
        <h3><?= $lembaga_unregister ?></h3>

        <p>Belum Register</p>
      </div>
      <div class="icon">
        <i class="fa fa-key"></i>
      </div>
      <a href="<?=base_url('admin/pengguna/crud/unregister')?>" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
</div>

<div class="row">
	<div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<?= message_box('success', TRUE)?>
		<?= message_box('warning', TRUE)?>
	</div>
	<div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
		<div><?php echo $output; ?></div>
	</div>
</div>
