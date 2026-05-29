<!DOCTYPE html>
<html>
  <head>
    <title><?= esc(@$title ?: 'Sistem Manajemen Klinik') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Memanggil CSS Utama -->
    <link rel="stylesheet" href="<?= base_url('content/css/bootstrap.min.css') ?>" media="screen,print"/>
    <link rel="stylesheet" href="<?= base_url('content/css/bootstrap-fileupload.css') ?>"/>
    <link rel="stylesheet" href="<?= base_url('content/css/ui/jquery-ui.min.css') ?>" media="screen"/>
    <link rel="stylesheet" href="<?= base_url('content/css/print.css') ?>" media="print"/>
    
    <!-- Kustomisasi CSS untuk Presisi Layar Laptop -->
  <style>
    body { 
        font-family: Tahoma, sans-serif; 
        font-size: 14px;
    }
    legend { 
        color: #0a78b4; 
        font-size: 18px;
        border-bottom: 2px solid #e5e5e5;
        margin-bottom: 20px;
    }
    .form-control { 
        margin-bottom: 10px; 
        height: 35px; 
        font-size: 14px;
    }
    .modal-lg { 
        width: 850px;
    }
    /* HAPUS .container yang hardcode width */
    header {
        margin-bottom: 20px;
    }
  </style>
    
    <!-- Render CSS tambahan jika dikirim dari Controller -->
    <?php if(isset($css)) echo $css; ?>

    <!-- Memanggil File JavaScript -->
    <script src="<?= base_url('content/js/jquery-2.1.0.min.js') ?>"></script>
    <script src="<?= base_url('content/js/bootstrap.min.js') ?>"></script>
  </head>
  <body>
    <div class="container" style="padding-top: 20px;">
      <header>
        <section>
          <?php
            $headerLoggedIn = false;
            $headerBitauth  = null;

            $currentRoute = trim(uri_string(), '/');

            // Halaman yang benar-benar publik saja
            $publicRoutes = [
                'account/login',
                'account/register',
                'account/register_patient',
                'account/patient_register'
            ];

            $isPublicHeader = in_array($currentRoute, $publicRoutes, true);

            try {
                $headerBitauth = (isset($bitauth) && $bitauth instanceof \App\Libraries\Bitauth)
                    ? $bitauth
                    : new \App\Libraries\Bitauth();

                $headerLoggedIn = $headerBitauth->logged_in();
            } catch (\Throwable $e) {
                $headerLoggedIn = false;
            }

            if ($headerLoggedIn && !$isPublicHeader) {
                echo view('repository/nav', [
                    'title'   => $title ?? '',
                    'bitauth' => $headerBitauth
                ]);
            } else {
                echo view('repository/logo', [
                    'title' => $title ?? 'Sistem Manajemen Klinik'
                ]);
            }
          ?>
          </section>
        <div id="fixedNavPadding" style="margin-bottom: 72px;" class="hidden"></div>
      </header>
      
      <!-- Konten Utama Dimulai di Sini -->
      <div class="content">