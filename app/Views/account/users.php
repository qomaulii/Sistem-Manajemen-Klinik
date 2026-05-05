<legend><?= "- " . esc(@$title) ?></legend>

<?php if(!empty($users)): ?>
  <div>
    <?= $pagination ?>
    <div class='table-responsive'>
      <table class='table table-bordered table-striped' style="font-size: 14px;">
        <thead>
          <tr>
            <th>Username</th>
            <th>Name</th>
            <th>Father Name</th>
            <th>Position</th>
            <th>Email</th>
            <th>Phone</th>
            <th class='hidden-print' style="width: 100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $_user) : ?>
          <tr id="<?= $_user->user_id ?>" title="<?= esc($_user->first_name . ' ' . $_user->last_name ?: $_user->username) ?>">
            <td><?= esc($_user->username) ?></td>
            <td><?= esc($_user->first_name . ' ' . $_user->last_name) ?></td>
            <td><?= esc($_user->fname) ?></td>
            <td><?= esc($_user->position) ?></td>
            <td><?= esc($_user->email) ?></td>
            <td><?= esc($_user->phone) ?></td>
            <td class="hidden-print text-center">
              <?php if(isset($bitauth) && $bitauth->is_admin()) : ?>
                <a href="<?= base_url('account/edit_user/' . $_user->user_id) ?>" title="Edit User" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-edit"></span></a>
                
                <?php if(!$_user->active) : ?>
                  <a href="#" onclick="$.ajax('<?= base_url('account/activate/' . $_user->activation_code) ?>').done(function(){window.location.reload();});return false;" title="Activate User" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-play"></span></a>
                <?php else : ?>
                  <a href="#" onclick="$.ajax('<?= base_url('account/deactivate/' . $_user->user_id) ?>').done(function(){window.location.reload();});return false;" title="Suspend User" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-pause"></span></a>
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
    <a href="<?= base_url('account/signup') ?>" class="btn btn-primary hidden-print">Add User</a>
  </div>
<?php endif; ?>