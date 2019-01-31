<div class="row">
  <div class="col-md-12">
    <?= message_box('success', TRUE)?>
		<?= message_box('error', TRUE)?>
    <div class="box box-primary">
      <div class="box-header">

      </div>
      <?php echo form_open(); ?>
      <form action="" method="POST">
      <div class="box-body">
        <div class="form-group">
            <label>Import Data Trainer</label>
            <input type="file" name="import" class="form-control">
        </div>

        <input class="btn btn-flat bg-purple" name="submit" type="submit" value="Import">

      </div>
    </form>

    </div>

  </div>

</div>
