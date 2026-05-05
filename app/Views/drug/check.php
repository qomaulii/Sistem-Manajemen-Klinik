<div>
  <div class="modal fade" id="modalCheckDrug" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 400px; margin-top: 100px;">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #f5f5f5;">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Stock Availability Check</h4>
        </div>
        <div class="modal-body" style="padding: 20px; font-size: 14px; line-height: 1.8;">
          Total available in stock: <strong><?= $count ?></strong><br/>
          <hr style="margin: 10px 0;">
          Purchased: <?= $all_drugs_count ?><br/>
          Returned: <?= $returned_drugs_count ?><br/>
          Sold: <?= $sold_drugs_count ?><br/>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function(){
      $('#modalCheckDrug').modal('show');
    });
  </script>
</div>