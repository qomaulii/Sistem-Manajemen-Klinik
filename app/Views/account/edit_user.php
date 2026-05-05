<?php 
$checkbox_conf_array = ['class' => 'checkbox-inline', 'style' => 'color: rgba(10,120,180,1); margin-bottom: 10px;'];
if (!empty($user)): 
?>
<link rel="stylesheet" href="<?= base_url('content/css/bootstrap-fileupload.min.css') ?>" media="screen"/>
<script src="<?= base_url('content/js/bootstrap-fileupload.js') ?>"></script>

<div class="col col-md-8 well well-md" style="padding: 20px;">
  <?= form_open_multipart('account/edit_user/' . $user->user_id, ['id' => 'edituserForm', 'role' => 'form']) ?>
  
  <div style="margin-bottom: 20px; color: black; text-align: center;">
    Edit User Information for:<br/>
    <strong><?= esc($user->username) ?></strong>
    <input type="hidden" name="username" id="username" value="<?= set_value('username', $user->username) ?>"/>
  </div>
  
  <?= !empty($error) ? '<div class="alert alert-danger" style="margin-bottom: 15px;">' . $error . '</div>' : '' ?>
  
    <fieldset>
      <legend>- Personal Information:</legend>
      <div>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-9">
            <div style="margin-bottom: 10px;"><input type="text" name="first_name" id="first_name" value="<?= set_value('first_name', $user->first_name) ?>" class="form-control" placeholder="First Name" title="First Name" required autofocus /></div>
            <div style="margin-bottom: 10px;"><input type="text" name="last_name" id="last_name" value="<?= set_value('last_name', $user->last_name) ?>" class="form-control" placeholder="Last Name" title="Last Name" /></div>
            <div style="margin-bottom: 10px;"><input type="text" name="fname" id="fname" value="<?= set_value('fname', $user->fname) ?>" class="form-control" placeholder="Father Name" title="Father Name" /></div>
            <div class="col-md-12" style="margin-bottom: 10px;">
              <label class="radio-inline"><input type="radio" name="gender" value="1" title="Male" <?= set_value('gender', $user->gender) == '1' ? 'checked' : '' ?> />Male</label>
              <label class="radio-inline"><input type="radio" name="gender" value="0" title="Female" <?= set_value('gender', $user->gender) == '0' ? 'checked' : '' ?> />Female</label>
            </div>
          </div>
          <div class="col-md-3">
            <div class="fileupload fileupload-new" data-provides="fileupload">
              <div class="fileupload-preview thumbnail" style="width: 120px; height: 140px;">
                  <img src="<?= base_url($user->picture) ?>" alt="Profile" style="max-width: 100px; max-height: 130px;" />
              </div>
              <div class="text-center">
                <span class="btn btn-file btn-default" style="font-size: 12px; padding: 4px 8px;">
                    <span class="fileupload-new">Select image</span>
                    <span class="fileupload-exists">Change</span>
                    <input type="file" name="picture" id="picture" />
                </span>
                <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none" title="Remove the selected picture">&times;</a>
              </div>  
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </fieldset>
    
    <fieldset>
      <legend>- Additional Information:</legend>
      <div>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-6"><input type="email" name="email" id="email" value="<?= set_value('email', $user->email) ?>" class="form-control" placeholder="Email" title="Email" required /></div>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-6"><input type="text" name="phone" id="phone" value="<?= set_value('phone', $user->phone) ?>" class="form-control" placeholder="Phone" title="Phone" required/></div>
        </div>
        <div class="clearfix"></div>
        
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-12"><input type="text" name="address" id="address" value="<?= set_value('address', $user->address) ?>" class="form-control" placeholder="Address" title="Address"/></div>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-6"><input type="text" name="social_id" id="social_id" value="<?= set_value('social_id', $user->social_id) ?>" class="form-control" placeholder="Social ID" title="Social ID" required/></div>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-6">
            <?= form_dropdown('id_type', $id_type_options, set_value('id_type', $user->id_type), "class='form-control' title='ID Type'") ?>
          </div>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-6">
            <input type="text" name="position" id="position" value="<?= set_value('position', $user->position) ?>" class="form-control" placeholder="Position" title="Position"/>
          </div>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-6"><input type="date" name="birth_date" id="birth_date" value="<?= set_value('birth_date', date('Y-m-d', $user->birth_date)) ?>" class="form-control" placeholder="Birth Date" title="Birth Date"/></div>
        </div>
        <div class="clearfix"></div>
      </div>
    </fieldset>
    
    <fieldset>
      <legend>- Account Settings:</legend>
      <div>
        <?php if(isset($bitauth) && $bitauth->is_admin()): ?>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-3">
            <label class="checkbox-inline" style="color: #0a78b4;"><input type="checkbox" name="active" id="active" value="1" <?= set_value('active', $user->active) ? 'checked' : '' ?>> Active</label>
          </div>
          <div class="col-md-3">
            <label class="checkbox-inline" style="color: #0a78b4;"><input type="checkbox" name="enabled" id="enabled" value="1" <?= set_value('enabled', $user->enabled) ? 'checked' : '' ?>> Enable</label>
          </div>
          <div class="col-md-6">
            <label class="checkbox-inline" style="color: #0a78b4;"><input type="checkbox" name="password_never_expires" id="password_never_expires" value="1" <?= set_value('password_never_expires', $user->password_never_expires) ? 'checked' : '' ?>> Password Never Expires</label>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="form-group" title="Select all groups you would like the user to be in." style="margin-bottom: 15px;">
          <div class="col-md-12">
              <label for="groups[]">Groups:</label>
              <?= form_multiselect('groups[]', $groups, set_value('groups[]', $user->groups), "class='form-control'") ?>
          </div>
        </div>
        <?php endif; ?>
        
        <?php if(isset($bitauth) && !$bitauth->is_admin()): ?>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-12"><input type="password" name="old_password" id="old_password" class="form-control" placeholder="Old Password" title="Old Password"/></div>
        </div>
        <?php endif; ?>
        
        <div class="form-group" title="Only enter a password if you would like to set a new one" style="margin-bottom: 15px;">
          <div class="col-md-6"><input type="password" name="password" id="password" class="form-control" placeholder="New Password" title="New Password"/></div>
          <div class="col-md-6"><input type="password" name="password_conf" id="password_conf" class="form-control" placeholder="Confirm New Password" title="Confirm New Password" /></div>
        </div>
        <div class="clearfix"></div>
      </div>
    </fieldset>
    
    <fieldset>
      <legend>- Memo:</legend>
      <div>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-12"><textarea name="memo" id="memo" class="form-control" rows="10"><?= set_value('memo', $user->memo) ?></textarea></div>
        </div>
      </div>
    </fieldset>
    
    <div class="form-group" style="margin-top: 20px;">
      <div class="col-md-6"><input type="submit" name="submit" id="submit" value="Update" class="form-control btn btn-info" style="height: 40px;" /></div>
      <div class="col-md-6"><a href="<?= base_url('account/users') ?>" class="form-control btn btn-info text-center" style="height: 40px; line-height: 26px;">Cancel</a></div>
    </div>
    
  <?= form_close() ?>
</div>
<?php else: ?>
  <div class="alert alert-danger text-center" style="margin-bottom: 20px;"><h2>User Not Found</h2></div>
  <div class="pull-right" title="Go to Users">
      <a href="<?= base_url('account/users') ?>" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>
  </div>
<?php endif; ?>