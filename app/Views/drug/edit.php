<div class="row">
<?php if(!empty($drug->drug_id)): ?>
  <div class="col col-md-8 well well-sm" style="padding: 20px;">
    <?= form_open('drug/edit/' . $drug->drug_id, ["id" => "editDrugForm", "role" => "form"]) ?>
      <fieldset>
        <legend>- Drug Information:</legend>
        <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="text" name='drug_name_en' value="<?= set_value('drug_name_en', $drug->drug_name_en) ?>" class='form-control' placeholder='Drug Name' required autofocus /></div>
            <div class="col-md-6"><input type="text" name='drug_name_fa' value="<?= set_value('drug_name_fa', $drug->drug_name_fa) ?>" class='form-control' placeholder='نام دارو' required /></div>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
            <!-- Typo 'category' diperbaiki sesuai database -->
            <div class="col-md-6"><input type="text" name='category' value="<?= set_value('category', $drug->category) ?>" class='form-control' placeholder='Category' /></div>
            <div class="col-md-6"><input type="number" name='price' value="<?= set_value('price', $drug->price) ?>" class='form-control' placeholder='Price' required /></div>
        </div>
        <div class="clearfix"></div>
      </fieldset>
      <fieldset>
        <legend>- Memo:</legend>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-12"><textarea name="memo" class="form-control" rows="10"><?= set_value('memo', $drug->memo) ?></textarea></div>
        </div>
      </fieldset>
      <div class="form-group" style="margin-top: 20px;">
        <div class="col-md-6"><input type="submit" value='Update' class="form-control btn btn-info" /></div>
        <div class="col-md-6"><a href="<?= base_url('drug') ?>" class="form-control btn btn-info text-center" style="line-height: 26px;">Cancel</a></div>
      </div>
    <?= form_close() ?>
  </div>
<?php else: ?>
  <div class="alert alert-danger text-center"><h1>Drug Not Found</h1></div>
  <div class="pull-right"><a href="<?= base_url('drug') ?>" class="btn btn-default">Back</a></div>
<?php endif; ?>
</div>