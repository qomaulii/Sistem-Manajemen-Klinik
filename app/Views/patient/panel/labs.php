<div class="tab-pane" id="labs">
  <script>
    $(document).ready(function(){
      $('#addTest').click(function(e){
        e.preventDefault();
        $.ajax($(this).attr('href')).done(function(data){ $('#tmpDiv').html(data); });
      });
      $('#labGroup .actions a').on('click', testItemsAction);
    });

    function testItemsAction(e){
        e.preventDefault();
        var dpi = $(this).attr('dpi');
        var tc = $(this).attr('tc') * 1;
        var action = $(this).attr('action');

        if(action == 'pay' || action == 'delete'){
            var url = (action == 'pay') ? '<?= base_url("test/payment") ?>/' : '<?= base_url("test/deletedpi") ?>/';
            $.post(url + dpi, { lab_patient_id: dpi, test_id: $(this).attr('di'), patient_id: $(this).attr('pi') }, function(data){
                if(data == 'ok') location.reload();
                else alert(data);
            });
        }
    }
  </script>

  <?php if((session()->get('ba_user_id') == $doctor->user_id || isset($bitauth) && $bitauth->has_role('lab')) && $status_code > 1): ?>
    <a href="<?= base_url('test/search') ?>" id="addTest" class="btn btn-danger" style="margin-bottom: 15px;">Assign a Test</a>
    <div class="hidden">
        <?= form_open('test/assign', ['id' => 'addTestForm']) ?>
            <input type="hidden" name="test_id" id="test_id">
            <input type="hidden" name="patient_id" value="<?= $patient->patient_id ?>">
            <input type="hidden" name="no_of_item" id="test_no_of_item">
            <input type="hidden" name="total_cost" id="test_total_cost">
        <?= form_close() ?>
    </div>
  <?php endif; ?>

  <div id="labGroup" class="table-responsive">
    <table class="table table-bordered table-striped" style="font-size: 13px;">
      <thead><tr style="background: #fcfcfc;"><th>#</th><th>Test Name</th><th>Price</th><th>QTY</th><th>Total</th><th class="hidden-print">Action</th></tr></thead>
      <tbody>
        <?php if(@$lab): $i=0; $unpaid=0; foreach($lab as $test): ?>
          <tr>
            <td><?= ++$i ?></td>
            <td><?= esc($test->test_name_en) ?></td>
            <td><?= number_format($test->price, 0) ?></td>
            <td><?= $test->no_of_item ?></td>
            <td><?= number_format($test->total_cost, 0) ?></td>
            <td class="actions">
              <?php if(!($test->user_id_discharge && $test->discharge_date)): $unpaid += $test->total_cost; ?>
                <a href="#" dpi="<?= $test->lab_patient_id ?>" di="<?= $test->test_id ?>" pi="<?= $test->patient_id ?>" action="delete" tc="<?= $test->total_cost ?>" class="btn btn-xs btn-danger">Del</a>
                <?php if(isset($bitauth) && $bitauth->has_role('receptionist')): ?>
                  <a href="#" dpi="<?= $test->lab_patient_id ?>" di="<?= $test->test_id ?>" pi="<?= $test->patient_id ?>" action="pay" tc="<?= $test->total_cost ?>" class="btn btn-xs btn-success">Pay</a>
                <?php endif; ?>
              <?php else: ?><span class="label label-success">Paid</span><?php endif; ?>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>