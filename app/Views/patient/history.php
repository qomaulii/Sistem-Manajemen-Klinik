<div class="container" style="margin-top:20px;">
    <legend>- Riwayat Medis & Hasil Pemeriksaan</legend>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#rekam" data-toggle="tab">Rekam Medis</a></li>
        <li><a href="#labxray" data-toggle="tab">Hasil Lab & X-Ray</a></li>
        <li><a href="#billing" data-toggle="tab">Pembayaran</a></li>
        <li><a href="#obat" data-toggle="tab">Obat</a></li>
    </ul>

    <div class="tab-content" style="padding: 20px; border: 1px solid #ddd; border-top: none;">
        <div class="tab-pane active" id="rekam">
            <table class="table">
                <thead><tr><th>Tanggal</th><th>Dokter</th><th>Catatan</th></tr></thead>
                <tbody>
                    <?php foreach($visits as $v): ?>
                    <tr><td><?= date('d M Y', $v->register_time) ?></td><td>...</td><td>...</td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>
</div>