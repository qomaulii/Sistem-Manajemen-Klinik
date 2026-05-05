<div>
  <div class="modal fade" id="modalXrayDetails" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="margin-top: 50px;">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #31708f; color: white;">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white;">&times;</button>
          <h4 class="modal-title">X-ray Diagnostic Details</h4>
        </div>
        <div class="modal-body" style="padding: 20px;">
          <div class="row">
            <div class="col col-md-12">
              <?= form_open_multipart('xray/details/'.$xray_patient_id, ["id" => "xrayDetailsForm", "role" => "form"]) ?>
                <fieldset style="margin-bottom: 20px;">
                  <?= !empty($error) ? '<div class="alert alert-danger">'.$error.'</div>' : '' ?>
                  <input type="hidden" name="xray_patient_id" value="<?= $xray_patient_id ?>" />
                  <legend>- Memo & Upload Result:</legend>
                  <div class="form-group" style="margin-bottom: 15px;">
                    <textarea name="memo" id="memo" class="form-control" rows="5" style="height: 120px; margin-bottom: 15px;" placeholder="Add clinical notes here..."><?= set_value('memo') ?></textarea>
                    <input type="file" name="picture" class="form-control" style="height: auto; padding: 10px;" required />
                  </div>
                </fieldset>
                <div class="text-right">
                  <input type="submit" value='Add Record' class="btn btn-info" style="width: 150px;" />
                </div>
              <?= form_close() ?>
            </div>
          </div>
          
          <hr style="margin: 25px 0;">
          
          <div class="row">
            <?php if(isset($xray_files) && count($xray_files) > 0): ?>
                <?php foreach ($xray_files as $file): ?>
                <div class="col col-md-6" style="margin-bottom: 20px;">
                    <div class="thumbnail" style="padding: 10px;">
                        <img src="<?= base_url($file->path) ?>" class="img-responsive" style="max-height: 300px; margin-bottom: 10px;" />
                        <div style="background: #f9f9f9; padding: 10px; font-size: 13px; border-top: 1px solid #eee;">
                            <?= esc($file->memo) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function(){
      $('#modalXrayDetails').modal('show');
      $('#modalXrayDetails form').on('submit', function(e){
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
              url : $(this).attr('action'),
              type : 'POST',
              data : formData,
              processData: false,
              contentType: false,
              success : function(data) {
                $('#tmpDiv').html(data);
              }
        });
      });
    });
  </script>
</div>