<?php
    $namaPasien = trim(($request->patient_first_name ?? '') . ' ' . ($request->patient_last_name ?? ''));
    $namaDokter = trim(($request->doctor_first_name ?? '') . ' ' . ($request->doctor_last_name ?? ''));
?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('message') ?>
    </div>
<?php endif; ?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <?= esc($title) ?>
    </div>

    <div class="panel-body">
        <p>
            <strong>No. Antrean:</strong> <?= esc($request->queue_number ?? '-') ?><br>
            <strong>Nama Pasien:</strong> <?= esc($namaPasien ?: '-') ?><br>
            <strong>Dokter:</strong> <?= esc($namaDokter ?: '-') ?><br>
            <strong>Pemeriksaan X-Ray:</strong> <?= esc($request->xray_name ?: '-') ?><br>
            <strong>Catatan Dokter:</strong> <?= esc($request->doctor_notes ?: '-') ?>
        </p>

        <hr>

        <?= form_open_multipart($formAction) ?>
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Hasil X-Ray:</label>
                <textarea 
                    name="result_note" 
                    class="form-control" 
                    rows="5" 
                    required
                    placeholder="Masukkan hasil pemeriksaan x-ray/radiologi..."
                ><?= old('result_note', $request->result_note ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>Bukti Hasil / Keterangan Tambahan:</label>
                <textarea 
                    name="proof_note" 
                    class="form-control" 
                    rows="3"
                    placeholder="Contoh: nomor dokumen, keterangan file, atau catatan tambahan hasil x-ray."
                ><?= old('proof_note', $request->proof_note ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>Upload Bukti Hasil X-Ray:</label>
                <input 
                    type="file" 
                    name="proof_file" 
                    class="form-control"
                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                >

                <small class="text-muted">
                    Format yang boleh: JPG, JPEG, PNG, PDF, DOC, DOCX. Maksimal 5 MB.
                </small>

                <?php if (!empty($request->proof_file)) : ?>
                    <br><br>
                    <a href="<?= base_url($request->proof_file) ?>" target="_blank" class="btn btn-info btn-xs">
                        Lihat File Bukti Sebelumnya
                    </a>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">
                <?= esc($buttonText) ?>
            </button>

            <a href="<?= base_url('xray/queue') ?>" class="btn btn-default">
                Kembali
            </a>
        <?= form_close() ?>
    </div>
</div>