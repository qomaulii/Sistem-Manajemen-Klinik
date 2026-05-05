<div>
  <div class="modal fade bs-modal-lg" id="modalDrugSearch" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="margin-top: 50px;">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #337ab7; color: white;">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white;">&times;</button>
          <h4 class="modal-title">Search Drug Database</h4>
        </div>
        <div class="modal-body" style="padding: 20px;">
          <?= form_open('', ['id' => 'formDrugQ']) ?>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                <input type="text" name="q" id="drugQ" class="form-control" placeholder="Type drug name..." required autofocus style="height: 45px; font-size: 16px;">
            </div>
          <?= form_close() ?>
          <div id="drugResult" style="min-height: 200px;"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function(){
      $('#modalDrugSearch').modal('show');
      $('#drugQ').on('keyup', function(){
          if($(this).val().length > 1){
              $.post("<?= base_url('drug/search') ?>", $('#formDrugQ').serialize(), function(data){
                  $('#drugResult').html(data);
              });
          }
      });
    });
  </script>
</div>