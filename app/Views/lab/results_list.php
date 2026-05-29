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

<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">Lihat Daftar Hasil Lab</h3>
    </div>

    <div class="panel-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No. Antrean</th>
                    <th>Nama Pasien</th>
                    <th>Pemeriksaan Lab</th>
                    <th>Hasil</th>
                    <th>Bukti / Keterangan</th>
                    <th>Tanggal Selesai</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($results)) : ?>
                    <?php foreach ($results as $r) : ?>
                        <?php
                            $namaPasien = trim(($r->patient_first_name ?? '') . ' ' . ($r->patient_last_name ?? ''));

                            $proofFile = $r->proof_file ?? '';
                            $ext = '';

                            if (!empty($proofFile)) {
                                $ext = strtolower(pathinfo($proofFile, PATHINFO_EXTENSION));
                            }
                        ?>

                        <tr>
                            <td>
                                <strong><?= esc($r->queue_number ?? '-') ?></strong>
                            </td>

                            <td>
                                <?= esc($namaPasien ?: '-') ?>
                            </td>

                            <td>
                                <?= esc($r->test_name ?? '-') ?>
                            </td>

                            <td>
                                <?= esc($r->result_note ?? '-') ?>
                            </td>

                            <td>
                                <?= esc($r->proof_note ?? '-') ?>

                                <?php if (!empty($proofFile)) : ?>
                                    <br>

                                    <a href="<?= base_url($proofFile) ?>" target="_blank" class="btn btn-info btn-xs">
                                        Lihat Bukti
                                    </a>

                                    <?php if (in_array($ext, ['jpg', 'jpeg', 'png'])) : ?>
                                        <br><br>

                                        <img 
                                            src="<?= base_url($proofFile) ?>" 
                                            alt="Bukti Hasil Lab" 
                                            style="max-width: 120px; border: 1px solid #ddd; padding: 4px;"
                                        >
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= !empty($r->completed_at) ? date('d-m-Y H:i', $r->completed_at) : '-' ?>
                            </td>

                            <td>
                                <a href="<?= base_url('test/edit_result/' . $r->request_id) ?>" class="btn btn-warning btn-xs">
                                    Edit
                                </a>

                                <?= form_open('test/delete_result/' . $r->request_id, [
                                    'style' => 'display:inline;',
                                    'onsubmit' => "return confirm('Yakin ingin menghapus hasil lab ini?')"
                                ]) ?>
                                    <button type="submit" class="btn btn-danger btn-xs">
                                        Hapus
                                    </button>
                                <?= form_close() ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Belum ada hasil lab.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="<?= base_url('test/queue') ?>" class="btn btn-default">
            Kembali ke Antrean Lab
        </a>
    </div>
</div>