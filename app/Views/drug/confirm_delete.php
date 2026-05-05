<div>
  <div class="modal fade" id="modalConfirmDelete<?= $drug->drug_id ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 400px; margin-top: 100px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Delete <?= esc($drug->drug_name_en) ?></h4>
        </div>
        <div class="modal-body" style="padding: 20px;">
          Are you sure you want to delete <strong><?= esc($drug->drug_name_en) ?></strong>?
        </div>
        <div class="modal-footer">
          <?= form_open('drug/delete/' . $drug->drug_id) ?>
            <?= form_hidden('drug_id', $drug->drug_id) ?>
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
      $('#modalConfirmDelete<?= $drug->drug_id ?>').modal('show');
      $('#modalConfirmDelete<?= $drug->drug_id ?> form').on('submit', function(e){
          e.preventDefault();
          $.post($(this).attr('action'), $(this).serialize(), function(data){
              if(data == 'ok'){
                  $('#drug<?= $drug->drug_id ?>').remove();
                  alert('Drug deleted successfully.');
              } else if(data == 'nok'){
                  alert('Cannot delete: Drug is already assigned to a patient.');
              }
              $('#modalConfirmDelete<?= $drug->drug_id ?>').modal('hide');
          });
      });
    });
  </script>
</div>