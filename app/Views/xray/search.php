<div>
  <div class="modal fade bs-modal-lg" id="modalXraySearch" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="margin-top: 50px;">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #5bc0de; color: white;">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white;">&times;</button>
          <h4 class="modal-title">Search X-ray Database</h4>
        </div>
        <div class="modal-body" style="padding: 20px;">
          <?= form_open('', ['id' => 'formXrayQ']) ?>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                <input type="text" name="q" id="xrayQ" class="form-control" placeholder="Search by name..." required autofocus style="height: 45px; font-size: 16px;">
            </div>
          <?= form_close() ?>
          <div id="xrayResult" style="min-height: 200px;"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function(){
      $('#modalXraySearch').modal('show');
      $('#xrayQ').on('keyup', function(){
          if($(this).val().length > 1){
              $.post("<?= base_url('xray/search') ?>", $('#formXrayQ').serialize(), function(data){
                  $('#xrayResult').html(data);
              });
          }
      });
    });
  </script>
</div>