<div class="panel panel-info" style="border: 1px solid #31708f; border-radius: 5px;">
    <div class="panel-heading" style="background-color: #d9edf7; color: #31708f; padding: 10px 15px;">
        <h3 class="panel-title" style="margin: 0; font-size: 18px;">
            <span class="glyphicon glyphicon-usd"></span> Kelola Pembayaran & Tagihan Pasien
        </h3>
    </div>
    <div class="panel-body" style="padding: 20px;">
        <div class="alert alert-warning">
            <strong>Informasi:</strong> Modul Kalkulasi Biaya dan Klaim Asuransi (BPJS) sedang dalam tahap pengembangan.
        </div>
        
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No. Antrean</th>
                    <th>Nama Pasien</th>
                    <th>Tindakan / Layanan</th>
                    <th>Status Tagihan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="text-center text-muted"><em>Belum ada data tagihan hari ini.</em></td>
                </tr>
            </tbody>
        </table>
        
    <a href="<?= base_url('billing/create') ?>" class="btn btn-success">
        <span class="glyphicon glyphicon-plus"></span> Buat Tagihan Baru
    </a>
</div>