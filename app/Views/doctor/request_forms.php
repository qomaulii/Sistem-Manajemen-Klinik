<div class="panel panel-info">
    <div class="panel-heading">Buat Resep Obat Baru</div>
    <div class="panel-body">
        <?= form_open('doctor/save_prescription') ?>
            <div class="form-group">
                <label>Pasien:</label>
                <select name="patient_id" class="form-control" required>
                    <?php foreach ($my_patients as $p) : ?>
                        <option value="<?= $p->user_id ?>"><?= esc($p->first_name . ' ' . $p->last_name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Pilih Obat & Instruksi:</label>
                <input type="text" name="drug_name" class="form-control" placeholder="Nama Obat" required>
                <input type="text" name="dosage" class="form-control" placeholder="Dosis (Contoh: 3x1)" required>
            </div>
            <button type="submit" class="btn btn-primary">Kirim ke Apotek</button>
        <?= form_close() ?>
    </div>
</div>