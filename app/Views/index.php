<section class="row mt-4">
    
    <?php 
      // Memanggil pustaka Bitauth untuk mengecek status login di level View
      $bitauth = new \App\Libraries\Bitauth();
      $is_logged_in = $bitauth->logged_in();

      $is_public_page = (isset($title) && (strtolower($title) === 'login' || strtolower($title) === 'registrasi pasien'));
      
      // PERBAIKAN: Jika pengguna belum login dan memaksa masuk ke rute selain Login/Register (misal rute '/'),
      // sistem akan secara paksa menjadikannya halaman publik dan merender form login.
      if (!$is_logged_in && !$is_public_page && !isset($view_content)) {
          $is_public_page = true;
          $includes = ['account/login'];
      }

      $colClass = $is_public_page ? 'col-md-6 col-md-offset-3 col-sm-12' : 'col-md-9 col-sm-12'; 
    ?>

    <?php if (!$is_public_page && $is_logged_in) : ?>
      <aside class="col-md-3 col-sm-12 mb-4">
          <?= view('repository/sidebar') ?>
      </aside>
    <?php endif; ?>
    
    <article class="<?= $colClass ?>" id="mainContent" style="<?= $is_public_page ? 'margin-top: 40px;' : '' ?>"> 
        <?php 
        if (isset($view_content)) {
            echo view($view_content);
        } 
        // TAMBAHKAN LOGIKA: Hanya tampilkan dasbor jika TIDAK di halaman publik DAN sudah login
        elseif (!$is_public_page && isset($bitauth) && $bitauth->logged_in()) {
            if ($bitauth->has_role('patient') && !$bitauth->is_admin()) {
                // Tampilan dasbor Pasien
                ?>
                <div style="padding: 24px; background-color: #ffffff; border-left: 5px solid #3498db; ...">
                    <h3>Portal Rekam Medis Pasien</h3>
                    <p>Selamat datang, Anda dapat melihat riwayat kunjungan melalui menu di samping.</p>
                </div>
                <?php
            } else {
                // Tampilan dasbor Staf/Admin
                ?>
                <div style="margin-top: 20px; margin-bottom: 20px; padding: 24px; background-color: #ffffff; border-left: 5px solid #48c9b0; ...">
                    <h3>Halo, Selamat Bekerja!</h3>
                    <p>Silakan gunakan menu navigasi untuk mengelola operasional klinik.</p>
                </div>
                <?php
            }
        }
        
        // Memuat form login/register/dll
        if (isset($includes) && is_array($includes)) {
            foreach ($includes as $include) {
                echo view($include);
            }
        }
        ?>
    </article>

</section>