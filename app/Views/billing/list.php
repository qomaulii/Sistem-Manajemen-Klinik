<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">
            <span class="glyphicon glyphicon-usd"></span> Tagihan Pembayaran Pasien
        </h3>
    </div>

    <div class="panel-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No. Antrean</th>
                    <th>Nama Pasien</th>
                    <th>Total Biaya</th>
                    <th>Status Pembayaran</th>
                    <th>Metode</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($billings)) : ?>
                    <?php foreach ($billings as $b) : ?>
                        <?php
                            $namaPasien = trim(($b->first_name ?? '') . ' ' . ($b->last_name ?? ''));
                            $sudahBayar = ($b->payment_status ?? '') === 'Sudah Bayar' || ($b->billing_status ?? '') === 'Paid';
                        ?>

                        <tr>
                            <td>
                                <strong><?= esc($b->queue_number ?? '-') ?></strong>
                            </td>

                            <td>
                                <?= esc($namaPasien ?: '-') ?>
                            </td>

                            <td>
                                <?php if (!empty($b->total_amount)) : ?>
                                    <strong>Rp <?= number_format((float) $b->total_amount, 0, ',', '.') ?></strong>
                                <?php else : ?>
                                    <span class="text-muted">Belum dibuat</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($sudahBayar) : ?>
                                    <span class="label label-success">Sudah Bayar</span>
                                <?php else : ?>
                                    <span class="label label-warning">Belum Bayar</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= esc($b->payment_method ?? '-') ?>
                            </td>

                            <td>
                                <?php if (!empty($b->bill_id)) : ?>
                                    <a href="<?= base_url('billing/receipt/' . $b->bill_id) ?>" class="btn btn-primary btn-sm">
                                        Lihat Struk
                                    </a>
                                <?php else : ?>
                                    <a href="<?= base_url('billing/create') ?>" class="btn btn-success btn-sm">
                                        Buat Tagihan
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Belum ada pasien yang memiliki rincian biaya.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <hr>

        <a href="<?= base_url('billing/create') ?>" class="btn btn-success">
            <span class="glyphicon glyphicon-plus"></span> Buat Tagihan Baru
        </a>
    </div>
</div>