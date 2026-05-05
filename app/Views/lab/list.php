<legend><?= "- " . esc(@$title) ?></legend>

<?php if($tests): ?>
  <div>
    <?= isset($pagination) ? $pagination : '' ?>
    <div class='table-responsive'>
      <table id='lab_list_table' class='table table-bordered table-striped' style="font-size: 14px;">
        <thead>
          <tr>
            <th style="width: 50px;">ID</th>
            <th>Name (EN)</th>
            <th>Nama (FA)</th>
            <th style="width: 120px;">Unit Price</th>
            <th>Memo</th>
            <th class="hidden-print" style="width: 100px;"></th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $start = ($page-1) * $per_page;
          $i=0;
          foreach($tests as $test): 
            if($i >= (int)$start && $i < (int)$start+(int)$per_page):
          ?>
          <tr id="test<?= $test->test_id ?>">
            <td><?= $test->test_id ?></td>
            <td><?= esc($test->test_name_en) ?></td>
            <td><?= esc($test->test_name_fa) ?></td>
            <td><?= number_format($test->price, 0) ?></td>
            <td><?= esc(mb_strimwidth($test->memo, 0, 50, "...")) ?></td>
            <td class="hidden-print text-center">
              <?php if(isset($bitauth) && $bitauth->has_role('lab')): ?>
                <a href="<?= base_url('test/edit/'.$test->test_id) ?>" class="btn btn-xs btn-default" title="Edit Test"><span class="glyphicon glyphicon-edit"></span></a>
                <a href="<?= base_url('test/delete/'.$test->test_id) ?>" class="btn btn-xs btn-danger btn-delete" title="Delete Test"><span class="glyphicon glyphicon-remove"></span></a>
              <?php endif; ?>
            </td>
          </tr>
          <?php 
            endif;
            $i++;
          endforeach; 
          ?>
        </tbody>
      </table>
    </div>
    <?= isset($pagination) ? $pagination : '' ?>
  </div>

<script>
    $(document).ready(function(){ 
        $('.btn-delete').on('click', function(e){
            e.preventDefault();
            $.get($(this).attr('href'), function(data){
                $('#tmpDiv').html(data);
            });
        });
    });
</script>
<?php endif; ?>

<div class="hidden-print" style="margin-top: 15px;">
    <a href="<?= base_url('test/new_test') ?>" class="btn btn-primary">Register New Test</a>
</div>