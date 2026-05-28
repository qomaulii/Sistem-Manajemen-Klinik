<legend><?= "- " . esc(@$title) ?></legend>

<?php if (session()->getFlashdata('success')) : ?>
    <script>
        window.onload = function() {
            alert("<?= session()->getFlashdata('success') ?>");
        };
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger" style="margin-bottom: 15px;">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if(!empty($users)): ?>
  <div>
    <?= $pagination ?>
    <div class='table-responsive'>
      <table class='table table-bordered table-striped' style="font-size: 14px;">
        <thead>
          <tr>
            <th>Nama Lengkap</th>
            <th>Jenis Kelamin</th>
            <th>Jabatan</th>
            <th>Email</th>
            <th>Nomor HP</th>
            <th class="text-center">Status Akun</th>
            <th class='hidden-print text-center' style="width: 120px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $_user) : ?>
          <tr id="<?= $_user->user_id ?>" title="<?= esc($_user->first_name . ' ' . $_user->last_name ?: $_user->username) ?>">
            <td><?= esc($_user->first_name . ' ' . $_user->last_name) ?></td>
            
            <td><?= (isset($_user->gender) && $_user->gender == '1') ? 'Laki-laki' : ((isset($_user->gender) && $_user->gender == '0') ? 'Perempuan' : '-') ?></td>
            
            <td><?= esc($_user->position) ?></td>
            <td><?= esc($_user->email) ?></td>
            <td><?= esc($_user->phone) ?></td>
            
            <td class="text-center" style="vertical-align: middle;">
                <?php if ($_user->active == 1): ?>
                    <span class="label label-success" style="font-size: 12px; padding: 4px 8px;">Aktif</span>
                <?php else: ?>
                    <span class="label label-warning" style="font-size: 12px; padding: 4px 8px; color: #333;">Suspend</span>
                <?php endif; ?>
            </td>
            
            <td class="hidden-print text-center" style="vertical-align: middle;">
              <?php if(isset($bitauth) && $bitauth->is_admin()) : ?>
                <a href="<?= base_url('account/edit_user/' . $_user->user_id) ?>" title="Edit Data Pengguna" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-edit"></span></a>
                
                <?php if($_user->active == 1) : ?>
                  <a href="<?= base_url('account/toggle_status/' . $_user->user_id) ?>" onclick="return confirm('Apakah Anda yakin ingin me-SUSPEND akun ini? Pengguna yang di-suspend tidak akan bisa masuk ke dalam sistem.');" title="Suspend Akun" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-pause"></span></a>
                <?php else : ?>
                  <a href="<?= base_url('account/toggle_status/' . $_user->user_id) ?>" onclick="return confirm('Apakah Anda yakin ingin MENGAKTIFKAN kembali akun ini?');" title="Aktifkan Akun" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-play"></span></a>
                <?php endif; ?>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?= $pagination ?>
  </div>
<?php endif; ?>

<?php if(isset($bitauth) && $bitauth->is_admin()): ?>
  <div style="margin-top: 15px;">
    <a href="<?= base_url('account/signup') ?>" class="btn btn-primary hidden-print">+ Tambah Pengguna Baru</a>
  </div>
<?php endif; ?>