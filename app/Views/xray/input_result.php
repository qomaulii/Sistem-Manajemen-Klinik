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


<?php if (empty($request)) : ?>

    <?php
        $xrayOptions = [];

        if (!empty($requests)) {
            foreach ($requests as $r) {
                $namaPasien = trim(($r->patient_first_name ?? '') . ' ' . ($r->patient_last_name ?? ''));
                $namaDokter = trim(($r->doctor_first_name ?? '') . ' ' . ($r->doctor_last_name ?? ''));

                $label = ($r->queue_number ?? '-') .
                    ' - ' . $namaPasien .
                    ' - ' . ($r->xray_name ?? '-') .
                    ' - Dr. ' . $namaDokter;

                $xrayOptions[] = [
                    'request_id'   => $r->request_id,
                    'label'        => $label,
                    'queue_number' => $r->queue_number ?? '-',
                    'patient_name' => $namaPasien,
                    'doctor_name'  => $namaDokter,
                    'xray_name'    => $r->xray_name ?? '-',
                    'doctor_notes' => $r->doctor_notes ?? '-'
                ];
            }
        }
    ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            Input Hasil X-Ray
        </div>

        <div class="panel-body">
            <p class="text-muted">
                Cari pasien berdasarkan nomor antrean, nama pasien, dokter, atau jenis pemeriksaan X-Ray.
            </p>

            <div class="form-group">
                <label>Pilih Pasien / Pemeriksaan X-Ray</label>

                <input 
                    type="text"
                    id="search_xray"
                    class="form-control"
                    list="list_xray"
                    placeholder="Ketik nama pasien, nomor antrean, atau jenis x-ray..."
                    autocomplete="off"
                >

                <datalist id="list_xray">
                    <?php foreach ($xrayOptions as $x) : ?>
                        <option value="<?= esc($x['label']) ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>

            <div id="info_xray" class="alert alert-info" style="display:none;">
                <strong>No. Antrean:</strong> <span id="info_queue">-</span><br>
                <strong>Nama Pasien:</strong> <span id="info_patient">-</span><br>
                <strong>Dokter:</strong> <span id="info_doctor">-</span><br>
                <strong>Pemeriksaan X-Ray:</strong> <span id="info_xray_name">-</span><br>
                <strong>Catatan Dokter:</strong> <span id="info_note">-</span>
            </div>

            <button type="button" id="btnLanjut" class="btn btn-primary">
                Lanjut Input Hasil
            </button>

            <a href="<?= base_url('xray/queue') ?>" class="btn btn-default">
                Lihat Daftar Antrean
            </a>
        </div>
    </div>

    <script>
        const xrayOptions = <?= json_encode($xrayOptions, JSON_UNESCAPED_UNICODE) ?>;
        let selectedRequestId = null;

        function findXray(label) {
            return xrayOptions.find(function(item) {
                return item.label.toLowerCase() === label.toLowerCase();
            });
        }

        document.getElementById('search_xray').addEventListener('input', function () {
            const selected = findXray(this.value.trim());

            if (!selected) {
                selectedRequestId = null;
                document.getElementById('info_xray').style.display = 'none';
                return;
            }

            selectedRequestId = selected.request_id;

            document.getElementById('info_xray').style.display = 'block';
            document.getElementById('info_queue').innerText = selected.queue_number || '-';
            document.getElementById('info_patient').innerText = selected.patient_name || '-';
            document.getElementById('info_doctor').innerText = selected.doctor_name ? 'Dr. ' + selected.doctor_name : '-';
            document.getElementById('info_xray_name').innerText = selected.xray_name || '-';
            document.getElementById('info_note').innerText = selected.doctor_notes || '-';
        });

        document.getElementById('btnLanjut').addEventListener('click', function () {
            if (!selectedRequestId) {
                alert('Pilih pasien / pemeriksaan X-Ray terlebih dahulu.');
                return;
            }

            window.location.href = "<?= base_url('xray/input_result') ?>/" + selectedRequestId;
        });
    </script>


<?php else : ?>

    <?php
        $namaPasien = trim(($request->patient_first_name ?? '') . ' ' . ($request->patient_last_name ?? ''));
        $namaDokter = trim(($request->doctor_first_name ?? '') . ' ' . ($request->doctor_last_name ?? ''));
    ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <?= esc($title) ?>
        </div>

        <div class="panel-body">
            <p>
                <strong>No. Antrean:</strong> <?= esc($request->queue_number ?? '-') ?><br>
                <strong>Nama Pasien:</strong> <?= esc($namaPasien ?: '-') ?><br>
                <strong>Dokter:</strong> <?= esc($namaDokter ? 'Dr. ' . $namaDokter : '-') ?><br>
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
                    Kembali ke Antrean
                </a>

                <a href="<?= base_url('xray/input_result') ?>" class="btn btn-info">
                    Pilih Pasien Lain
                </a>
            <?= form_close() ?>
        </div>
    </div>

<?php endif; ?>