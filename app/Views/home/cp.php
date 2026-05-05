<div id="cPanel" class="">
  <?php
    // Jika tidak ada user terdaftar (untuk setup awal)
    if(isset($bitauth) && !$bitauth->get_users()) {
        echo view('home/cp/admin');
    }

    if(isset($bitauth) && $bitauth->logged_in()) {
        // Jika admin, tampilkan menu admin
        if($bitauth->is_admin()) {
            echo view('home/cp/admin');
        }

        // Semua user yang login (biasanya dokter) bisa melihat menu dokter
        echo view('home/cp/doctor');

        if($bitauth->has_role('receptionist')) {
            echo view('home/cp/receptionist');
        }

        if($bitauth->has_role('pharmacy')) {
            echo view('home/cp/pharmacy');
        }

        if($bitauth->has_role('lab')) {
            echo view('home/cp/lab');
        }

        if($bitauth->has_role('xray')) {
            echo view('home/cp/xray');
        }
    }
  ?>
  
  <style>
    /* Ukuran pixel presisi sesuai permintaanmu */
    #cPanel a {
        width: 180px;
        height: 80px;
        margin-right: 8px;
        margin-bottom: 8px;
        border-radius: 0px;
        font-size: 14px; /* Medium set to pixel */
        padding: 15px;
        display: inline-block;
        text-align: center;
    }
    #cPanel .glyphicon {
        font-size: 20px;
        margin-bottom: 5px;
    }
  </style>
</div>