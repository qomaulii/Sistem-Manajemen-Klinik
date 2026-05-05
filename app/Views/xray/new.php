<div class="row">
  <div class="col col-md-8 well well-sm" style="padding: 20px;">
    <?= form_open('xray/new_xray', ["id" => "newXrayForm", "role" => "form"]) ?>
      <fieldset>
        <legend>- X-Ray Information:</legend>
        <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="text" name='xray_name_en' value="<?= set_value('xray_name_en') ?>" class='form-control' placeholder='Xray Name' required autofocus /></div>
            <div class="col-md-6"><input type="text" name='xray_name_fa' value="<?= set_value('xray_name_fa') ?>" class='form-control' placeholder='نام اکسri' required /></div>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="text" name='category' value="<?= set_value('category') ?>" class='form-control' placeholder='Category' /></div>
            <div class="col-md-6"><input type="number" name='price' value="<?= set_value('price') ?>" class='form-control' placeholder='Price' required /></div>
        </div>
        <div class="clearfix"></div>
      </fieldset>
      <fieldset>
        <legend>- Memo:</legend>
        <textarea name="memo" class="form-control" rows="10" style="height: 150px;"><?= set_value('memo') ?></textarea>
      </fieldset>
      <div class="form-group" style="margin-top: 20px;">
        <div class="col-md-6"><input type="submit" value='Register' class="form-control btn btn-info" /></div>
        <div class="col-md-6"><a href="<?= base_url('xray') ?>" class="form-control btn btn-info text-center" style="line-height: 26px;">Cancel</a></div>
      </div>
    <?= form_close() ?>
  </div>
</div>