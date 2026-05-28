<nav id="main_nav" class="navbar navbar-default" role="navigation" style="margin-bottom: 20px; border-radius: 4px; border: 1px solid #e7e7e7; background-color: #f8f8f8;">
  <div class="container-fluid" style="display: flex; justify-content: space-between; align-items: center; height: 50px;">
    
    <div class="navbar-header">
      <span class="navbar-brand" style="font-weight: bold; font-size: 16px; color: #2c3e50; cursor: default;">
        Halo, <?= session()->get('ba_first_name') ?: 'Administrator' ?>!
      </span>
    </div>

    <ul class="nav navbar-nav" style="margin: 0;">
      <li>
        <a href="<?= base_url('account/logout') ?>" style="color: #d9534f; font-weight: bold; padding: 15px 15px;">
          <span class="glyphicon glyphicon-off"></span> Keluar Sistem
        </a>
      </li>
    </ul>

  </div>
</nav>