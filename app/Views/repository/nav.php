<nav id="main_nav" class="navbar navbar-default" role="navigation" style="margin-bottom: 20px;">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#" onclick="$('#main_nav').toggleClass('navbar-fixed-top');$('#fixedNavPadding').toggleClass('hidden');return false;">Clinic</a>
  </div>
  
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
      <li id="navbarLiHome"><a href="<?= base_url() ?>"><span class="glyphicon glyphicon-home"></span> Home</a></li>
      
      <?php
        // Panggil library secara manual agar terbaca di View CI4
        $bitauth = new \App\Libraries\Bitauth();

        // Pemanggilan Sub-Navigasi berdasarkan Role
        if($bitauth->is_admin()) echo view('repository/nav/admin');
        elseif ($bitauth->has_role('doctor')) echo view('repository/nav/doctor');
        elseif ($bitauth->has_role('xray')) echo view('repository/nav/xray');
        elseif ($bitauth->has_role('lab')) echo view('repository/nav/lab');
        elseif ($bitauth->has_role('pharmacy')) echo view('repository/nav/pharmacy');
        elseif ($bitauth->has_role('receptionist')) echo view('repository/nav/receptionist');
        else echo view('repository/nav/default');
      ?>

      <!-- Fitur Cepat Cari Pasien Berdasarkan ID -->
      <li id="navbarGoTo">
          <div style="padding: 10px 15px;">
              <input type='number' placeholder='Patient ID...' id='goToPatient' class="form-control" style="width: 150px; height: 30px;" data-url="<?= base_url('patient/panel') ?>"/>
          </div>
      </li>
      
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <span class="glyphicon glyphicon-user"></span> 
          <?= session()->get('ba_first_name').' '.session()->get('ba_last_name') ?> <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
          <li><a href="<?= base_url('account/edit_user/'.session()->get('ba_user_id')) ?>"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
          <li class="divider"></li>
          <li><a href="<?= base_url('account/logout') ?>"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
        </ul>
      </li>
    </ul>
  </div>

  <script>
    $(document).ready(function(){
      <?php if(isset($navActiveId)): ?>
        $('#<?= $navActiveId ?>').addClass('active');
      <?php endif; ?>

      // Logika Enter untuk Patient ID
      $('#goToPatient').keypress(function(e){
          if(e.which == 13 && $(this).val() != ''){
              window.location = $(this).attr('data-url') + '/' + $(this).val();
          }
      });
    });
  </script>
</nav>