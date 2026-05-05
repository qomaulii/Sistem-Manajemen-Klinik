<div class="row">
  <div class="col col-md-8 well well-sm" style="padding: 20px;">
    <?= form_open('patient/register', ['id' => 'signupForm', 'role' => 'form']) ?>
    <?= csrf_field() ?>
      <fieldset>
        <legend>- Patient Information:</legend>
        <div>
          <?= !empty($error) ? $error : '' ?>
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="text" name='first_name' value="<?= set_value('first_name') ?>" class='form-control' placeholder='First Name' required autofocus /></div>
            <div class="col-md-6"><input type="text" name='last_name' value="<?= set_value('last_name') ?>" class='form-control' placeholder='Last Name' /></div>
          </div>
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="text" name='fname' value="<?= set_value('fname') ?>" class='form-control' placeholder='Father Name' /></div>
            <div class="col-md-6" style="padding-top: 5px;">
              <label class="radio-inline"><input type="radio" name='gender' value="1" <?= set_value('gender') == '1' ? 'checked' : '' ?> />Male</label>
              <label class="radio-inline"><input type="radio" name='gender' value="0" <?= set_value('gender') == '0' ? 'checked' : '' ?> />Female</label>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type='text' name='phone' value="<?= set_value('phone') ?>" class='form-control' placeholder='Phone' required/></div>
            <div class="col-md-6"><input type="number" name='age' value="<?= set_value('age') ?>" class='form-control' placeholder='Age' required/></div>
          </div>
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6">
              <?= form_dropdown('doctor', $doctor_list, set_value('doctor'), "class='form-control' title='Doctor' required") ?>
            </div>
            <div class="col-md-6"><input type='email' name='email' value="<?= set_value('email') ?>" class='form-control' placeholder='Email'/></div>
          </div>
          <div class="clearfix"></div>
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-12"><input type="text" name='address' value="<?= set_value('address') ?>" class='form-control' placeholder='Address'/></div>
          </div>
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="text" name='social_id' value="<?= set_value('social_id') ?>" class='form-control' placeholder='Social ID'/></div>
            <div class="col-md-6">
              <?= form_dropdown('id_type', $id_type_options, set_value('id_type'), "class='form-control' title='ID Type'") ?>
            </div>
          </div>
        </div>
      </fieldset>
      
      <div class="form-group" style="margin-top: 20px;">
        <div class="col-md-6"><input type="submit" value='Register' class="form-control btn btn-info" /></div>
        <div class="col-md-6"><a href="<?= base_url('patient') ?>" class="form-control btn btn-info text-center" style="line-height: 26px;">Cancel</a></div>
      </div>
    <?= form_close() ?>
  </div>
</div>