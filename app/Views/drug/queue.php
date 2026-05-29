<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="panel panel-warning">
    <div class="panel-heading">
        Daftar Antrean Pasien dan Obat yang Diresepkan
    </div>

    <div class="panel-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No. Antrean</th>
                    <th>Nama Pasien</th>
                    <th>Status</th>
                    <th>List Obat yang Diresepkan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($queues)) : ?>
                    <?php foreach ($queues as $q) : ?>
                        <?php
                            $namaPasien = trim(($q->patient_first_name ?? '') . ' ' . ($q->patient_last_name ?? ''));
                            $status = ((int) $q->pending_total > 0) ? 'Menunggu' : 'Selesai';
                        ?>
                        <tr>
                            <td><strong><?= esc($q->queue_number ?? '-') ?></strong></td>
                            <td><?= esc($namaPasien ?: '-') ?></td>
                            <td>
                                <?= form_open('drug/update_prescription_status/' . $q->visit_id) ?>
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="Menunggu" <?= $status === 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                        <option value="Selesai" <?= $status === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                    </select>
                                <?= form_close() ?>
                            </td>
                            <td><?= esc($q->medicine_list ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada resep obat dari dokter.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>