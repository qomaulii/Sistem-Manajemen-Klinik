<div class="row">
  <div class="col col-md-8 well well-sm" style="padding: 20px;">
    <?= form_open('drug/new_drug', ["id" => "newDrugForm", "role" => "form"]) ?>
      <fieldset>
        <legend>- Drug Information:</legend>
        <div>
          <?= !empty($error) ? '<div class="alert alert-danger">' . $error . '</div>' : '' ?>
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="text" name='drug_name_en' value="<?= set_value('drug_name_en') ?>" class='form-control' placeholder='Drug Name' required autofocus /></div>
            <div class="col-md-6"><input type="text" name='drug_name_fa' value="<?= set_value('drug_name_fa') ?>" class='form-control' placeholder='نام دارو' required /></div>
          </div>
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="text" name='category' value="<?= set_value('category') ?>" class='form-control' placeholder='Category' /></div>
            <div class="col-md-6"><input type="number" name='price' value="<?= set_value('price') ?>" class='form-control' placeholder='Price' required /></div>
          </div>
          <div class="clearfix"></div>
      </fieldset>
      <fieldset>
        <legend>- Memo:</legend>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-12"><textarea name="memo" class="form-control" rows="10" style="height: 150px;"><?= set_value('memo') ?></textarea></div>
        </div>
      </fieldset>
      <div class="form-group" style="margin-top: 20px;">
        <div class="col-md-6"><input type="submit" value='Register' class="form-control btn btn-info" /></div>
        <div class="col-md-6"><a href="<?= base_url('drug') ?>" class="form-control btn btn-info text-center" style="line-height: 26px;">Cancel</a></div>
      </div>
    <?= form_close() ?>
  </div>
</div>