<?php if($lab): ?>
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
        <?php foreach($lab as $test): ?>
        <tr id="<?= $test->test_id ?>">
          <td><?= $test->test_id ?></td>
          <td><?= esc($test->test_name_en) ?></td>
          <td><?= esc($test->test_name_fa) ?></td>
          <td class="price-val"><?= $test->price ?></td>
          <td><input type="number" name="no_of_item" value="1" class="form-control input-sm" style="width: 60px;"></td>
          <td><a href="#" class="btn btn-xs btn-danger btn-assign">Assign</a></td>
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
        
        // Asumsi ID input form utama ada di halaman panel pasien
        $('#test_id').val(tr.find('td:first').text());
        var qty = tr.find('input[name="no_of_item"]').val();
        $('#test_no_of_item').val(qty);
        $('#test_total_cost').val(tr.find('.price-val').text() * qty);
        
        $.post($('#addTestForm').attr('action'), $('#addTestForm').serialize(), function(data){
          if(data != ''){
            alert('Test assigned to the patient successfully');
            // Logika refresh tabel lab di panel pasien bisa ditambahkan di sini
          }
        });
      });
    });
  </script>
<?php else: ?>
  <div class="alert alert-warning text-center">Test Not Found</div>
<?php endif; ?>