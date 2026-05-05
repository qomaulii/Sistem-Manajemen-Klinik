<div>
  <div class="modal fade" id="modalReportBug" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 500px; margin-top: 50px;">
      <div class="modal-content">
        <?= form_open('report_bug/add', ['id' => 'reportBugForm']) ?>
        <div class="modal-header" style="background-color: #f5f5f5;">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Report a Bug</h4>
        </div>
        <div class="modal-body" style="padding: 20px;">
            <div style="margin-bottom: 10px;">
                <input type="text" name='subject' value="<?= set_value('subject') ?>" class='form-control' placeholder='Subject' required autofocus />
            </div>
            <div style="margin-bottom: 10px;">
                <input type="text" name='url' value="<?= set_value('url') ?>" class='form-control' placeholder='URL' />
            </div>
            <div>
                <textarea name="description" placeholder="Description" class="form-control" rows="8" style="height: 150px;"><?= set_value('description') ?></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          <input type="submit" value="Report" class="btn btn-success" />
        </div>
        <?= form_close() ?>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function(){
      $('#modalReportBug').modal('show');
      $('#reportBugForm').on('submit', function(e){
         e.preventDefault();
         $.post($(this).attr('action'), $(this).serialize(), function(){
             alert('You have successfully reported a bug.');
             $('#modalReportBug').modal('hide');
         });
      });
    });
  </script>
</div>