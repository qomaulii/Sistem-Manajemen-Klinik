<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="panel panel-info">
    <div class="panel-heading">Daftar Antrean Pasien Lab</div>

    <div class="panel-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No. Antrean</th>
                    <th>Nama Pasien</th>
                    <th>Dokter</th>
                    <th>Tes Lab</th>
                    <th>Catatan Dokter</th>
                    <th>Status</th>
                    <th width="130">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($requests)) : ?>
                    <?php foreach ($requests as $r) : ?>
                        <?php
                            $namaPasien = trim(($r->patient_first_name ?? '') . ' ' . ($r->patient_last_name ?? ''));
                            $namaDokter = trim(($r->doctor_first_name ?? '') . ' ' . ($r->doctor_last_name ?? ''));
                        ?>
                        <tr>
                            <td><strong><?= esc($r->queue_number ?? '-') ?></strong></td>
                            <td><?= esc($namaPasien ?: '-') ?></td>
                            <td><?= esc($namaDokter ?: '-') ?></td>
                            <td><?= esc($r->test_name ?: '-') ?></td>
                            <td><?= esc($r->doctor_notes ?: '-') ?></td>
                            <td><span class="label label-warning">Menunggu</span></td>
                            <td>
                                <a href="<?= base_url('test/input_result/' . $r->request_id) ?>" class="btn btn-primary btn-sm">
                                    Input Hasil
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Belum ada pasien yang menunggu hasil lab.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>