<div class="panel panel-success" style="border: 1px solid #d6e9c6; border-radius: 5px;">
    <div class="panel-heading" style="background-color: #dff0d8; color: #3c763d; padding: 10px 15px;">
        <h3 class="panel-title" style="margin: 0; font-size: 18px;">
            <span class="glyphicon glyphicon-plus"></span> Form Pembuatan Tagihan
        </h3>
    </div>
    <div class="panel-body" style="padding: 20px;">
        
        <form action="<?= base_url('billing/save') ?>" method="post">
            
            <div class="form-group">
                <label>Nama Pasien / No. Antrean</label>
                <select class="form-control" name="patient_id" required>
                    <option value="">-- Pilih Pasien --</option>
                    <option value="1">A-001 - Budi Santoso</option>
                    <option value="2">A-002 - Siti Aminah</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Layanan / Tindakan (Diagnosis)</label>
                <input type="text" class="form-control" name="service" placeholder="Misal: Konsultasi Dokter Umum & Cek Darah" required>
            </div>
            
            <div class="form-group">
                <label>Total Biaya (Rp)</label>
                <input type="number" class="form-control" name="amount" placeholder="Misal: 150000" required>
            </div>
            
            <div class="form-group">
                <label>Metode Pembayaran</label>
                <select class="form-control" name="payment_method" required>
                    <option value="Cash">Tunai (Cash)</option>
                    <option value="Transfer">Transfer Bank</option>
                    <option value="BPJS">Klaim BPJS</option>
                    <option value="Insurance">Asuransi Swasta</option>
                </select>
            </div>
            
            <hr>
            <button type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-floppy-disk"></span> Simpan Tagihan
            </button>
            <a href="<?= base_url('billing') ?>" class="btn btn-default">Batal</a>
        </form>
        
    </div>
</div>