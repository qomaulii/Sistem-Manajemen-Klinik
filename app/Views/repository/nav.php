<?php
  $nama_user = session()->get('ba_first_name') ?: session()->get('ba_username') ?: 'Pengguna';

  $judul_menu = trim($title ?? '');

  $judul_kosong = [
      '',
      'Sistem Manajemen Klinik',
      'Sistem Manajemen Klinik',
      'Login'
  ];

  if (in_array($judul_menu, $judul_kosong, true)) {
      $judul_menu = '';
  }
?>

<nav id="main_nav" class="navbar navbar-default" role="navigation" style="margin-bottom: 20px; border-radius: 4px; border: 1px solid #e7e7e7; background-color: #f8f8f8;">
  <div class="container-fluid" style="display: flex; justify-content: space-between; align-items: center; min-height: 50px;">
    
    <div class="navbar-header">
      <span class="navbar-brand" style="font-weight: bold; font-size: 16px; color: #2c3e50; cursor: default;">
        Halo, <?= esc($nama_user) ?>!
      </span>
    </div>

    <div style="font-weight: bold; font-size: 15px; color: #0a78b4; padding: 15px 15px; text-align: right;">
      <?php if ($judul_menu !== '') : ?>
        <span class="glyphicon glyphicon-menu-right" style="margin-right: 6px;"></span>
        <?= esc($judul_menu) ?>
      <?php endif; ?>
    </div>

  </div>
</nav>