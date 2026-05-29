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
        $labOptions = [];

        if (!empty($requests)) {
            foreach ($requests as $r) {
                $namaPasien = trim(($r->patient_first_name ?? '') . ' ' . ($r->patient_last_name ?? ''));
                $namaDokter = trim(($r->doctor_first_name ?? '') . ' ' . ($r->doctor_last_name ?? ''));

                $label = ($r->queue_number ?? '-') .
                    ' - ' . $namaPasien .
                    ' - ' . ($r->test_name ?? '-') .
                    ' - Dr. ' . $namaDokter;

                $labOptions[] = [
                    'request_id'   => $r->request_id,
                    'label'        => $label,
                    'queue_number' => $r->queue_number ?? '-',
                    'patient_name' => $namaPasien,
                    'doctor_name'  => $namaDokter,
                    'test_name'    => $r->test_name ?? '-',
                    'doctor_notes' => $r->doctor_notes ?? '-',
                    'form_action'  => base_url('test/input_result/' . $r->request_id)
                ];
            }
        }
    ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            Input Hasil Lab
        </div>

        <div class="panel-body">
            <p class="text-muted">
                Cari pasien berdasarkan nomor antrean, nama pasien, dokter, atau jenis pemeriksaan lab.
            </p>

            <div class="form-group">
                <label>Pilih Pasien / Pemeriksaan Lab</label>

                <input 
                    type="text"
                    id="search_lab"
                    class="form-control"
                    list="list_lab"
                    placeholder="Ketik nama pasien, nomor antrean, atau jenis tes lab..."
                    autocomplete="off"
                >

                <datalist id="list_lab">
                    <?php foreach ($labOptions as $x) : ?>
                        <option value="<?= esc($x['label']) ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>

            <div id="info_lab" class="alert alert-info" style="display:none;">
                <strong>No. Antrean:</strong> <span id="info_queue">-</span><br>
                <strong>Nama Pasien:</strong> <span id="info_patient">-</span><br>
                <strong>Dokter:</strong> <span id="info_doctor">-</span><br>
                <strong>Pemeriksaan Lab:</strong> <span id="info_test_name">-</span><br>
                <strong>Catatan Dokter:</strong> <span id="info_note">-</span>
            </div>

            <div id="form_lab_wrapper" style="display:none;">
                <hr>

                <?= form_open_multipart('', ['id' => 'formLabResult']) ?>
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label>Hasil Lab:</label>
                        <textarea 
                            name="result_note" 
                            class="form-control" 
                            rows="5" 
                            required
                            placeholder="Masukkan hasil pemeriksaan laboratorium..."
                        ></textarea>
                    </div>

                    <div class="form-group">
                        <label>Bukti Hasil / Keterangan Tambahan:</label>
                        <textarea 
                            name="proof_note" 
                            class="form-control" 
                            rows="3"
                            placeholder="Contoh: nomor dokumen, keterangan file, atau catatan tambahan hasil lab."
                        ></textarea>
                    </div>

                    <div class="form-group">
                        <label>Upload Bukti Hasil Lab:</label>
                        <input 
                            type="file" 
                            name="proof_file" 
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                        >

                        <small class="text-muted">
                            Format yang boleh: JPG, JPEG, PNG, PDF, DOC, DOCX. Maksimal 5 MB.
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan Hasil Lab
                    </button>

                    <a href="<?= base_url('test/queue') ?>" class="btn btn-default">
                        Lihat Daftar Antrean
                    </a>
                <?= form_close() ?>
            </div>
        </div>
    </div>

    <script>
        const labOptions = <?= json_encode($labOptions, JSON_UNESCAPED_UNICODE) ?>;

        function findLab(label) {
            return labOptions.find(function(item) {
                return item.label.toLowerCase() === label.toLowerCase();
            });
        }

        document.getElementById('search_lab').addEventListener('input', function () {
            const selected = findLab(this.value.trim());

            if (!selected) {
                document.getElementById('info_lab').style.display = 'none';
                document.getElementById('form_lab_wrapper').style.display = 'none';
                document.getElementById('formLabResult').setAttribute('action', '');
                return;
            }

            document.getElementById('info_lab').style.display = 'block';
            document.getElementById('form_lab_wrapper').style.display = 'block';

            document.getElementById('info_queue').innerText = selected.queue_number || '-';
            document.getElementById('info_patient').innerText = selected.patient_name || '-';
            document.getElementById('info_doctor').innerText = selected.doctor_name ? 'Dr. ' + selected.doctor_name : '-';
            document.getElementById('info_test_name').innerText = selected.test_name || '-';
            document.getElementById('info_note').innerText = selected.doctor_notes || '-';

            document.getElementById('formLabResult').setAttribute('action', selected.form_action);
        });

        document.getElementById('formLabResult').addEventListener('submit', function(e) {
            if (!this.getAttribute('action')) {
                e.preventDefault();
                alert('Pilih pasien / pemeriksaan lab terlebih dahulu.');
            }
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
                <strong>Pemeriksaan Lab:</strong> <?= esc($request->test_name ?: '-') ?><br>
                <strong>Catatan Dokter:</strong> <?= esc($request->doctor_notes ?: '-') ?>
            </p>

            <hr>

            <?= form_open_multipart($formAction) ?>
                <?= csrf_field() ?>

                <div class="form-group">
                    <label>Hasil Lab:</label>
                    <textarea 
                        name="result_note" 
                        class="form-control" 
                        rows="5" 
                        required
                        placeholder="Masukkan hasil pemeriksaan laboratorium..."
                    ><?= old('result_note', $request->result_note ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label>Bukti Hasil / Keterangan Tambahan:</label>
                    <textarea 
                        name="proof_note" 
                        class="form-control" 
                        rows="3"
                        placeholder="Contoh: nomor dokumen, keterangan file, atau catatan tambahan hasil lab."
                    ><?= old('proof_note', $request->proof_note ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label>Upload Bukti Hasil Lab:</label>
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

                <a href="<?= base_url('test/queue') ?>" class="btn btn-default">
                    Kembali ke Antrean
                </a>

                <a href="<?= base_url('test/input_result') ?>" class="btn btn-info">
                    Pilih Pasien Lain
                </a>
            <?= form_close() ?>
        </div>
    </div>

<?php endif; ?>