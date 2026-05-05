<div class="tab-pane" id="drugs">
  <script>
    $(document).ready(function(){
      $('#addDrug').click(function(e){
        e.preventDefault();
        $.ajax($(this).attr('href')).done(function(data){
          $('#tmpDiv').html(data);
        });
      });
      $('#drugGroup .actions a').on('click', drugItemsAction);
    });

    function drugItemsAction(e){
        e.preventDefault();
        var dpi = $(this).attr('dpi');
        var di = $(this).attr('di');
        var pi = $(this).attr('pi');
        var tc = $(this).attr('tc') * 1;
        var action = $(this).attr('action');

        if(action == 'pay' || action == 'delete'){
            var url = (action == 'pay') ? '<?= base_url("drug/payment") ?>/' : '<?= base_url("drug/deletedpi") ?>/';
            $.post(url + dpi, {
                drug_patient_id: dpi,
                drug_id: di,
                patient_id: pi
            }, function(data){
                if(data == 'ok'){
                    if(action == 'pay'){
                        $('#dpi'+dpi+' .actions').html('<span class="text-success">Paid</span>');
                        location.reload(); // Lebih aman di CI4 untuk update totalan
                    } else {
                        $('#dpi'+dpi).remove();
                    }
                } else {
                    alert('Error: ' + data);
                }
            });
        }
    }
  </script>

  <?php if((session()->get('ba_user_id') == $doctor->user_id || isset($bitauth) && $bitauth->has_role('pharmacy')) && $status_code > 1): ?>
    <a href="<?= base_url('drug/search') ?>" id="addDrug" class="btn btn-primary" style="margin-bottom: 15px;">Assign a Drug</a>
    
    <div class="hidden">
        <?= form_open('drug/assign', ['id' => 'addDrugForm']) ?>
            <input type="hidden" name="drug_id" id="drug_id">
            <input type="hidden" name="patient_id" value="<?= $patient->patient_id ?>">
            <input type="hidden" name="no_of_item" id="no_of_item">
            <input type="hidden" name="total_cost" id="total_cost">
            <input type="hidden" name="memo" id="memo">
        <?= form_close() ?>
    </div>
  <?php endif; ?>

  <div id="drugGroup" class="table-responsive">
    <table class="table table-bordered table-striped" style="font-size: 13px;">
      <thead>
        <tr style="background: #f9f9f9;">
          <th>#</th><th>Name</th><th>Unit Price</th><th>QTY</th><th>Total</th><th class="hidden-print">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if(@$drugs): $i=0; $paid=0; $unpaid=0; foreach($drugs as $drug): ?>
          <tr id="dpi<?= $drug->drug_patient_id ?>">
            <td><?= ++$i ?></td>
            <td><?= esc($drug->drug_name_en) ?></td>
            <td><?= number_format($drug->price, 0) ?></td>
            <td><?= $drug->no_of_item ?></td>
            <td><?= number_format($drug->total_cost, 0) ?></td>
            <td class="actions">
              <?php if(!($drug->user_id_discharge && $drug->discharge_date)): ?>
                <?php $unpaid += $drug->total_cost; ?>
                <a href="#" dpi="<?= $drug->drug_patient_id ?>" di="<?= $drug->drug_id ?>" pi="<?= $drug->patient_id ?>" action="delete" tc="<?= $drug->total_cost ?>" class="btn btn-xs btn-danger">Del</a>
                <?php if(isset($bitauth) && $bitauth->has_role('receptionist')): ?>
                  <a href="#" dpi="<?= $drug->drug_patient_id ?>" di="<?= $drug->drug_id ?>" pi="<?= $drug->patient_id ?>" action="pay" tc="<?= $drug->total_cost ?>" class="btn btn-xs btn-success">Pay</a>
                <?php endif; ?>
              <?php else: $paid += $drug->total_cost; ?>
                <span class="label label-success">Paid</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
          <tr class="text-right">
              <td colspan="4"><strong>Total Unpaid:</strong></td>
              <td colspan="2" class="text-danger"><strong><?= number_format($unpaid, 0) ?></strong></td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>