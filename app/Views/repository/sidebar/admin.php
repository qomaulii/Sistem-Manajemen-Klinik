<div class="panel-group" id="accordion" style="margin-top: 15px;">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a href="#collapseAdmin" data-parent="#accordion" data-toggle="collapse" style="font-size: 14px; font-weight: bold; text-decoration: none;">
          <span class="glyphicon glyphicon-cog" style="margin-right: 5px;"></span> Administrator
        </a>
      </h4>
    </div>
    
    <div class="panel-collapse collapse in" id="collapseAdmin">
      <div class="panel-body" style="padding: 0;">
        <table class="table table-hover" style="margin-bottom: 0px; font-size: 14px;">
          <tbody>
            <tr>
              <td style="padding: 10px 15px;">
                <span class="glyphicon glyphicon-list-alt text-primary" style="margin-right: 8px;"></span> 
                <a href="<?= base_url('account/users') ?>" style="text-decoration: none; color: #333333;">Direktori Pengguna</a>
              </td>
            </tr>
            <tr>
              <td style="padding: 10px 15px;">
                <span class="glyphicon glyphicon-plus text-primary" style="margin-right: 8px;"></span> 
                <a href="<?= base_url('account/signup') ?>" style="text-decoration: none; color: #333333;">Tambah Pengguna Baru</a>
              </td>
            </tr>
            <tr>
              <td style="padding: 10px 15px;">
                <span class="glyphicon glyphicon-tags text-primary" style="margin-right: 8px;"></span> 
                <a href="<?= base_url('account/groups') ?>" style="text-decoration: none; color: #333333;">Direktori Grup</a>
              </td>
            </tr>
            <tr>
              <td style="padding: 10px 15px;">
                <span class="glyphicon glyphicon-folder-open text-primary" style="margin-right: 8px;"></span> 
                <a href="<?= base_url('account/add_group') ?>" style="text-decoration: none; color: #333333;">Membuat Grup Baru</a>
              </td>
            </tr>
            <tr>
              <td style="padding: 10px 15px; border-top: 2px solid #eeeeee;">
                <span class="glyphicon glyphicon-user text-primary" style="margin-right: 8px;"></span> 
                <a href="<?= base_url('account/edit_user/' . session()->get('ba_user_id')) ?>" style="text-decoration: none; color: #333333;">Pengaturan Profil Saya</a>
              </td>
            </tr>
            <tr>
              <td style="padding: 10px 15px;">
                <span class="glyphicon glyphicon-log-out text-danger" style="margin-right: 8px;"></span> 
                <a href="<?= base_url('account/logout') ?>" style="text-decoration: none; color: #d9534f; font-weight: bold;">Keluar (Logout)</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>