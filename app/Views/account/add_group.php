<div class="col col-md-8 well well-sm well-md" style="padding: 20px;">
  <?= form_open(current_url(), ['class' => 'form-horizontal', 'id' => 'edit_group_form', 'role' => 'form']) ?>
    
    <!-- Perbaikan pesan error untuk CI4 -->
    <?php if (isset($error) && !empty($error)): ?>
      <div class="alert alert-danger" style="margin-bottom: 15px;">
        <?= $error ?>
      </div>
    <?php endif; ?>

    <fieldset>
      <legend>- Group Information</legend>
      <div>
        <div class="form-group" style="margin-bottom: 15px;">
          <label for="name" class="col col-md-3 control-label">Name:</label>
          <div class="col col-md-9">
            <?= form_input('name', set_value('name'), 'class="form-control" title="Group Name" placeholder="Group Name" required') ?>
          </div>
        </div>
        
        <div class="form-group" style="margin-bottom: 15px;">
          <label for="description" class="col col-md-3 control-label">Description:</label>
          <div class="col col-md-9">
            <?= form_textarea('description', set_value('description'), 'class="form-control" title="Description" placeholder="Description" style="height: 68px;"') ?>
          </div>
        </div>
        
        <div class="form-group" style="margin-bottom: 15px;">
          <label for="roles[]" class="col col-md-3 control-label">Role:</label>
          <div class="col col-md-9">
            <!-- Perbaikan: Memaksa set_value menjadi (array) untuk menghindari TypeError -->
            <?= form_multiselect('roles[]', $roles, (array)set_value('roles[]', ['guest']), 'class="form-control" title="Role" required style="height: 120px;"') ?>
          </div>
        </div>
        
        <div class="form-group" style="margin-bottom: 20px;">
          <label for="members[]" class="col col-md-3 control-label">Members:</label>
          <div class="col col-md-9">
            <!-- Perbaikan: Memaksa set_value menjadi (array) -->
            <?= form_multiselect('members[]', $users, (array)set_value('members[]', []), 'class="form-control" title="Members" style="height: 120px;"') ?>
          </div>
        </div>
        
        <div class="form-group">
          <div class="col-md-offset-3 col-md-9">
            <div class="col col-md-6" style="padding-left: 0;">
              <input type="submit" name="submit" id="submit" value="Create" class="form-control btn btn-info" style="height: 40px;" />
            </div> 
            <div class="col col-md-6" style="padding-right: 0;">
              <a href="<?= base_url('account/groups') ?>" class="form-control btn btn-info text-center" style="height: 40px; line-height: 26px;">Cancel</a>
            </div>
          </div>
        </div>
      </div>
    </fieldset>
  <?= form_close() ?>
</div>