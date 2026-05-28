<link rel="stylesheet" href="<?= base_url('content/css/bootstrap-fileupload.min.css') ?>" media="screen"/>
<script src="<?= base_url('content/js/bootstrap-fileupload.js') ?>"></script>

<div style="padding: 35px 30px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.06); border: 1px solid #f0f0f0; margin-bottom: 30px;">
  
  <div style="text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #eeeeee;">
    <h2 style="color: #2c3e50; margin-top: 0; font-weight: 800;">Registrasi Pasien Baru</h2>
    <p style="color: #666666; font-size: 14px; line-height: 1.6;">
      Lengkapi formulir pendataan di bawah ini. Pastikan data yang dimasukkan valid untuk keperluan verifikasi rekam medis Anda.
    </p>
  </div>

  <?= form_open_multipart('account/register', ['id' => 'registerForm', 'role' => 'form']) ?>
    
    <?php if (session()->getFlashdata('error')) : ?>
      <div class="alert alert-danger" style="font-size: 13px; padding: 10px 15px; border-radius: 4px;">
        <span class="glyphicon glyphicon-exclamation-sign" style="margin-right: 5px;"></span> <?= session()->getFlashdata('error') ?>
      </div>
    <?php endif; ?>

    <h4 style="font-size: 15px; font-weight: bold; color: #2c3e50; margin-bottom: 15px;">- Informasi Kredensial (Akun)</h4>
    <div class="row">
      <div class="col-md-12 form-group" style="margin-bottom: 15px;">
        <input type="text" name="username" value="<?= set_value('username') ?>" class="form-control" placeholder="Buat Username (Untuk Login)" required autofocus style="height: 40px; border-radius: 4px;">
      </div>
      <div class="col-md-6 form-group" style="margin-bottom: 15px;">
        <input type="password" name="password" class="form-control" placeholder="Buat Kata Sandi" required style="height: 40px; border-radius: 4px;">
      </div>
      <div class="col-md-6 form-group" style="margin-bottom: 25px;">
        <input type="password" name="password_conf" class="form-control" placeholder="Ulangi Kata Sandi" required style="height: 40px; border-radius: 4px;">
      </div>
    </div>

    <h4 style="font-size: 15px; font-weight: bold; color: #2c3e50; margin-bottom: 15px;">- Identitas Pasien</h4>
    <div class="row">
      <div class="col-md-9">
        <div class="row">
            <div class="col-md-6 form-group" style="margin-bottom: 15px;">
              <input type="text" name="first_name" value="<?= set_value('first_name') ?>" class="form-control" placeholder="Nama Depan" required style="height: 40px; border-radius: 4px;">
            </div>
            <div class="col-md-6 form-group" style="margin-bottom: 15px;">
              <input type="text" name="last_name" value="<?= set_value('last_name') ?>" class="form-control" placeholder="Nama Belakang" style="height: 40px; border-radius: 4px;">
            </div>
            <div class="col-md-12 form-group" style="margin-bottom: 15px;">
              <input type="text" name="nik" value="<?= set_value('nik') ?>" class="form-control" placeholder="Nomor Induk Kependudukan (NIK - 16 Digit)" required style="height: 40px; border-radius: 4px;">
            </div>
            <div class="col-md-12 form-group" style="margin-bottom: 15px;">
              <input type="text" name="nip" value="<?= set_value('nip') ?>" class="form-control" placeholder="Nomor Induk Pegawai (NIP) - Opsional" style="height: 40px; border-radius: 4px;">
            </div>
            
            <div class="col-md-6 form-group" style="margin-bottom: 15px;">
              <label for="birth_date" style="font-size: 12px; color: #666;">Tanggal Lahir:</label>
              <input type="date" name="birth_date" id="birth_date" value="<?= set_value('birth_date') ?>" class="form-control" required style="height: 40px; border-radius: 4px;">
            </div>
            
            <div class="col-md-6 form-group" style="margin-bottom: 15px; padding-top: 25px;">
              <label class="radio-inline" style="font-size: 13px; color: #555555;"><input type="radio" name="gender" value="1" <?= set_value('gender') == '1' ? 'checked' : '' ?> />Laki-laki</label>
              <label class="radio-inline" style="font-size: 13px; color: #555555;"><input type="radio" name="gender" value="0" <?= set_value('gender') == '0' ? 'checked' : '' ?> />Perempuan</label>
            </div>
        </div>
      </div>
      
      <div class="col-md-3">
        <div class="fileupload fileupload-new" data-provides="fileupload" style="text-align: center;">
          <label style="font-size: 12px; color: #666;">Foto Profil (Opsional):</label>
          <div class="fileupload-preview thumbnail" style="width: 100%; height: 140px; border: 1px solid #dddddd; margin-bottom: 10px;">
              <img src="<?= base_url('content/img/default-profile.png') ?>" alt="Profil" style="max-height: 130px;" />
          </div>
          <div>
            <span class="btn btn-file btn-default" style="font-size: 12px; padding: 4px 8px; width: 100%;">
                <span class="fileupload-new">Pilih Foto</span>
                <span class="fileupload-exists">Ubah</span>
                <input type="file" name="picture" id="picture" accept="image/*" />
            </span>
            <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none; font-size: 18px;">&times;</a>
          </div>  
        </div>
      </div>

      <div class="col-md-12 form-group" style="margin-bottom: 25px; background-color: #f8f9fa; padding: 15px; border-radius: 4px; border: 1px solid #e9ecef;">
        <label id="identity_label" for="identity_document" style="font-size: 13px; font-weight: bold; color: #d9534f;">
          Masukkan Tanggal Lahir terlebih dahulu untuk menentukan dokumen identitas yang wajib diunggah.
        </label>
        <input type="file" name="identity_document" id="identity_document" class="form-control" accept="image/jpeg, image/png, image/jpg" required style="height: auto; padding: 5px; border-radius: 4px;">
        <small style="color: #888; font-size: 11px;">Format yang didukung: JPG, JPEG, atau PNG.</small>
      </div>
    </div>

    <h4 style="font-size: 15px; font-weight: bold; color: #2c3e50; margin-bottom: 15px;">- Kontak & Alamat</h4>
    <div class="row">
      <div class="col-md-6 form-group" style="margin-bottom: 15px;">
        <input type="email" name="email" value="<?= set_value('email') ?>" class="form-control" placeholder="Alamat Email Aktif" required style="height: 40px; border-radius: 4px;">
      </div>
      <div class="col-md-6 form-group" style="margin-bottom: 15px;">
        <input type="text" name="phone" value="<?= set_value('phone') ?>" class="form-control" placeholder="Nomor Telepon / WhatsApp" required style="height: 40px; border-radius: 4px;">
      </div>
      <div class="col-md-12 form-group" style="margin-bottom: 25px;">
        <input type="text" name="address" value="<?= set_value('address') ?>" class="form-control" placeholder="Alamat Tempat Tinggal" required style="height: 40px; border-radius: 4px;">
      </div>
    </div>
    
    <div class="form-group" style="margin-top: 10px;">
      <button type="submit" name="submit" class="btn btn-primary btn-block" style="height: 45px; font-size: 15px; font-weight: bold; border-radius: 4px;">Registrasi Akun Pasien</button>
    </div>

    <div style="text-align: center; margin-top: 25px;">
      <p style="color: #666666; font-size: 13px;">Sudah memiliki akun?</p>
      <a href="<?= base_url('account/login') ?>" class="btn btn-default btn-block" style="height: 42px; font-size: 14px; color: #2c3e50; font-weight: bold; padding-top: 10px; border-radius: 4px;">
        Kembali ke Halaman Login
      </a>
    </div>

  <?= form_close() ?>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const birthDateInput = document.getElementById('birth_date');
    const identityLabel  = document.getElementById('identity_label');

    function calculateAge(dobString) {
      if(!dobString) return -1;
      const dob = new Date(dobString);
      const today = new Date();
      let age = today.getFullYear() - dob.getFullYear();
      const m = today.getMonth() - dob.getMonth();
      if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
        age--;
      }
      return age;
    }

    birthDateInput.addEventListener('change', function() {
      const age = calculateAge(this.value);
      if (age >= 17) {
        identityLabel.innerHTML = 'Unggah Foto KTP Pribadi <span style="color:red;">*</span>';
      } else if (age >= 0 && age < 17) {
        identityLabel.innerHTML = 'Unggah Foto Kartu Keluarga (KK) / KTP Wali <span style="color:red;">*</span>';
      } else {
        identityLabel.innerHTML = 'Masukkan Tanggal Lahir terlebih dahulu untuk menentukan dokumen identitas yang wajib diunggah.';
      }
    });
  });
</script>