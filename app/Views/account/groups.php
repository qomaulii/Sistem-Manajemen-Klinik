<legend style="border-bottom: 2px solid #e5e5e5; padding-bottom: 10px; margin-bottom: 20px; font-size: 20px; color: #333333;">- <?= esc(@$title) ?></legend>

<?php if (session()->getFlashdata('success')) : ?>
    <script>
        window.onload = function() {
            alert("<?= session()->getFlashdata('success') ?>");
        };
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger" style="margin-bottom: 15px; font-size: 14px;">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (!empty($groups)): ?>
  <div class="table-responsive" style="background-color: #ffffff; padding: 15px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <table class="table table-bordered table-hover" style="font-size: 14px; margin-bottom: 0;">
      <thead>
        <tr style="background-color: #f8f9fa;">
          <th style="width: 80px; text-align: center;">ID Grup</th>
          <th style="width: 200px;">Nama Grup</th>
          <th>Deskripsi Hak Akses</th>
          <th class="hidden-print text-center" style="width: 140px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($groups as $_group) : ?>
        <tr title="<?= esc($_group->description ?: $_group->name) ?>">
          <td style="text-align: center; vertical-align: middle;"><?= $_group->group_id ?></td>
          <td style="vertical-align: middle; font-weight: bold; color: #007bff;"><?= esc($_group->name) ?></td>
          <td style="vertical-align: middle;"><?= esc(strlen($_group->description) > 100 ? substr($_group->description, 0, 100) . '...' : $_group->description) ?></td>
          <td class="hidden-print text-center" style="vertical-align: middle;">
            <?php if (isset($bitauth) && $bitauth->is_admin()): ?>
              <a href="<?= base_url('account/edit_group/' . $_group->group_id) ?>" title="Edit Data Grup" class="btn btn-xs btn-info" style="margin-right: 4px; padding: 4px 10px; font-size: 12px;">
                <span class="glyphicon glyphicon-edit"></span> Edit
              </a>
              
              <a href="<?= base_url('account/delete_group/' . $_group->group_id) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus grup ini? Pengguna di dalamnya akan kehilangan akses khusus mereka.');" title="Hapus Grup" class="btn btn-xs btn-danger" style="padding: 4px 10px; font-size: 12px;">
                <span class="glyphicon glyphicon-remove"></span> Hapus
              </a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php if (isset($bitauth) && $bitauth->is_admin()): ?>
  <div style="margin-top: 20px;">
    <a href="<?= base_url('account/add_group') ?>" class="btn btn-primary hidden-print" style="padding: 8px 16px; font-size: 14px;">
      + Buat Grup Baru
    </a>
  </div>
<?php endif; ?>