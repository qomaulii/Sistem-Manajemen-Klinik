<?php if($xrays): ?>
  <div class='table-responsive'>
    <table class="table table-bordered table-striped" style="font-size: 13px;">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name (EN)</th>
          <th>Nama (FA)</th>
          <th>Price</th>
          <th style="width: 80px;">QTY</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($xrays as $xray): ?>
        <tr>
          <td><?= $xray->xray_id ?></td>
          <td><?= esc($xray->xray_name_en) ?></td>
          <td><?= esc($xray->xray_name_fa) ?></td>
          <td class="price-val"><?= $xray->price ?></td>
          <td><input type="number" name="no_of_item" value="1" class="form-control input-sm" style="width: 60px;"></td>
          <td><a href="#" class="btn btn-xs btn-primary btn-assign">Assign</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <script>
    $(document).ready(function(){
      $('.btn-assign').on('click', function(e){
        e.preventDefault();
        var tr = $(this).closest('tr');
        
        $('#xray_id').val(tr.find('td:first').text());
        var qty = tr.find('input[name="no_of_item"]').val();
        $('#xray_no_of_item').val(qty);
        $('#xray_total_cost').val(tr.find('.price-val').text() * qty);
        
        $.post($('#addXrayForm').attr('action'), $('#addXrayForm').serialize(), function(data){
            if(data != ''){
                alert('X-ray assigned to patient successfully.');
                // Logika refresh di panel rekam medis
            }
        });
      });
    });
  </script>
<?php else: ?>
  <div class="alert alert-warning text-center">X-ray Not Found</div>
<?php endif; ?>