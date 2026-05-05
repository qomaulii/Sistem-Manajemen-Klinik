<div class="row">
  <div class="col col-md-8 well well-sm" style="padding: 20px;">
    <?= form_open('account/signup', ['id' => 'signupForm', 'role' => 'form']) ?>
      <fieldset>
        <legend>- User Information:</legend>
        <div>
          <?= !empty($error) ? '<div class="alert alert-danger" style="margin-bottom: 15px;">' . $error . '</div>' : '' ?>
          
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="text" name="first_name" id="first_name" value="<?= set_value('first_name') ?>" class="form-control" placeholder="First Name" title="First Name" required autofocus /></div>
            <div class="col-md-6"><input type="text" name="last_name" id="last_name" value="<?= set_value('last_name') ?>" class="form-control" placeholder="Last Name" title="Last Name" /></div>
          </div>
          
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="text" name="fname" id="fname" value="<?= set_value('fname') ?>" class="form-control" placeholder="Father Name" title="Father Name" /></div>
            <div class="col-md-6" style="padding-top: 5px;">
              <label class="radio-inline"><input type="radio" name="gender" value="1" title="Male" <?= set_value('gender') == '1' ? 'checked' : '' ?> />Male</label>
              <label class="radio-inline"><input type="radio" name="gender" value="0" title="Female" <?= set_value('gender') == '0' ? 'checked' : '' ?> />Female</label>
            </div>
          </div>
          <div class="clearfix"></div>
          
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-12"><input type="text" name="username" id="username" value="<?= set_value('username') ?>" class="form-control" placeholder="User Name" title="User Name" required /></div>
          </div>
          
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="password" name="password" id="password" class="form-control" placeholder="Password" title="Password" required/></div>
            <div class="col-md-6"><input type="password" name="password_conf" id="password_conf" class="form-control" placeholder="Confirm Password" title="Confirm Password" required /></div>
          </div>
          
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="email" name="email" id="email" value="<?= set_value('email') ?>" class="form-control" placeholder="Email" title="Email" required /></div>
          </div>
          
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="text" name="phone" id="phone" value="<?= set_value('phone') ?>" class="form-control" placeholder="Phone" title="Phone" required/></div>
          </div>
          <div class="clearfix"></div>
          
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-12"><input type="text" name="address" id="address" value="<?= set_value('address') ?>" class="form-control" placeholder="Address" title="Address"/></div>
          </div>
          
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="text" name="social_id" id="social_id" value="<?= set_value('social_id') ?>" class="form-control" placeholder="Social ID" title="Social ID" required/></div>
          </div>
          
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6">
              <?= form_dropdown('id_type', $id_type_options, set_value('id_type'), "class='form-control' title='ID Type'") ?>
            </div>
          </div>
          
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6">
              <?= form_dropdown('position', $roles_option, set_value('position'), "class='form-control' title='Position'") ?>
            </div>
          </div>
          
          <div class="form-group" style="margin-bottom: 15px;">
            <div class="col-md-6"><input type="date" name="birth_date" id="birth_date" value="<?= set_value('birth_date') ?>" class="form-control" placeholder="Birth Date" title="Birth Date"/></div>
          </div>
          <div class="clearfix"></div>
        </div>
      </fieldset>
      
      <fieldset style="display: none;">
        <legend>+ Memo:</legend>
        <div>
          <div class="form-group">
            <div class="col-md-12"><textarea name="memo" id="memo" class="form-control" rows="10"><?= set_value('memo') ?></textarea></div>
          </div>
        </div>
      </fieldset>
      
      <div class="form-group" style="margin-top: 20px;">
        <div class="col-md-6"><input type="submit" name="submit" id="submit" value="Register" class="form-control btn btn-info" style="height: 40px;" /></div>
        <div class="col-md-6"><a href="<?= base_url('account/users') ?>" class="form-control btn btn-info text-center" style="height: 40px; line-height: 26px;">Cancel</a></div>
      </div>

    <?= form_close() ?>
  </div>
</div>