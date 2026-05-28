<div style="padding: 35px 30px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.06); border: 1px solid #f0f0f0;">
  
  <div style="text-align: center; margin-bottom: 30px;">
    <!-- PERUBAHAN: Emotikon dihapus, bahasa lebih formal dan berwibawa -->
    <h2 style="color: #2c3e50; margin-top: 0; font-weight: 800;">Selamat Datang</h2>
    <p style="color: #666666; font-size: 14px; line-height: 1.6;">
      Silakan masuk menggunakan kredensial Anda untuk mengakses layanan, jadwal operasional, dan sistem rekam medis secara aman.
    </p>
  </div>

  <?= form_open('account/login', ['id' => 'loginForm', 'role' => 'form']) ?>
    
    <?php if (!empty($error)) : ?>
      <div class="alert alert-danger" style="font-size: 13px; padding: 10px 15px; border-radius: 4px;">
        <span class="glyphicon glyphicon-exclamation-sign" style="margin-right: 5px;"></span> <?= $error ?>
      </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')) : ?>
      <div class="alert alert-success" style="font-size: 13px; padding: 10px 15px; border-radius: 4px;">
        <span class="glyphicon glyphicon-ok-sign" style="margin-right: 5px;"></span> <?= session()->getFlashdata('success') ?>
      </div>
    <?php endif; ?>
    
    <div class="form-group" style="margin-bottom: 20px;">
      <label for="username" style="font-size: 13px; color: #555555; font-weight: bold;">Username</label>
      <input type="text" name="username" class="form-control" placeholder="Masukkan username Anda" required autofocus style="height: 42px; font-size: 14px; border-radius: 4px;">
    </div>
    
    <div class="form-group" style="margin-bottom: 20px;">
      <label for="password" style="font-size: 13px; color: #555555; font-weight: bold;">Kata Sandi</label>
      <input type="password" name="password" class="form-control" placeholder="Masukkan kata sandi" required style="height: 42px; font-size: 14px; border-radius: 4px;">
    </div>
    
    <div class="checkbox" style="margin-bottom: 25px;">
      <label style="font-size: 13px; color: #666666;">
        <input type="checkbox" name="remember_me" value="1" id="remember_me"> Ingat Sesi Saya
      </label>
    </div>
    
    <div class="form-group">
      <button type="submit" name="login" class="btn btn-primary btn-block" style="height: 45px; font-size: 15px; font-weight: bold; border-radius: 4px;">Login</button>
    </div>

    <!-- Hanya pasien yang diarahkan ke pendaftaran mandiri -->
    <div style="text-align: center; margin-top: 30px; padding-top: 25px; border-top: 1px solid #eeeeee;">
      <p style="color: #666666; font-size: 13px; margin-bottom: 12px;">Pendaftaran khusus untuk Pasien Baru:</p>
      <a href="<?= base_url('account/register') ?>" class="btn btn-default btn-block" style="height: 42px; font-size: 14px; color: #2c3e50; border-color: #cccccc; font-weight: bold; padding-top: 10px;">
        Registrasi Akun Pasien
      </a>
    </div>

  <?= form_close() ?>
</div>