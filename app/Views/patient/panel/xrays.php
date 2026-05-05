<div class="tab-pane" id="xrays">
  <script>
    $(document).ready(function(){
      $('#addXray').click(function(e){
        e.preventDefault();
        $.ajax($(this).attr('href')).done(function(data){ $('#tmpDiv').html(data); });
      });
      $('#xrayGroup .actions a').on('click', xrayItemsAction);
    });

    function xrayItemsAction(e){
        e.preventDefault();
        var action = $(this).attr('action');
        if(action == 'pay' || action == 'delete'){
            $.post('<?= base_url("xray") ?>/' + action + '/' + $(this).attr('dpi'), { xray_patient_id: $(this).attr('dpi'), xray_id: $(this).attr('di'), patient_id: $(this).attr('pi') }, function(data){
                if(data == 'ok') location.reload();
            });
        } else if(action == 'details'){
            $.get($(this).attr('href'), function(data){ $('#tmpDiv').html(data); });
        }
    }
  </script>

  <?php if((session()->get('ba_user_id') == $doctor->user_id || isset($bitauth) && $bitauth->has_role('xray')) && $status_code > 1): ?>
    <a href="<?= base_url('xray/search') ?>" id="addXray" class="btn btn-info" style="margin-bottom: 15px;">Assign an X-Ray</a>
    <div class="hidden">
        <?= form_open('xray/assign', ['id' => 'addXrayForm']) ?>
            <input type="hidden" name="xray_id" id="xray_id">
            <input type="hidden" name="patient_id" value="<?= $patient->patient_id ?>">
            <input type="hidden" name="no_of_item" id="xray_no_of_item">
            <input type="hidden" name="total_cost" id="xray_total_cost">
        <?= form_close() ?>
    </div>
  <?php endif; ?>

  <div id="xrayGroup" class="table-responsive">
    <table class="table table-bordered table-striped" style="font-size: 13px;">
      <thead><tr style="background: #fcfcfc;"><th>#</th><th>X-Ray Name</th><th>Price</th><th>QTY</th><th>Total</th><th class="hidden-print">Action</th></tr></thead>
      <tbody>
        <?php if(@$xrays): $i=0; foreach($xrays as $xray): ?>
          <tr>
            <td><?= ++$i ?></td>
            <td><?= esc($xray->xray_name_en) ?></td>
            <td><?= number_format($xray->price, 0) ?></td>
            <td><?= $xray->no_of_item ?></td>
            <td><?= number_format($xray->total_cost, 0) ?></td>
            <td class="actions">
              <?php if(!($xray->user_id_discharge && $xray->discharge_date)): ?>
                <a href="#" dpi="<?= $xray->xray_patient_id ?>" di="<?= $xray->xray_id ?>" pi="<?= $xray->patient_id ?>" action="delete" class="btn btn-xs btn-danger">Del</a>
                <?php if(isset($bitauth) && $bitauth->has_role('receptionist')): ?>
                  <a href="#" dpi="<?= $xray->xray_patient_id ?>" di="<?= $xray->xray_id ?>" pi="<?= $xray->patient_id ?>" action="pay" class="btn btn-xs btn-success">Pay</a>
                <?php endif; ?>
              <?php else: ?>
                <a href="<?= base_url('xray/details/'.$xray->xray_patient_id) ?>" action="details" class="btn btn-xs btn-info">View Results</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>