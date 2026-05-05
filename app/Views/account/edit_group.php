<?php if (!empty($group)): ?>
  <div class="col col-md-8 well well-md" style="padding: 20px;">
    <?= form_open(current_url(), ['class' => 'form-horizontal', 'id' => 'edit_group_form', 'role' => 'form']) ?>
      
      <?php if (validation_errors()): ?>
        <div class="alert alert-danger" style="margin-bottom: 15px;">
          <?= validation_errors() ?>
        </div>
      <?php endif; ?>

      <fieldset>
        <legend>- Group Information</legend>
        <div>
          <div class="form-group" style="margin-bottom: 15px;">
            <label for="name" class="col col-md-3 control-label">Name:</label>
            <div class="col col-md-9">
              <?= form_input('name', set_value('name', $group->name), 'class="form-control"') ?>
            </div>
          </div>
          
          <div class="form-group" style="margin-bottom: 15px;">
            <label for="description" class="col col-md-3 control-label">Description:</label>
            <div class="col col-md-9">
              <?= form_textarea('description', set_value('description', $group->description), 'class="form-control" style="height: 68px;"') ?>
            </div>
          </div>
          
          <div class="form-group" style="margin-bottom: 15px;">
            <label for="roles[]" class="col col-md-3 control-label">Role:</label>
            <div class="col col-md-9">
              <?= form_multiselect('roles[]', $roles, set_value('roles[]', $group_roles), 'class="form-control" title="" style="height: 120px;"') ?>
            </div>
          </div>
          
          <div class="form-group" style="margin-bottom: 20px;">
            <label for="members[]" class="col col-md-3 control-label">Members:</label>
            <div class="col col-md-9">
              <?= form_multiselect('members[]', $users, set_value('members[]', $group->members), 'class="form-control" style="height: 120px;"') ?>
            </div>
          </div>
        </div>
        
        <div class="form-group">
          <div class="col-md-offset-3 col-md-9">
            <div class="col col-md-6" style="padding-left: 0;">
              <input type="submit" name="submit" id="submit" value="Update" class="form-control btn btn-info" style="height: 40px;" />
            </div> 
            <div class="col col-md-6" style="padding-right: 0;">
              <a href="<?= base_url('account/groups') ?>" class="form-control btn btn-info text-center" style="height: 40px; line-height: 26px;">Cancel</a>
            </div>
          </div>
        </div>
      </fieldset>
    <?= form_close() ?>
  </div>
<?php else: ?>
  <div style="margin-bottom: 20px;">
    <h2>Group Not Found</h2>
  </div>
  <div>
    <a href="<?= base_url('account/groups') ?>" class="btn btn-default">Go Back</a>
  </div>
<?php endif; ?>