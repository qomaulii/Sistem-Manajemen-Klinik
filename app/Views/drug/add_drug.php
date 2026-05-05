<div class="row">
  <div class="col col-md-8 well well-sm" style="padding: 20px;">
    <?= form_open('drug/add_drug', ["id" => "addDrugForm", "role" => "form"]) ?>
      <fieldset>
        <legend>- Purchased Drug Information:</legend>
        <div>
          <?= !empty($error) ? '<div class="alert alert-danger">' . $error . '</div>' : '' ?>
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-12">
              <?= form_dropdown('drug_id', $drugs_list, set_value('drug_id'), "class='form-control' title='Drug' required") ?>
            </div>
          </div>
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6">
              <input type="number" name='no_of_item' id='no_of_item' value="<?= set_value('no_of_item') ?>" class='form-control' placeholder='Number of Items' title='Number of Items' required/>
            </div>
            <div class="col-md-6">
              <input type="number" name='purchase_price' id='purchase_price' value="<?= set_value('purchase_price') ?>" class='form-control' placeholder='Unit Price' title='Unit Price' required />
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6">
              <input type="number" name='total_cost' id='total_cost' value="<?= set_value('total_cost') ?>" class='form-control' placeholder='Total Cost' title='Total Cost' required/>
            </div>
            <div class="col-md-6">
              <input type="date" name='purchase_date' id='purchase_date' value="<?= set_value('purchase_date', @$today) ?>" class='form-control' title='Purchase Date' required />
            </div>
          </div>
      </fieldset>
      <fieldset>
        <legend>- Memo:</legend>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-12">
            <textarea name="memo" id="memo" class="form-control" rows="10" style="height: 150px;"><?= set_value('memo') ?></textarea>
          </div>
        </div>
      </fieldset>
      <div class="form-group" style="margin-top: 20px;">
        <div class="col-md-6"><input type="submit" name='submit' id='submit' value='Add' class="form-control btn btn-info" /></div>
        <div class="col-md-6"><a href="<?= base_url('drug') ?>" class="form-control btn btn-info text-center" style="line-height: 26px;">Cancel</a></div>
      </div>
    <?= form_close() ?>
  </div>
</div>

<script>
  $(document).ready(function(){
      // Kalkulasi otomatis harga beli
      $('#purchase_price, #no_of_item').on('blur', function(){
         var uPrice = $('#purchase_price').val() * 1;
         var num = $('#no_of_item').val() * 1;
         if(num > 0 && uPrice > 0){
            $('#total_cost').val(uPrice * num);
         }
      });
      $('#total_cost').on('blur', function(){
         var total = $(this).val() * 1;
         var num = $('#no_of_item').val() * 1;
         if(num > 0 && total > 0){
            $('#purchase_price').val(total / num);
         }
      });
  });
</script>