<div class="row">
  <div class="col col-md-8 well well-sm" style="padding: 20px;">
    <?= form_open('test/new_test', ["id" => "newTestForm", "role" => "form"]) ?>
      <fieldset>
        <legend>- Test Information:</legend>
        <div id="test_info">
          <?= !empty($error) ? '<div class="alert alert-danger" style="margin-bottom: 15px;">' . $error . '</div>' : '' ?>
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="text" name='test_name_en' value="<?= set_value('test_name_en') ?>" class='form-control' placeholder='Test Name' required autofocus /></div>
            <div class="col-md-6"><input type="text" name='test_name_fa' value="<?= set_value('test_name_fa') ?>" class='form-control' placeholder='نام آزمایش' required /></div>
          </div>
          <div class="form-group" style="margin-bottom: 15px;">
            <!-- Category typo sudah diperbaiki sesuai DB -->
            <div class="col-md-6"><input type="text" name='category' value="<?= set_value('category') ?>" class='form-control' placeholder='Category' /></div>
            <div class="col-md-6"><input type="number" name='price' value="<?= set_value('price') ?>" class='form-control' placeholder='Price' required /></div>
          </div>
          <div class="clearfix"></div>
      </fieldset>
      <fieldset>
        <legend>- Memo:</legend>
        <div class="form-group">
          <div class="col-md-12">
            <textarea name="memo" class="form-control" rows="10" style="height: 150px;"><?= set_value('memo') ?></textarea>
          </div>
        </div>
      </fieldset>
      <div class="form-group" style="margin-top: 20px;">
        <div class="col-md-6"><input type="submit" value='Register' class="form-control btn btn-info" /></div>
        <div class="col-md-6"><a href="<?= base_url('test') ?>" class="form-control btn btn-info text-center" style="line-height: 26px;">Cancel</a></div>
      </div>
    <?= form_close() ?>
  </div>
</div>