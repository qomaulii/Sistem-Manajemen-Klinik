<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('message') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Daftar Antrean Harian</h3>
    </div>

    <div class="panel-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No. Antrean</th>
                    <th>Nama Pasien</th>
                    <th>Status Pelayanan</th>
                    <th>Status Pembayaran</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($queues)) : ?>
                    <?php foreach ($queues as $q) : ?>
                        <?php
                            $namaPasien = trim(($q->first_name ?? '') . ' ' . ($q->last_name ?? ''));

                            $statusPelayanan = $q->status ?: 'Menunggu';
                            $statusPembayaran = $q->payment_status ?: 'Belum Bayar';

                            $statusOptions = ['Menunggu', 'Telah Diurus', 'Selesai'];
                            $paymentOptions = ['Belum Bayar', 'Sudah Bayar'];
                        ?>

                        <tr>
                            <td>
                                <strong><?= esc($q->queue_number) ?></strong>
                            </td>

                            <td>
                                <?= esc($namaPasien ?: '-') ?>
                            </td>

                            <td>
                                <?= form_open('receptionist/update_status/' . $q->visit_id) ?>
                                    <?= csrf_field() ?>

                                    <select name="status"
                                            class="form-control input-sm"
                                            style="width: 150px; display: inline-block; font-weight: bold;"
                                            onchange="this.form.submit()">
                                        <?php foreach ($statusOptions as $status) : ?>
                                            <option value="<?= esc($status) ?>"
                                                <?= ($statusPelayanan === $status) ? 'selected' : '' ?>>
                                                <?= esc($status) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?= form_close() ?>
                            </td>

                            <td>
                                <?= form_open('receptionist/update_payment_status/' . $q->visit_id) ?>
                                    <?= csrf_field() ?>

                                    <select name="payment_status"
                                            class="form-control input-sm"
                                            style="width: 150px; display: inline-block; font-weight: bold;"
                                            onchange="this.form.submit()">
                                        <?php foreach ($paymentOptions as $payment) : ?>
                                            <option value="<?= esc($payment) ?>"
                                                <?= ($statusPembayaran === $payment) ? 'selected' : '' ?>>
                                                <?= esc($payment) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?= form_close() ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            Belum ada antrean hari ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>