<div id='sidebar'>
  <div id="accordion" class="panel-group">
    
    <?php
      // Panggil library secara manual agar terbaca di View CI4
      $bitauth = new \App\Libraries\Bitauth();

      // Load Sidebar Murni HANYA Berdasarkan Role
      if($bitauth->is_admin()) {
          echo view('repository/sidebar/admin');
      }
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
      // Jika butuh sidebar khusus untuk role pasien:
      if($bitauth->has_role('patient')) {
          echo view('repository/sidebar/patient'); 
      }
    ?>

  </div>
</div>