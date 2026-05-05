<div class="row">
  <div class="col col-md-8 well well-sm" style="padding: 20px;">
    <?= form_open('drug/return_drug', ["id" => "returnDrugForm", "role" => "form"]) ?>
      <fieldset>
        <legend>- Returned Drug Information:</legend>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-12">
            <?= form_dropdown('drug_id', $drugs_list, set_value('drug_id'), "class='form-control' required") ?>
          </div>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-6"><input type="number" name='no_of_item' id='no_of_item' value="<?= set_value('no_of_item') ?>" class='form-control' placeholder='Qty' required/></div>
          <div class="col-md-6"><input type="number" id='unit_price' class='form-control' placeholder='Unit Price' /></div>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-6"><input type="number" name='total_cost' id='total_cost' value="<?= set_value('total_cost') ?>" class='form-control' placeholder='Total Refund' required/></div>
          <div class="col-md-6"><input type="date" name='return_date' value="<?= set_value('return_date', @$today) ?>" class='form-control' required /></div>
        </div>
      </fieldset>
      <fieldset>
        <legend>- Reason/Memo:</legend>
        <div class="form-group">
          <div class="col-md-12">
            <textarea name="memo" class="form-control" rows="5" required style="height: 100px;"><?= set_value('memo') ?></textarea>
          </div>
        </div>
      </fieldset>
      <div class="form-group" style="margin-top: 20px;">
        <div class="col-md-6"><input type="submit" value='Confirm Return' class="form-control btn btn-danger" /></div>
        <div class="col-md-6"><a href="<?= base_url('drug') ?>" class="form-control btn btn-info text-center" style="line-height: 26px;">Cancel</a></div>
      </div>
    <?= form_close() ?>
  </div>
</div>