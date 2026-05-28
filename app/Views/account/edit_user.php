<?php if (!empty($user)): ?>
<link rel="stylesheet" href="<?= base_url('content/css/bootstrap-fileupload.min.css') ?>" media="screen"/>
<script src="<?= base_url('content/js/bootstrap-fileupload.js') ?>"></script>

<div class="col col-md-8 well well-md" style="padding: 20px; background-color: #ffffff; border-radius: 8px;">
  <?= form_open_multipart('account/edit_user/' . $user->user_id, ['id' => 'edituserForm', 'role' => 'form']) ?>
  
  <div style="margin-bottom: 24px; color: #333333; text-align: center; border-bottom: 1px solid #eeeeee; padding-bottom: 10px;">
    <h3 style="margin: 0; font-size: 20px;">Edit Data Staf</h3>
    <strong style="font-size: 16px; color: #007bff;"><?= esc($user->username) ?></strong>
    <input type="hidden" name="username" value="<?= set_value('username', $user->username) ?>"/>
  </div>
  
  <?= !empty($error) ? '<div class="alert alert-danger" style="margin-bottom: 15px;">' . $error . '</div>' : '' ?>
  
    <fieldset style="margin-bottom: 20px;">
      <legend style="font-size: 16px; font-weight: bold; color: #555555; border-bottom: none;">- Informasi Pribadi:</legend>
      <div>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-9">
            <div style="margin-bottom: 10px;"><input type="text" name="first_name" id="first_name" value="<?= set_value('first_name', $user->first_name) ?>" class="form-control" placeholder="Nama Depan" required autofocus /></div>
            <div style="margin-bottom: 10px;"><input type="text" name="last_name" id="last_name" value="<?= set_value('last_name', $user->last_name) ?>" class="form-control" placeholder="Nama Belakang" /></div>
            
            <div style="margin-bottom: 10px;"><input type="text" name="nip" id="nip" value="<?= set_value('nip', $user->nip) ?>" class="form-control" placeholder="NIP (Nomor Induk Pegawai)" /></div>
            <div style="margin-bottom: 10px;"><input type="text" name="nik" id="nik" value="<?= set_value('nik', $user->nik) ?>" class="form-control" placeholder="NIK (Nomor Induk Kependudukan)" required /></div>
            
            <div class="col-md-12" style="margin-bottom: 10px; padding-left: 0;">
              <label class="radio-inline"><input type="radio" name="gender" value="1" <?= set_value('gender', $user->gender) == '1' ? 'checked' : '' ?> />Laki-laki</label>
              <label class="radio-inline"><input type="radio" name="gender" value="0" <?= set_value('gender', $user->gender) == '0' ? 'checked' : '' ?> />Perempuan</label>
            </div>
          </div>
          <div class="col-md-3">
            <div class="fileupload fileupload-new" data-provides="fileupload">
              <div class="fileupload-preview thumbnail" style="width: 120px; height: 140px; border: 1px solid #dddddd;">
                  <img src="<?= base_url($user->picture ?? 'content/img/default-profile.png') ?>" alt="Profil" style="max-width: 100px; max-height: 130px;" />
              </div>
              <div class="text-center">
                <span class="btn btn-file btn-default" style="font-size: 12px; padding: 4px 8px;">
                    <span class="fileupload-new">Pilih Foto</span>
                    <span class="fileupload-exists">Ubah</span>
                    <input type="file" name="picture" id="picture" accept="image/*" />
                </span>
                <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">&times;</a>
              </div>  
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </fieldset>
    
    <fieldset style="margin-bottom: 20px;">
      <legend style="font-size: 16px; font-weight: bold; color: #555555; border-bottom: none;">- Informasi Pekerjaan & Kontak:</legend>
      <div>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-6"><input type="email" name="email" id="email" value="<?= set_value('email', $user->email) ?>" class="form-control" placeholder="Email" required /></div>
          <div class="col-md-6"><input type="text" name="phone" id="phone" value="<?= set_value('phone', $user->phone) ?>" class="form-control" placeholder="Nomor Handphone" required/></div>
        </div>
        <div class="clearfix"></div>
        
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-12"><input type="text" name="address" id="address" value="<?= set_value('address', $user->address) ?>" class="form-control" placeholder="Alamat Tempat Tinggal"/></div>
        </div>
        
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-6">
            <input type="text" name="position" id="position" value="<?= set_value('position', $user->position) ?>" class="form-control" placeholder="Spesialisasi / Jabatan (Msl: Dokter Anak)" required/>
          </div>
          <div class="col-md-6">
            <input type="date" name="birth_date" id="birth_date" value="<?= set_value('birth_date', date('Y-m-d', (int)$user->birth_date)) ?>" class="form-control" title="Tanggal Lahir"/>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </fieldset>
    
    <fieldset style="margin-bottom: 20px;">
      <legend style="font-size: 16px; font-weight: bold; color: #555555; border-bottom: none;">- Pengaturan Akun Sistem:</legend>
      <div>
        <?php if(isset($bitauth) && $bitauth->is_admin()): ?>
        <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-6">
            <label for="active" style="font-size: 13px; color: #666666;">Status Akun (Otorisasi Login):</label>
            <select name="active" id="active" class="form-control">
                <option value="1" <?= set_value('active', $user->active) == '1' ? 'selected' : '' ?>>Aktif</option>
                <option value="0" <?= set_value('active', $user->active) == '0' ? 'selected' : '' ?>>Suspend (Ditangguhkan)</option>
            </select>
          </div>
          <div class="col-md-6">
              <label for="groups[]" style="font-size: 13px; color: #666666;">Grup Hak Akses (Izin Buka Menu):</label>
              <?= form_multiselect('groups[]', $groups, set_value('groups[]', $user->groups), "class='form-control'") ?>
          </div>
        </div>
        <div class="clearfix"></div>
        <?php endif; ?>
        
        <div class="form-group" style="margin-bottom: 15px; margin-top: 15px;">
          <div class="col-md-12">
            <label style="font-size: 13px; color: #666666;">Reset Kata Sandi (Kosongkan jika tidak ingin mengubah):</label>
          </div>
          <div class="col-md-6"><input type="password" name="password" id="password" class="form-control" placeholder="Kata Sandi Baru"/></div>
          <div class="col-md-6"><input type="password" name="password_conf" id="password_conf" class="form-control" placeholder="Konfirmasi Kata Sandi Baru" /></div>
        </div>
        <div class="clearfix"></div>
      </div>
    </fieldset>
    
    <div class="form-group" style="margin-top: 30px; border-top: 1px solid #eeeeee; padding-top: 20px;">
      <div class="col-md-6"><input type="submit" name="submit" id="submit" value="Simpan Perubahan" class="form-control btn btn-primary" style="height: 42px; font-weight: bold;" /></div>
      <div class="col-md-6"><a href="<?= base_url('account/users') ?>" class="form-control btn btn-default text-center" style="height: 42px; line-height: 28px;">Batal & Kembali</a></div>
    </div>
    
  <?= form_close() ?>
</div>
<?php else: ?>
  <div class="alert alert-danger text-center" style="margin-bottom: 20px;"><h2>Data Staf Tidak Ditemukan</h2></div>
  <div class="pull-right">
      <a href="<?= base_url('account/users') ?>" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
  </div>
<?php endif; ?>