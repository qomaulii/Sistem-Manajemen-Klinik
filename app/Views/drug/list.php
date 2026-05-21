<legend><?= "- " . esc(@$title) ?></legend>

<?php if($drugs): ?>
<div>

    <?= $pagination ?>

    <div class='table-responsive'>
        <table id='drug_list_table' class='table table-bordered table-striped' style="font-size: 14px;">

            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th>Name (EN)</th>
                    <th>Name (FA)</th>
                    <th style="width: 100px;">Price</th>
                    <th>Memo</th>
                    <th class="hidden-print" style="width: 120px;"></th>
                </tr>
            </thead>

            <tbody>

                <?php 
                $start = ($page - 1) * $per_page;
                $i = 0;

                foreach($drugs as $_drug): 

                    if($i >= (int)$start && $i < (int)$start + (int)$per_page):
                ?>

                <tr id="drug<?= $_drug['drug_id'] ?>">

                    <td><?= $_drug['drug_id'] ?></td>

                    <td>
                        <?= esc($_drug['drug_name_en']) ?>
                    </td>

                    <td>
                        <?= esc($_drug['drug_name_fa']) ?>
                    </td>

                    <td>
                        <?= number_format($_drug['price'], 0) ?>
                    </td>

                    <td>
                        <?= esc(mb_strimwidth($_drug['memo'], 0, 50, "...")) ?>
                    </td>

                    <td class="hidden-print text-center">

                        <?php if(isset($bitauth) && $bitauth->has_role('pharmacy')): ?>

                            <a href="<?= base_url('drug/edit/' . $_drug['drug_id']) ?>" 
                               class="btn btn-xs btn-default" 
                               title="Edit Drug">

                                <span class="glyphicon glyphicon-edit"></span>

                            </a>

                            <a href="<?= base_url('drug/delete/' . $_drug['drug_id']) ?>" 
                               class="btn btn-xs btn-danger btn-delete" 
                               title="Delete Drug">

                                <span class="glyphicon glyphicon-remove"></span>

                            </a>

                            <a href="<?= base_url('drug/check/' . $_drug['drug_id']) ?>" 
                               class="btn btn-xs btn-info btn-check" 
                               title="Check Availability">

                                <span class="glyphicon glyphicon-check"></span>

                            </a>

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

    <?= $pagination ?>

</div>

<script>
$(document).ready(function(){ 

    $('.btn-delete, .btn-check').on('click', function(e){

        e.preventDefault();

        $.get($(this).attr('href'), function(data){

            $('#tmpDiv').html(data);

        });

    });

});
</script>

<?php endif; ?>

<div class="hidden-print" style="margin-top: 15px;">

    <a href="<?= base_url('drug/new_drug') ?>" 
       class="btn btn-primary">

       Register New Drug

    </a>

</div>