<div id='sidebar'>
  <div id="accordion" class="panel-group">
    
    <?php
      $bitauth = new \App\Libraries\Bitauth();

      // PERBAIKAN: Jika Admin, HANYA tampilkan menu Administrator.
      if($bitauth->is_admin()) {
          echo view('repository/sidebar/admin');
      } 
      // Jika BUKAN Admin, barulah cek role lainnya
      else {
          if($bitauth->has_role('doctor')) {
              echo view('repository/sidebar/doctor');
          }
          if($bitauth->has_role('pharmacy')) {
              echo view('repository/sidebar/pharmacy');
          }
          if($bitauth->has_role('xray')) {
              echo view('repository/sidebar/xray');
          }
          if($bitauth->has_role('lab')) {
              echo view('repository/sidebar/lab');
          }
          if($bitauth->has_role('receptionist')) {
              echo view('repository/sidebar/receptionist');
          }
          if($bitauth->has_role('patient')) {
              echo view('repository/sidebar/patient'); 
          }
      }
    ?>

  </div>
</div>