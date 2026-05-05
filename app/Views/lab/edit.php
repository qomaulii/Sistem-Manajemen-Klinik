<div class="row">
<?php if(!empty($test->test_id)): ?>
  <div class="col col-md-8 well well-sm" style="padding: 20px;">
    <?= form_open('test/edit/'.$test->test_id, ["id" => "editTestForm", "role" => "form"]) ?>
      <fieldset>
        <legend>- Test Information:</legend>
        <div id="test_info">
          <?= !empty($error) ? '<div class="alert alert-danger" style="margin-bottom: 15px;">' . $error . '</div>' : '' ?>
          <div class="form-group" style="margin-bottom: 15px;">
              <div class="col-md-6"><input type="text" name='test_name_en' value="<?= set_value('test_name_en', $test->test_name_en) ?>" class='form-control' placeholder='Test Name' required autofocus /></div>
            <div class="col-md-6"><input type="text" name='test_name_fa' value="<?= set_value('test_name_fa', $test->test_name_fa) ?>" class='form-control' placeholder='نام آزمایش' required /></div>
          </div>
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="text" name='category' value="<?= set_value('category', $test->category) ?>" class='form-control' placeholder='Category' /></div>
            <div class="col-md-6"><input type="number" name='price' value="<?= set_value('price', $test->price) ?>" class='form-control' placeholder='Price' required /></div>
          </div>
          <div class="clearfix"></div>
      </fieldset>
      <fieldset>
        <legend>- Memo:</legend>
        <div class="form-group">
          <div class="col-md-12">
            <textarea name="memo" class="form-control" rows="10" style="height: 150px;"><?= set_value('memo', $test->memo) ?></textarea>
          </div>
        </div>
      </fieldset>
      <div class="form-group" style="margin-top: 20px;">
        <div class="col-md-6"><input type="submit" value='Update' class="form-control btn btn-info" /></div>
        <div class="col-md-6"><a href="<?= base_url('test') ?>" class="form-control btn btn-info text-center" style="line-height: 26px;">Cancel</a></div>
      </div>
    <?= form_close() ?>
  </div>
<?php else: ?>
  <div class="alert alert-danger text-center"><h1>Test Not Found</h1></div>
  <div class="pull-right">
      <a href="<?= base_url('test') ?>" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>
  </div>
<?php endif; ?>
</div>