<?php
    $namaPasien = trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? ''));
?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Form Pemeriksaan Pasien</h3>
    </div>

    <div class="panel-body">
        <p>
            <strong>Nama Pasien:</strong> <?= esc($namaPasien) ?><br>
            <strong>No. Antrean:</strong> <?= esc($visit->queue_number ?? '-') ?>
        </p>

        <hr>

        <?= form_open(current_url()) ?>
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Keluhan Pasien</label>
                <textarea name="keluhan" class="form-control" rows="3" required><?= old('keluhan') ?></textarea>
            </div>

            <div class="form-group">
                <label>Diagnosis</label>
                <textarea name="diagnosis" class="form-control" rows="3" required><?= old('diagnosis') ?></textarea>
            </div>

            <div class="form-group">
                <label>Hasil Pemeriksaan</label>
                <textarea name="hasil_pemeriksaan" class="form-control" rows="3"><?= old('hasil_pemeriksaan') ?></textarea>
            </div>

            <div class="form-group">
                <label>Catatan Tindakan</label>
                <textarea name="catatan_tindakan" class="form-control" rows="3"><?= old('catatan_tindakan') ?></textarea>
            </div>

            <hr>

            <h4>Pilih Pemeriksaan / Obat / Lab / X-Ray</h4>

            <?php
                $jenisItem = [
                    'PEMERIKSAAN' => 'Pemeriksaan / Tindakan',
                    'OBAT'        => 'Obat',
                    'LAB'         => 'Tes Laboratorium',
                    'XRAY'        => 'X-Ray / Radiologi'
                ];
            ?>

            <?php foreach ($jenisItem as $kodeJenis => $judulJenis) : ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong><?= esc($judulJenis) ?></strong>
                    </div>

                    <div class="panel-body">
                        <input type="text"
                               class="form-control search-item"
                               placeholder="Cari <?= esc(strtolower($judulJenis)) ?>..."
                               data-target="<?= esc($kodeJenis) ?>"
                               style="margin-bottom: 10px;">

                        <?php if (!empty($groupedItems[$kodeJenis])) : ?>
                            <div class="row">
                                <?php foreach ($groupedItems[$kodeJenis] as $item) : ?>
                                    <div class="col-md-6 item-row item-<?= esc($kodeJenis) ?>" style="margin-bottom: 10px;">
                                        <label>
                                            <input type="checkbox"
                                                   name="item_ids[]"
                                                   value="<?= esc($item->item_id) ?>">
                                            <?= esc($item->item_name) ?>
                                        </label>

                                        <input type="number"
                                               name="qty_<?= esc($item->item_id) ?>"
                                               class="form-control input-sm"
                                               value="1"
                                               min="1"
                                               style="width: 90px;">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <p class="text-muted">
                                Belum ada data <?= esc(strtolower($judulJenis)) ?>.
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <button type="submit" class="btn btn-primary">
                Simpan Pemeriksaan
            </button>

            <a href="<?= base_url('doctor/queue') ?>" class="btn btn-default">
                Kembali
            </a>

        <?= form_close() ?>
    </div>
</div>

<?php if (!empty($history)) : ?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Riwayat Rekam Medis Pasien</h3>
        </div>

        <div class="panel-body">
            <?php foreach ($history as $h) : ?>
                <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                    <strong>Tanggal:</strong>
                    <?= !empty($h->created_at) ? date('d-m-Y H:i', $h->created_at) : '-' ?><br>

                    <strong>Dokter:</strong>
                    <?= esc(trim(($h->first_name ?? '') . ' ' . ($h->last_name ?? ''))) ?><br>

                    <strong>Keluhan:</strong>
                    <?= esc($h->keluhan ?? '-') ?><br>

                    <strong>Diagnosis:</strong>
                    <?= esc($h->diagnosis ?? '-') ?><br>

                    <strong>Hasil Pemeriksaan:</strong>
                    <?= esc($h->hasil_pemeriksaan ?? '-') ?><br>

                    <strong>Catatan Tindakan:</strong>
                    <?= esc($h->catatan_tindakan ?? '-') ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<script>
    document.querySelectorAll('.search-item').forEach(function(input) {
        input.addEventListener('keyup', function() {
            const keyword = this.value.toLowerCase();
            const target = this.getAttribute('data-target');
            const rows = document.querySelectorAll('.item-' + target);

            rows.forEach(function(row) {
                const text = row.innerText.toLowerCase();

                if (text.includes(keyword)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>