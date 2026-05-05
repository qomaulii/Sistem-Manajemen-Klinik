<legend><?= "- " . esc(@$title) ?></legend>

<?php if (!empty($groups)): ?>
  <div>
    <?= isset($pagination) ? $pagination : '' ?>
    
    <div class="table-responsive">
      <table class="table table-bordered table-striped" style="font-size: 14px;">
        <thead>
          <tr>
            <th style="width: 80px;">GroupID</th>
            <th style="width: 200px;">Name</th>
            <th>Description</th>
            <th class="hidden-print" style="width: 100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $start = ($page - 1) * $per_page;
          for ($i = $start; $i < $start + $per_page; $i++): 
            if (isset($groups[$i])): 
              $_group = $groups[$i];
          ?>
          <tr title="<?= esc($_group->description ?: $_group->name) ?>">
            <td><?= $_group->group_id ?></td>
            <td><?= esc($_group->name) ?></td>
            <td><?= esc(strlen($_group->description) > 100 ? substr($_group->description, 0, 100) . '...' : $_group->description) ?></td>
            <td class="hidden-print text-center">
              <?php if (isset($bitauth) && $bitauth->is_admin()): ?>
                <a href="<?= base_url('account/edit_group/' . $_group->group_id) ?>" title="Edit Group" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-edit"></span></a>
                <a href="#" onclick="$.ajax('<?= base_url('account/remove_group/' . $_group->group_id) ?>').done(function(data){$('#tmpDiv').html(data);});return false;" title="Remove Group" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
              <?php endif; ?>
            </td>
          </tr>
          <?php 
            endif;
          endfor; 
          ?>
        </tbody>
      </table>
    </div>
    
    <?= isset($pagination) ? $pagination : '' ?>
  </div>
<?php endif; ?>

<?php if (isset($bitauth) && $bitauth->is_admin()): ?>
  <div style="margin-top: 15px;">
    <a href="<?= base_url('account/add_group') ?>" class="btn btn-primary hidden-print">Add Group</a>
  </div>
<?php endif; ?>