<div>
  <div class="modal fade" id="modalConfirmDelete<?= $test->test_id ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 400px; margin-top: 100px;">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #f5f5f5;">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Delete <?= esc($test->test_name_en) ?></h4>
        </div>
        <div class="modal-body" style="padding: 20px;">
          You want to delete <strong><?= esc($test->test_name_en) ?></strong>.<br/>Are you sure?
        </div>
        <div class="modal-footer">
          <?= form_open('test/delete/'.$test->test_id) ?>
            <?= form_hidden('test_id', $test->test_id) ?>
            <?= form_hidden('del', 1) ?>
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <input type="submit" class="btn btn-primary" value="YES" />
          <?= form_close() ?>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function(){
      $('#modalConfirmDelete<?= $test->test_id ?>').modal('show');
      $('#modalConfirmDelete<?= $test->test_id ?> form').on('submit', function(e){
          e.preventDefault();
          $.post($(this).attr('action'), $(this).serialize(), function(data){
              if(data == 'ok'){
                  $('#test<?= $test->test_id ?>').remove();
                  alert('Test has been deleted successfully.');
              } else if(data == 'nok'){
                  alert('Test is already assigned to a patient and cannot be deleted.');
              } else {
                  alert('Error: ' + data);
              }
              $('#modalConfirmDelete<?= $test->test_id ?>').modal('hide');
          });
      });
    });
  </script>
</div>