<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">Profil Pasien</h3></div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Nama:</strong> <?= esc($patient->first_name . ' ' . $patient->last_name) ?></p>
                <p><strong>NIK:</strong> <?= esc($patient->nik) ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Telepon:</strong> <?= esc($patient->phone) ?></p>
                <p><strong>Alamat:</strong> <?= esc($patient->address) ?></p>
            </div>
        </div>
    </div>
</div>

<h4><span class="glyphicon glyphicon-list-alt"></span> Riwayat Pemeriksaan</h4>
<hr>

<?php if (!empty($history)) : ?>
    <?php foreach ($history as $index => $h) : ?>
        <div class="media" style="border: 1px solid #ddd; padding: 15px; border-radius: 5px; margin-bottom: 10px;">
            <div class="media-left">
                <span class="glyphicon glyphicon-heart" style="font-size: 24px; color: #e74c3c;"></span>
            </div>
            <div class="media-body">
                <h4 class="media-heading">
                    Kunjungan #<?= $index + 1 ?> 
                    <small style="float:right;"><?= date('d M Y', $h->created_at) ?></small>
                </h4>
                <p><strong>Dokter:</strong> Dr. <?= esc($h->doc_first . ' ' . $h->doc_last) ?></p>
                <p><strong>Gejala:</strong> <?= esc($h->symptoms) ?></p>
                <p><strong>Diagnosis:</strong> <?= esc($h->diagnosis) ?></p>
                <p><strong>Catatan/Tindakan:</strong> <?= esc($h->medical_action) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Tidak ada riwayat medis ditemukan untuk pasien ini.</p>
<?php endif; ?>

<a href="<?= base_url('doctor/medical_history') ?>" class="btn btn-default">Kembali ke Daftar Pasien</a>