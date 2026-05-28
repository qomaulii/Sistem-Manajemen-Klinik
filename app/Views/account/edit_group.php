<?php if (!empty($group)): ?>
  <div class="col col-md-8 well well-md" style="padding: 20px; background-color: #ffffff; border-radius: 8px;">
    <?= form_open(current_url(), ['class' => 'form-horizontal', 'id' => 'edit_group_form', 'role' => 'form']) ?>
      
      <?php if (isset($error) || session()->getFlashdata('error')): ?>
        <div class="alert alert-danger" style="margin-bottom: 15px;">
          <?= $error ?? session()->getFlashdata('error') ?>
        </div>
      <?php endif; ?>

      <fieldset>
        <legend style="font-size: 16px; font-weight: bold; color: #555555; border-bottom: 1px solid #eeeeee; padding-bottom: 10px;">- Informasi Identitas Grup</legend>
        <div>
          <div class="form-group" style="margin-bottom: 15px;">
            <label for="name" class="col col-md-3 control-label" style="font-size: 13px; color: #666666;">Nama Grup:</label>
            <div class="col col-md-9">
              <?= form_input('name', set_value('name', $group->name), 'class="form-control" required') ?>
            </div>
          </div>
          
          <div class="form-group" style="margin-bottom: 15px;">
            <label for="description" class="col col-md-3 control-label" style="font-size: 13px; color: #666666;">Deskripsi Singkat:</label>
            <div class="col col-md-9">
              <?= form_textarea('description', set_value('description', $group->description), 'class="form-control" style="height: 68px;"') ?>
            </div>
          </div>
          
          <div class="form-group" style="margin-bottom: 15px;">
            <label for="roles[]" class="col col-md-3 control-label" style="font-size: 13px; color: #666666;">Otorisasi Sistem <br><small class="text-muted">(Tahan CTRL)</small>:</label>
            <div class="col col-md-9">
              <?= form_multiselect('roles[]', $roles, (array)set_value('roles[]', $group_roles), 'class="form-control" title="" style="height: 120px;"') ?>
            </div>
          </div>
          
          <div class="form-group" style="margin-bottom: 20px;">
            <label for="members[]" class="col col-md-3 control-label" style="font-size: 13px; color: #666666;">Anggota Grup <br><small class="text-muted">(Tahan CTRL)</small>:</label>
            <div class="col col-md-9">
              <?= form_multiselect('members[]', $users, (array)set_value('members[]', $group->members), 'class="form-control" style="height: 120px;"') ?>
            </div>
          </div>
        </div>
        
        <div class="form-group" style="margin-top: 30px;">
          <div class="col-md-offset-3 col-md-9">
            <div class="col col-md-6" style="padding-left: 0;">
              <input type="submit" name="submit" id="submit" value="Simpan Pembaruan" class="form-control btn btn-primary" style="height: 40px; font-weight: bold;" />
            </div> 
            <div class="col col-md-6" style="padding-right: 0;">
              <a href="<?= base_url('account/groups') ?>" class="form-control btn btn-default text-center" style="height: 40px; line-height: 26px;">Batal & Kembali</a>
            </div>
          </div>
        </div>
      </fieldset>
    <?= form_close() ?>
  </div>
<?php else: ?>
  <div style="margin-bottom: 20px;">
    <h2>Grup Tidak Ditemukan</h2>
  </div>
  <div>
    <a href="<?= base_url('account/groups') ?>" class="btn btn-default">Kembali ke Direktori</a>
  </div>
<?php endif; ?>