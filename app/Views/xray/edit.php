<div class="row">
<?php if(!empty($xray->xray_id)): ?>
  <div class="col col-md-8 well well-sm" style="padding: 20px;">
    <?= form_open('xray/edit/'.$xray->xray_id, ["id" => "editXrayForm", "role" => "form"]) ?>
      <fieldset>
        <legend>- Edit X-Ray Type:</legend>
        <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="text" name='xray_name_en' value="<?= set_value('xray_name_en', $xray->xray_name_en) ?>" class='form-control' placeholder='X-ray Name' required autofocus /></div>
            <div class="col-md-6"><input type="text" name='xray_name_fa' value="<?= set_value('xray_name_fa', $xray->xray_name_fa) ?>" class='form-control' placeholder='نام اکسری' required /></div>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="text" name='category' value="<?= set_value('category', $xray->category) ?>" class='form-control' placeholder='Category' /></div>
            <div class="col-md-6"><input type="number" name='price' value="<?= set_value('price', $xray->price) ?>" class='form-control' placeholder='Price' required /></div>
        </div>
        <div class="clearfix"></div>
      </fieldset>
      <fieldset>
        <legend>- Memo:</legend>
        <textarea name="memo" class="form-control" rows="8" style="height: 150px;"><?= set_value('memo', $xray->memo) ?></textarea>
      </fieldset>
      <div class="form-group" style="margin-top: 20px;">
        <div class="col-md-6"><input type="submit" value='Update' class="form-control btn btn-info" /></div>
        <div class="col-md-6"><a href="<?= base_url('xray') ?>" class="form-control btn btn-info text-center" style="line-height: 26px;">Cancel</a></div>
      </div>
    <?= form_close() ?>
  </div>
<?php else: ?>
  <div class="alert alert-danger text-center"><h1>Xray Not Found</h1></div>
<?php endif; ?>
</div>