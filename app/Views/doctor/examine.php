<div class="row">
    <div class="col-md-12">
        <div style="margin-bottom: 24px; border-bottom: 2px solid #e5e5e5; padding-bottom: 10px;">
            <h3 style="margin: 0; font-size: 22px; color: #2c3e50; font-weight: bold;">
                <span class="glyphicon glyphicon-stethoscope" style="margin-right: 10px; color: #3498db;"></span>
                <?= esc(@$title) ?>
            </h3>
            <p style="color: #666666; font-size: 14px; margin-top: 5px;">
                Nomor Antrean: <strong><?= str_pad($visit->queue_number, 3, '0', STR_PAD_LEFT) ?></strong> | 
                NIK: <strong><?= esc($patient->nik) ?></strong>
            </p>
        </div>
    </div>

    <div class="col-md-7">
        <div style="background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #f0f0f0;">
            <h4 style="font-size: 16px; font-weight: bold; color: #2c3e50; border-bottom: 1px dashed #cccccc; padding-bottom: 10px; margin-bottom: 20px;">
                Catatan Medis Hari Ini
            </h4>

            <?php if (isset($error) || session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger" style="font-size: 13px; border-radius: 4px;">
                    <?= $error ?? session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?= form_open(current_url(), ['id' => 'examineForm']) ?>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="font-size: 13px; color: #555555;">Keluhan / Gejala (Symptoms) <span style="color:red">*</span></label>
                    <textarea name="symptoms" class="form-control" rows="3" required placeholder="Keluhan yang dirasakan pasien..."><?= set_value('symptoms') ?></textarea>
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="font-size: 13px; color: #555555;">Diagnosis Penyakit <span style="color:red">*</span></label>
                    <textarea name="diagnosis" class="form-control" rows="3" required placeholder="Hasil diagnosis Anda..."><?= set_value('diagnosis') ?></textarea>
                </div>
                
                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="font-size: 13px; color: #555555;">Tindakan Medis / Catatan Khusus (Opsional)</label>
                    <textarea name="medical_action" class="form-control" rows="2" placeholder="Tindakan medis yang diberikan atau saran istirahat..."><?= set_value('medical_action') ?></textarea>
                </div>
                
                <div class="form-group text-right">
                    <a href="<?= base_url('doctor/queue') ?>" class="btn btn-default" style="font-weight: bold; height: 40px; line-height: 26px;">Kembali</a>
                    <button type="submit" class="btn btn-primary" style="font-weight: bold; height: 40px; padding: 0 20px;" onclick="return confirm('Apakah Anda yakin ingin menyimpan rekam medis ini? Status antrean pasien akan otomatis menjadi Selesai.');">
                        Simpan & Akhiri Pemeriksaan
                    </button>
                </div>
            <?= form_close() ?>
        </div>
    </div>

    <div class="col-md-5">
        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e9ecef;">
            <h4 style="font-size: 16px; font-weight: bold; color: #2c3e50; border-bottom: 1px dashed #cccccc; padding-bottom: 10px; margin-bottom: 20px;">
                Riwayat Medis Terdahulu
            </h4>
            
            <div style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
                <?php if (!empty($history)) : ?>
                    <?php foreach ($history as $h) : ?>
                        <div style="background-color: #ffffff; padding: 15px; border-radius: 6px; margin-bottom: 15px; border-left: 4px solid #3498db; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <p style="font-size: 12px; color: #888888; margin-bottom: 5px;">
                                <span class="glyphicon glyphicon-calendar"></span> <?= date('d M Y - H:i', $h->created_at) ?> | 
                                <span class="glyphicon glyphicon-user"></span> Dr. <?= esc($h->last_name) ?>
                            </p>
                            <p style="font-size: 13px; margin-bottom: 5px;"><strong>Gejala:</strong> <?= esc($h->symptoms) ?></p>
                            <p style="font-size: 13px; margin-bottom: 5px;"><strong>Diagnosis:</strong> <span style="color: #d35400; font-weight: bold;"><?= esc($h->diagnosis) ?></span></p>
                            <?php if (!empty($h->medical_action)) : ?>
                                <p style="font-size: 13px; margin-bottom: 0;"><strong>Tindakan:</strong> <?= esc($h->medical_action) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p style="font-size: 13px; color: #888888; text-align: center; margin-top: 20px;">
                        Belum ada riwayat rekam medis untuk pasien ini.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>