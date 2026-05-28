<div class="row">
    <div class="col-md-8">
        
        <?php if (session()->getFlashdata('message')) : ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Berhasil!</strong> <?= session()->getFlashdata('message') ?>
            </div>
        <?php endif; ?>

        <div class="panel panel-primary">
            <div class="panel-heading">Form Resep Obat</div>
            <div class="panel-body">
                <?= form_open('doctor/save_prescription') ?>
                    <div class="form-group">
                        <label>Pilih Pasien:</label>
                        <select name="patient_id" class="form-control" required>
                            <?php foreach ($patients as $p) : ?>
                                <option value="<?= $p->user_id ?>"><?= esc($p->first_name . ' ' . $p->last_name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Obat:</label>
                        <input type="text" name="drug_name" class="form-control" placeholder="Masukkan nama obat" required>
                    </div>
                    <div class="form-group">
                        <label>Dosis/Instruksi:</label>
                        <input type="text" name="dosage" class="form-control" placeholder="Contoh: 3x1 setelah makan" required>
                    </div>
                    <button type="submit" class="btn btn-success">Kirim ke Apotek</button>
                <?= form_close() ?>
            </div>
        </div>
        
    </div>
</div>