<div>
  <div class="modal fade" id="modalConfirmDelete<?= @$id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 400px; margin-top: 100px;"> <!-- Ukuran pixel biar presisi -->
      <div class="modal-content">
        <div class="modal-header" style="background-color: #f5f5f5;">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Delete <?= esc(@$name) ?></h4>
        </div>
        <div class="modal-body" style="padding: 20px; font-size: 14px;">
          You want to delete <strong><?= esc(@$name) ?></strong>.<br/>Are you sure?
        </div>
        <div class="modal-footer">
          <?= form_open(@$url) ?>
            <?= form_hidden('del', 1) ?>
            <button type="button" class="btn btn-default" data-dismiss="modal" style="width: 80px;">No</button>
            <input type="submit" class="btn btn-primary" value="YES" style="width: 80px;" />
          <?= form_close() ?>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function(){
      $('#modalConfirmDelete<?= @$id ?>').modal('show');
    });
  </script>
</div>