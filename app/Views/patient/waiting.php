<legend><?= "- " . esc(@$title) ?></legend>

<?php if(!empty($waitings)): ?>
  <div class='table-responsive'>
    <table class='table table-bordered table-striped' style="font-size: 14px;">
      <thead>
        <tr>
          <th style="width: 50px;">ID</th>
          <th>Name</th>
          <th>Father Name</th>
          <th>Phone</th>
          <th>Age</th>
          <th>G</th>
          <th>Assigned Doctor</th>
          <th class='hidden-print' style="width: 100px;"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($waitings as $waiting): ?>
        <tr>
          <td><?= $waiting->patient_id ?></td>
          <td><?= esc($waiting->first_name.' '.$waiting->last_name) ?></td>
          <td><?= esc($waiting->fname) ?></td>
          <td><?= esc($waiting->phone) ?></td>
          <td><?= (date('Y') - date('Y', $waiting->birth_date)) ?></td>
          <td><?= $waiting->gender ? 'M' : 'F' ?></td>
          <td><?= esc($waiting->doc_first_name.' '.$waiting->doc_last_name) ?></td>
          <td class="hidden-print text-center">
              <a href="<?= base_url('patient/panel/'.$waiting->patient_id) ?>" class="btn btn-xs btn-default" title="Panel"><span class="glyphicon glyphicon-cog"></span></a>
              <a href="<?= base_url('patient/edit_patient/'.$waiting->patient_id) ?>" class="btn btn-xs btn-info" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>