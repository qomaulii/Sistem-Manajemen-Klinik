<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">
            <span class="glyphicon glyphicon-folder-open"></span>
            Riwayat & Hasil Pemeriksaan
        </h3>
    </div>

    <div class="panel-body">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#rekam" data-toggle="tab">Rekam Medis</a>
            </li>
            <li>
                <a href="#hasil" data-toggle="tab">Hasil Lab & X-Ray</a>
            </li>
            <li>
                <a href="#obat" data-toggle="tab">Obat</a>
            </li>
            <li>
                <a href="#pembayaran" data-toggle="tab">Pembayaran</a>
            </li>
        </ul>

        <div class="tab-content" style="padding-top: 20px;">

            <!-- TAB 1: REKAM MEDIS -->
            <div class="tab-pane active" id="rekam">
                <?php if (!empty($visits)) : ?>
                    <?php foreach ($visits as $v) : ?>
                        <?php
                            $namaDokter = trim(($v->doctor_first_name ?? '') . ' ' . ($v->doctor_last_name ?? ''));
                        ?>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>No. Antrean: <?= esc($v->queue_number ?? '-') ?></strong>
                                <span class="pull-right">
                                    <?= !empty($v->register_time) ? date('d-m-Y H:i', $v->register_time) : '-' ?>
                                </span>
                            </div>

                            <div class="panel-body">
                                <p>
                                    <strong>Dokter:</strong>
                                    <?= $namaDokter ? 'Dr. ' . esc($namaDokter) : '-' ?><br>

                                    <strong>Status Pelayanan:</strong>
                                    <?= esc($v->status ?? '-') ?><br>

                                    <strong>Status Pembayaran:</strong>
                                    <?= esc($v->payment_status ?? 'Belum Bayar') ?>
                                </p>

                                <hr>

                                <?php if (!empty($v->records)) : ?>
                                    <?php foreach ($v->records as $r) : ?>
                                        <div style="margin-bottom: 15px;">
                                            <p>
                                                <strong>Tanggal Catatan:</strong>
                                                <?= !empty($r->created_at) ? date('d-m-Y H:i', $r->created_at) : '-' ?>
                                            </p>

                                            <p>
                                                <strong>Keluhan:</strong><br>
                                                <?= esc($r->keluhan ?? $r->symptoms ?? '-') ?>
                                            </p>

                                            <p>
                                                <strong>Diagnosis:</strong><br>
                                                <?= esc($r->diagnosis ?? '-') ?>
                                            </p>

                                            <p>
                                                <strong>Hasil Pemeriksaan:</strong><br>
                                                <?= esc($r->hasil_pemeriksaan ?? '-') ?>
                                            </p>

                                            <p>
                                                <strong>Catatan / Tindakan Dokter:</strong><br>
                                                <?= esc($r->catatan_tindakan ?? $r->medical_action ?? '-') ?>
                                            </p>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <p class="text-muted">
                                        Belum ada catatan rekam medis dari dokter untuk kunjungan ini.
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($v->details)) : ?>
                                    <h4>Detail Pemeriksaan / Tindakan</h4>

                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Jenis</th>
                                                <th>Nama Pemeriksaan</th>
                                                <th>Hasil / Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($v->details as $d) : ?>
                                                <tr>
                                                    <td><?= esc($d->item_type ?? '-') ?></td>
                                                    <td><?= esc($d->item_name ?? '-') ?></td>
                                                    <td><?= esc($d->result_note ?? '-') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="alert alert-info">
                        Belum ada riwayat kunjungan.
                    </div>
                <?php endif; ?>
            </div>

            <!-- TAB 2: HASIL LAB & XRAY -->
            <div class="tab-pane" id="hasil">
                <?php if (!empty($visits)) : ?>
                    <?php foreach ($visits as $v) : ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>No. Antrean: <?= esc($v->queue_number ?? '-') ?></strong>
                                <span class="pull-right">
                                    <?= !empty($v->register_time) ? date('d-m-Y H:i', $v->register_time) : '-' ?>
                                </span>
                            </div>

                            <div class="panel-body">
                                <h4>Hasil Laboratorium</h4>

                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama Tes Lab</th>
                                            <th>Catatan Dokter</th>
                                            <th>Status</th>
                                            <th>Hasil</th>
                                            <th>Bukti / Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($v->labs)) : ?>
                                            <?php foreach ($v->labs as $lab) : ?>
                                                <tr>
                                                    <td><?= esc($lab->test_name ?? '-') ?></td>
                                                    <td><?= esc($lab->doctor_notes ?? '-') ?></td>
                                                    <td>
                                                        <?php if (($lab->status ?? '') === 'Selesai') : ?>
                                                            <span class="label label-success">Selesai</span>
                                                        <?php else : ?>
                                                            <span class="label label-warning">Menunggu</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= esc($lab->result_note ?? '-') ?></td>
                                                    <td>
                                                        <?= esc($lab->proof_note ?? '-') ?>

                                                        <?php if (!empty($lab->proof_file)) : ?>
                                                            <?php
                                                                $proofFile = $lab->proof_file;
                                                                $ext = strtolower(pathinfo($proofFile, PATHINFO_EXTENSION));
                                                            ?>

                                                            <br>
                                                            <a href="<?= base_url($proofFile) ?>" target="_blank" class="btn btn-info btn-xs">
                                                                Lihat Bukti Lab
                                                            </a>

                                                            <?php if (in_array($ext, ['jpg', 'jpeg', 'png'])) : ?>
                                                                <br><br>
                                                                <img 
                                                                    src="<?= base_url($proofFile) ?>" 
                                                                    alt="Bukti Hasil Lab" 
                                                                    style="max-width: 180px; border: 1px solid #ddd; padding: 4px;"
                                                                >
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">
                                                    Tidak ada permintaan lab pada kunjungan ini.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <h4>Hasil X-Ray / Radiologi</h4>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama Pemeriksaan X-Ray</th>
                                            <th>Catatan Dokter</th>
                                            <th>Status</th>
                                            <th>Hasil</th>
                                            <th>Bukti / Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($v->xrays)) : ?>
                                            <?php foreach ($v->xrays as $xray) : ?>
                                                <tr>
                                                    <td><?= esc($xray->xray_name ?? '-') ?></td>
                                                    <td><?= esc($xray->doctor_notes ?? '-') ?></td>
                                                    <td>
                                                        <?php if (($xray->status ?? '') === 'Selesai') : ?>
                                                            <span class="label label-success">Selesai</span>
                                                        <?php else : ?>
                                                            <span class="label label-warning">Menunggu</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= esc($xray->result_note ?? '-') ?></td>
                                                    <td>
                                                        <?= esc($xray->proof_note ?? '-') ?>

                                                        <?php if (!empty($xray->proof_file)) : ?>
                                                            <?php
                                                                $proofFile = $xray->proof_file;
                                                                $ext = strtolower(pathinfo($proofFile, PATHINFO_EXTENSION));
                                                            ?>

                                                            <br>
                                                            <a href="<?= base_url($proofFile) ?>" target="_blank" class="btn btn-info btn-xs">
                                                                Lihat Bukti X-Ray
                                                            </a>

                                                            <?php if (in_array($ext, ['jpg', 'jpeg', 'png'])) : ?>
                                                                <br><br>
                                                                <img 
                                                                    src="<?= base_url($proofFile) ?>" 
                                                                    alt="Bukti X-Ray" 
                                                                    style="max-width: 180px; border: 1px solid #ddd; padding: 4px;"
                                                                >
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">
                                                    Tidak ada permintaan x-ray pada kunjungan ini.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="alert alert-info">
                        Belum ada hasil pemeriksaan.
                    </div>
                <?php endif; ?>
            </div>

            <!-- TAB 3: OBAT -->
            <div class="tab-pane" id="obat">
                <?php if (!empty($visits)) : ?>
                    <?php foreach ($visits as $v) : ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>No. Antrean: <?= esc($v->queue_number ?? '-') ?></strong>
                                <span class="pull-right">
                                    <?= !empty($v->register_time) ? date('d-m-Y H:i', $v->register_time) : '-' ?>
                                </span>
                            </div>

                            <div class="panel-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama Obat</th>
                                            <th>Jumlah</th>
                                            <th>Instruksi / Catatan</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($v->medicines)) : ?>
                                            <?php foreach ($v->medicines as $obat) : ?>
                                                <tr>
                                                    <td><?= esc($obat->item_name ?? '-') ?></td>
                                                    <td><?= esc($obat->qty ?? 1) ?></td>
                                                    <td><?= esc($obat->note ?? '-') ?></td>
                                                    <td>
                                                        Rp <?= number_format((float) ($obat->subtotal ?? 0), 0, ',', '.') ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">
                                                    Tidak ada resep obat pada kunjungan ini.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="alert alert-info">
                        Belum ada data obat.
                    </div>
                <?php endif; ?>
            </div>

            <!-- TAB 4: PEMBAYARAN -->
            <div class="tab-pane" id="pembayaran">
                <?php if (!empty($visits)) : ?>
                    <?php foreach ($visits as $v) : ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>No. Antrean: <?= esc($v->queue_number ?? '-') ?></strong>
                                <span class="pull-right">
                                    <?= !empty($v->register_time) ? date('d-m-Y H:i', $v->register_time) : '-' ?>
                                </span>
                            </div>

                            <div class="panel-body">
                                <?php if (!empty($v->billing)) : ?>
                                    <p>
                                        <strong>Status Pembayaran:</strong>
                                        <span class="label label-success">Sudah Bayar</span><br>

                                        <strong>Total Pembayaran:</strong>
                                        Rp <?= number_format((float) ($v->billing->total_amount ?? 0), 0, ',', '.') ?><br>

                                        <strong>Metode Pembayaran:</strong>
                                        <?= esc($v->billing->payment_method ?? '-') ?><br>

                                        <strong>Tanggal Bayar:</strong>
                                        <?= !empty($v->billing->paid_date) ? date('d-m-Y H:i', $v->billing->paid_date) : '-' ?>
                                    </p>

                                    <a href="<?= base_url('billing/receipt/' . $v->billing->bill_id) ?>" class="btn btn-primary btn-sm">
                                        Lihat Struk
                                    </a>
                                <?php else : ?>
                                    <p>
                                        <strong>Status Pembayaran:</strong>
                                        <span class="label label-warning">Belum Bayar</span>
                                    </p>

                                    <p class="text-muted">
                                        Tagihan belum dibuat atau pembayaran belum dikonfirmasi oleh resepsionis.
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="alert alert-info">
                        Belum ada data pembayaran.
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>