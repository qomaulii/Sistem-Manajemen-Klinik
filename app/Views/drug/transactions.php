<div class="panel panel-info">
    <div class="panel-heading">
        Transaksi Pembelian Obat Pasien
    </div>

    <div class="panel-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No. Antrean</th>
                    <th>Nama Pasien</th>
                    <th>Dokter yang Meresepkan</th>
                    <th>Obat yang Diresepkan</th>
                    <th>Total Obat</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($transactions)) : ?>
                    <?php foreach ($transactions as $t) : ?>
                        <?php
                            $namaPasien = trim(($t->patient_first_name ?? '') . ' ' . ($t->patient_last_name ?? ''));
                            $namaDokter = trim(($t->doctor_first_name ?? '') . ' ' . ($t->doctor_last_name ?? ''));
                        ?>
                        <tr>
                            <td><strong><?= esc($t->queue_number ?? '-') ?></strong></td>
                            <td><?= esc($namaPasien ?: '-') ?></td>
                            <td>Dr. <?= esc($namaDokter ?: '-') ?></td>
                            <td><?= esc($t->medicine_list ?? '-') ?></td>
                            <td><strong>Rp <?= number_format((float) $t->total_obat, 0, ',', '.') ?></strong></td>
                            <td><?= !empty($t->tanggal) ? date('d M Y H:i', $t->tanggal) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada transaksi obat.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>