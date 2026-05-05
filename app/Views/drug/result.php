<?php if($drugs): ?>
  <div class='table-responsive'>
    <table class="table table-bordered table-striped" style="font-size: 13px;">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name (EN)</th>
          <th>Name (FA)</th>
          <th>Price</th>
          <th style="width: 80px;">QTY</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($drugs as $_drug): ?>
        <tr id="<?= $_drug->drug_id ?>">
          <td><?= $_drug->drug_id ?></td>
          <td><?= esc($_drug->drug_name_en) ?></td>
          <td><?= esc($_drug->drug_name_fa) ?></td>
          <td class="price-val"><?= $_drug->price ?></td>
          <td><input type="number" name="no_of_item" value="1" class="form-control input-sm" style="width: 60px;"></td>
          <td><a href="#" class="btn btn-xs btn-success btn-assign">Assign</a></td>
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
        // Set nilai ke form utama (asumsi ID input sudah sesuai di form pendaftaran pasien)
        $('#drug_id').val(tr.find('td:first').text());
        var qty = tr.find('input[name="no_of_item"]').val();
        $('#no_of_item').val(qty);
        $('#total_cost').val(tr.find('.price-val').text() * qty);
        
        // Kirim data via Ajax
        $.post($('#addDrugForm').attr('action'), $('#addDrugForm').serialize(), function(data){
            if(data != ''){
                // Logika update tabel tagihan pasien (sesuai kode lamamu)
                alert('Drug assigned successfully');
                // Reload atau update UI di sini
            }
        });
      });
    });
  </script>
<?php else: ?>
  <div class="alert alert-warning text-center">Drug Not Found</div>
<?php endif; ?>