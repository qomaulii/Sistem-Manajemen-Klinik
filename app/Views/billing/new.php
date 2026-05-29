<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php
    $billingOptions = [];

    foreach ($visits as $v) {
        $namaPasien = trim(($v->patient_first_name ?? '') . ' ' . ($v->patient_last_name ?? ''));
        $namaDokter = trim(($v->doctor_first_name ?? '') . ' ' . ($v->doctor_last_name ?? ''));

        $label = ($v->queue_number ?? '-') . ' - ' . $namaPasien;

        $items = [];

        foreach ($v->items as $item) {
            $items[] = [
                'item_type' => $item->item_type,
                'item_name' => $item->item_name,
                'price'     => (float) $item->price,
                'qty'       => (int) $item->qty,
                'subtotal'  => (float) $item->subtotal,
                'note'      => $item->note ?? ''
            ];
        }

        $billingOptions[] = [
            'label'        => $label,
            'visit_id'     => $v->visit_id,
            'patient_id'   => $v->patient_id,
            'patient_name' => $namaPasien,
            'doctor_name'  => $namaDokter,
            'queue_number' => $v->queue_number,
            'total_amount' => (float) $v->total_amount,
            'items'        => $items
        ];
    }
?>

<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">
            <span class="glyphicon glyphicon-plus"></span> Form Pembuatan Tagihan
        </h3>
    </div>

    <div class="panel-body">
        <?= form_open('billing/save', ['id' => 'formBilling']) ?>
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Pilih Pasien / No. Antrean</label>

                <input type="text"
                       id="search_pasien"
                       class="form-control"
                       list="list_pasien"
                       placeholder="Cari nomor antrean atau nama pasien..."
                       autocomplete="off"
                       required>

                <input type="hidden" name="visit_id" id="visit_id">
                <input type="hidden" name="patient_id" id="patient_id">

                <datalist id="list_pasien">
                    <?php foreach ($billingOptions as $p) : ?>
                        <option value="<?= esc($p['label']) ?>"></option>
                    <?php endforeach; ?>
                </datalist>

                <small class="text-muted">
                    Pasien yang muncul hanya pasien dari antrean yang sudah memiliki rincian biaya dari dokter.
                </small>
            </div>

            <div id="info_pasien" class="alert alert-info" style="display:none;">
                <strong>Nama Pasien:</strong> <span id="nama_pasien">-</span><br>
                <strong>No. Antrean:</strong> <span id="no_antrean">-</span><br>
                <strong>Dokter:</strong> <span id="nama_dokter">-</span>
            </div>

            <hr>

            <h4>Rincian Biaya Otomatis</h4>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Jenis</th>
                        <th>Nama Item</th>
                        <th width="120">Harga</th>
                        <th width="80">Jumlah</th>
                        <th width="130">Subtotal</th>
                        <th>Catatan</th>
                    </tr>
                </thead>

                <tbody id="tbody_items">
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Pilih pasien terlebih dahulu.
                        </td>
                    </tr>
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total Biaya</th>
                        <th colspan="2" id="total_biaya">Rp 0</th>
                    </tr>
                </tfoot>
            </table>

            <div class="form-group">
                <label>Metode Pembayaran</label>
                <select class="form-control" name="payment_method" required>
                    <option value="Cash">Tunai / Cash</option>
                    <option value="Transfer">Transfer Bank</option>
                    <option value="BPJS">BPJS</option>
                    <option value="Insurance">Asuransi</option>
                </select>
            </div>

            <hr>

            <button type="submit" class="btn btn-primary">
                Konfirmasi Tagihan & Buat Struk
            </button>

            <a href="<?= base_url('billing') ?>" class="btn btn-default">
                Batal
            </a>
        <?= form_close() ?>
    </div>
</div>

<script>
    const billingOptions = <?= json_encode($billingOptions, JSON_UNESCAPED_UNICODE) ?>;

    function rupiah(number) {
        number = Number(number || 0);

        return 'Rp ' + number.toLocaleString('id-ID');
    }

    function findPatient(label) {
        return billingOptions.find(function(item) {
            return item.label.toLowerCase() === label.toLowerCase();
        });
    }

    document.getElementById('search_pasien').addEventListener('input', function () {
        const selected = findPatient(this.value.trim());

        const visitIdInput = document.getElementById('visit_id');
        const patientIdInput = document.getElementById('patient_id');
        const tbody = document.getElementById('tbody_items');

        if (!selected) {
            visitIdInput.value = '';
            patientIdInput.value = '';

            document.getElementById('info_pasien').style.display = 'none';
            document.getElementById('total_biaya').innerText = 'Rp 0';

            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Pilih pasien terlebih dahulu.
                    </td>
                </tr>
            `;

            return;
        }

        visitIdInput.value = selected.visit_id;
        patientIdInput.value = selected.patient_id;

        document.getElementById('info_pasien').style.display = 'block';
        document.getElementById('nama_pasien').innerText = selected.patient_name || '-';
        document.getElementById('no_antrean').innerText = selected.queue_number || '-';
        document.getElementById('nama_dokter').innerText = selected.doctor_name || '-';

        let rows = '';

        selected.items.forEach(function(item) {
            rows += `
                <tr>
                    <td>${item.item_type}</td>
                    <td>${item.item_name}</td>
                    <td>${rupiah(item.price)}</td>
                    <td>${item.qty}</td>
                    <td><strong>${rupiah(item.subtotal)}</strong></td>
                    <td>${item.note || '-'}</td>
                </tr>
            `;
        });

        tbody.innerHTML = rows;
        document.getElementById('total_biaya').innerText = rupiah(selected.total_amount);
    });

    document.getElementById('formBilling').addEventListener('submit', function(e) {
        if (document.getElementById('visit_id').value === '') {
            e.preventDefault();
            alert('Pilih pasien terlebih dahulu.');
        }
    });
</script>