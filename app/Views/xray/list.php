<legend><?= "- " . esc(@$title) ?></legend>

<?php if($xrays): ?>
  <div>
    <?= isset($pagination) ? $pagination : '' ?>
    <div class='table-responsive'>
      <table id='xray_list_table' class='table table-bordered table-striped' style="font-size: 14px;">
        <thead>
          <tr>
            <th style="width: 50px;">ID</th>
            <th>Name (EN)</th>
            <th>Nama (FA)</th>
            <th style="width: 120px;">Price</th>
            <th>Memo</th>
            <th class="hidden-print" style="width: 100px;"></th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $start = ($page-1) * $per_page;
          $i=0;
          foreach($xrays as $xray): 
            if($i >= (int)$start && $i < (int)$start+(int)$per_page):
          ?>
          <tr id="xray<?= $xray->xray_id ?>">
            <td><?= $xray->xray_id ?></td>
            <td><?= esc($xray->xray_name_en) ?></td>
            <td><?= esc($xray->xray_name_fa) ?></td>
            <td><?= number_format($xray->price, 0) ?></td>
            <td><?= esc(mb_strimwidth($xray->memo, 0, 50, "...")) ?></td>
            <td class="hidden-print text-center">
                <a href="<?= base_url('xray/edit/'.$xray->xray_id) ?>" class="btn btn-xs btn-default" title="Edit Xray"><span class="glyphicon glyphicon-edit"></span></a>
                <a href="<?= base_url('xray/delete/'.$xray->xray_id) ?>" class="btn btn-xs btn-danger btn-delete" title="Delete Xray"><span class="glyphicon glyphicon-remove"></span></a>
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
    <a href="<?= base_url('xray/new_xray') ?>" class="btn btn-primary">Register New X-ray</a>
</div>