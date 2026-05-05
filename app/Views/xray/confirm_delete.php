<div>
  <div class="modal fade" id="modalConfirmDelete<?= $xray->xray_id ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 400px; margin-top: 100px;">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #f5f5f5;">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Delete <?= esc($xray->xray_name_en) ?></h4>
        </div>
        <div class="modal-body" style="padding: 20px;">
          Are you sure you want to delete <strong><?= esc($xray->xray_name_en) ?></strong>?
        </div>
        <div class="modal-footer">
          <?= form_open('xray/delete/' . $xray->xray_id) ?>
            <?= form_hidden('xray_id', $xray->xray_id) ?>
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
      $('#modalConfirmDelete<?= $xray->xray_id ?>').modal('show');
      $('#modalConfirmDelete<?= $xray->xray_id ?> form').on('submit', function(e){
          e.preventDefault();
          $.post($(this).attr('action'), $(this).serialize(), function(data){
              if(data == 'ok'){
                  $('#xray<?= $xray->xray_id ?>').remove();
                  alert('X-ray deleted successfully.');
              } else if(data == 'nok'){
                  alert('Cannot delete: X-ray is already assigned to a patient.');
              }
              $('#modalConfirmDelete<?= $xray->xray_id ?>').modal('hide');
          });
      });
    });
  </script>
</div>