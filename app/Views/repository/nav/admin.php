<li class="dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <span class="glyphicon glyphicon-user"></span> Manajemen Pengguna <b class="caret"></b>
  </a>
  <ul class="dropdown-menu" style="min-width: 220px; padding: 10px 0px;">
    <li style="padding: 5px 20px;"><strong>- Kelola Akun</strong></li>
    <li><a href="<?= base_url('account/users') ?>"><span class="glyphicon glyphicon-list-alt"></span> Lihat Semua Pengguna</a></li>
    
    <li><a href="<?= base_url('account/signup') ?>"><span class="glyphicon glyphicon-plus"></span> Tambah Pengguna Baru</a></li>
    <li class="divider"></li>
    
    <li style="padding: 5px 20px;"><strong>- Kontrol Hak Akses</strong></li>
    <li><a href="<?= base_url('account/groups') ?>"><span class="glyphicon glyphicon-tags"></span> Direktori Grup</a></li>
    <li><a href="<?= base_url('account/add_group') ?>"><span class="glyphicon glyphicon-lock"></span> Buat Grup Baru</a></li>
  </ul>
</li>

<li class="dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <span class="glyphicon glyphicon-cog"></span> Pengaturan Sistem <b class="caret"></b>
  </a>
  <ul class="dropdown-menu" style="min-width: 200px;">
    <li><a href="<?= base_url('setting') ?>"><span class="glyphicon glyphicon-wrench"></span> Pengaturan Umum</a></li>
    
    <li><a href="<?= base_url('report_bug/add') ?>"><span class="glyphicon glyphicon-bullhorn"></span> Laporkan Masalah</a></li>
  </ul>
</li>