<?= form_open('doctor/save_prescription') ?>
    <div class="form-group">
        <label>Pilih Pasien:</label>
        <select name="patient_id" class="form-control" required>
            <?php foreach ($my_patients as $p) : ?>
                <option value="<?= $p->user_id ?>"><?= esc($p->first_name) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Obat & Dosis:</label>
        <select name="drug_id" class="form-control">
            <?php foreach ($drugs as $d) : ?>
                <option value="<?= $d->drug_id ?>"><?= esc($d->drug_name_en) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="dosage" class="form-control" placeholder="Contoh: 3x1 setelah makan">
    </div>
    <button type="submit" class="btn btn-success">Kirim ke Apotek</button>
<?= form_close() ?>