<legend><?= "- " . esc(@$title) ?></legend>

<?php if(!empty($patients)): ?>
    <div>
        <?= $pagination ?>
        <div class='table-responsive'>
            <table class='table table-bordered table-striped' style="font-size: 14px;">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Name</th>
                        <th>Father Name</th>
                        <th>Phone</th>
                        <th style="width: 60px;">Age</th>
                        <th style="width: 40px;">G</th>
                        <th class='hidden-print' style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $start = ($page-1) * $per_page;
                    $i=0;
                    foreach ($patients as $_patient): 
                        if($i >= (int)$start && $i < (int)$start+(int)$per_page):
                    ?>
                    <tr id="<?= $_patient->patient_id ?>" title="<?= esc($_patient->memo) ?>">
                        <td><?= $_patient->patient_id ?></td>
                        <td><?= esc($_patient->first_name.' '.$_patient->last_name) ?></td>
                        <td><?= esc($_patient->fname) ?></td>
                        <td><?= esc($_patient->phone) ?></td>
                        <td><?= isset($_patient->birth_date) ? (date('Y') - date('Y', $_patient->birth_date)) : '' ?></td>
                        <td><?= $_patient->gender ? 'M' : 'F' ?></td>
                        <td class="hidden-print text-center">
                            <a href="<?= base_url('patient/panel/'.$_patient->patient_id) ?>" class="btn btn-xs btn-default" title="Control Panel"><span class="glyphicon glyphicon-cog"></span></a>
                            <?php if(isset($bitauth) && $bitauth->has_role('receptionist')): ?>
                                <a href="<?= base_url('patient/edit_patient/'.$_patient->patient_id) ?>" class="btn btn-xs btn-info" title="Edit Patient"><span class="glyphicon glyphicon-edit"></span></a>
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
<?php endif; ?>

<div class="hidden-print" style="margin-top: 15px;">
    <a href="<?= base_url('patient/register') ?>" class="btn btn-success">Register Patient</a>
</div>