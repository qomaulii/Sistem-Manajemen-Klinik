<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('message') ?>
    </div>
<?php endif; ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Daftar Pasien per Dokter</h3>
    </div>

    <div class="panel-body">
        <?php if (!empty($doctors)) : ?>
            <?php foreach ($doctors as $d) : ?>
                <?php
                    $namaDokter = trim($d->first_name . ' ' . $d->last_name);
                    $patients = $patientsByDoctor[$d->user_id] ?? [];
                ?>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong><?= esc($namaDokter) ?></strong>
                        <span class="pull-right">
                            Spesialis: <?= esc($d->position ?? '-') ?>
                        </span>
                    </div>

                    <div class="panel-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No. Antrean</th>
                                    <th>Nama Pasien</th>
                                    <th>Status Pelayanan</th>
                                    <th>Status Pembayaran</th>
                                    <th>Waktu Daftar</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (!empty($patients)) : ?>
                                    <?php foreach ($patients as $p) : ?>
                                        <?php
                                            $namaPasien = trim(($p->patient_first_name ?? '') . ' ' . ($p->patient_last_name ?? ''));
                                        ?>
                                        <tr>
                                            <td><?= esc($p->queue_number) ?></td>
                                            <td><?= esc($namaPasien ?: '-') ?></td>
                                            <td>
                                                <span class="label label-default">
                                                    <?= esc($p->status) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($p->payment_status)) : ?>
                                                    <span class="label label-info">
                                                        <?= esc($p->payment_status) ?>
                                                    </span>
                                                <?php else : ?>
                                                    <span class="text-muted">Belum Diproses</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('d-m-Y H:i', $p->register_time) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            Belum ada pasien yang didaftarkan ke dokter ini.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="alert alert-warning">
                Data dokter belum tersedia.
            </div>
        <?php endif; ?>
    </div>
</div>