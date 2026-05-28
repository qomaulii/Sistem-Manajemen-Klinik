<div class="row">
    <div class="col-md-8">
        <?php if (session()->getFlashdata('message')) : ?>
            <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
        <?php endif; ?>

        <div class="panel panel-info">
            <div class="panel-heading">Form Penjadwalan Tes Lab</div>
            <div class="panel-body">
                <?= form_open('doctor/save_lab_request') ?>
                    <div class="form-group">
                        <label>Pilih Pasien:</label>
                        <select name="patient_id" class="form-control" required>
                            <?php foreach ($patients as $p) : ?>
                                <option value="<?= $p->user_id ?>"><?= esc($p->first_name . ' ' . $p->last_name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jenis Tes Lab:</label>
                        <input type="text" name="test_name" class="form-control" placeholder="Contoh: Tes Darah, Urinalisis" required>
                    </div>
                    <div class="form-group">
                        <label>Jadwal/Waktu Tes:</label>
                        <input type="datetime-local" name="appointment_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Catatan Dokter:</label>
                        <textarea name="doctor_notes" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim ke Lab</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>