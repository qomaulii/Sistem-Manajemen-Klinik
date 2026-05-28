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

        <div class="panel panel-warning">
            <div class="panel-heading">Form Penjadwalan X-Ray</div>
            <div class="panel-body">
                <?= form_open('doctor/save_xray_request') ?>
                    <input type="hidden" name="visit_id" value="1"> 
                    
                    <div class="form-group">
                        <label>Pilih Pasien:</label>
                        <select name="patient_id" class="form-control" required>
                            <?php foreach ($patients as $p) : ?>
                                <option value="<?= $p->user_id ?>"><?= esc($p->first_name . ' ' . $p->last_name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Bagian Tubuh yang Diperiksa:</label>
                        <input type="text" name="body_part" class="form-control" placeholder="Contoh: Thorax, Ekstremitas" required>
                    </div>
                    <div class="form-group">
                        <label>Catatan Dokter:</label>
                        <textarea name="doctor_notes" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning">Kirim ke Radiografer</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>