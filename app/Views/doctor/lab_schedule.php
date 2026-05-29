<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('message') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php
    $patientOptions = [];
    foreach ($patients as $p) {
        $nama = trim(($p->first_name ?? '') . ' ' . ($p->last_name ?? ''));
        $label = ($p->queue_number ?? '-') . ' - ' . $nama;

        $patientOptions[] = [
            'label' => $label,
            'patient_id' => $p->patient_id,
            'visit_id' => $p->visit_id
        ];
    }

    $labOptions = [];
    foreach ($labItems as $l) {
        $labOptions[] = [
            'id' => $l->item_id,
            'label' => $l->item_name
        ];
    }
?>

<div class="panel panel-info">
    <div class="panel-heading">Form Penjadwalan Tes Lab</div>

    <div class="panel-body">
        <?= form_open('doctor/save_lab_request', ['id' => 'formLab']) ?>
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Pilih Pasien:</label>
                <input type="text" id="search_pasien" class="form-control" list="list_pasien" placeholder="Cari nomor antrean / nama pasien..." autocomplete="off">
                <input type="hidden" name="patient_id" id="patient_id">
                <input type="hidden" name="visit_id" id="visit_id">

                <datalist id="list_pasien">
                    <?php foreach ($patientOptions as $p) : ?>
                        <option value="<?= esc($p['label']) ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-5">
                    <label>Cari Tes Lab:</label>
                    <input type="text" id="search_lab" class="form-control" list="list_lab" placeholder="Ketik nama tes lab..." autocomplete="off">

                    <datalist id="list_lab">
                        <?php foreach ($labOptions as $l) : ?>
                            <option value="<?= esc($l['label']) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>

                <div class="col-md-2">
                    <label>Jumlah:</label>
                    <input type="number" id="qty_lab" class="form-control" value="1" min="1">
                </div>

                <div class="col-md-4">
                    <label>Catatan Dokter:</label>
                    <input type="text" id="note_lab" class="form-control" placeholder="Contoh: cek darah lengkap">
                </div>

                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <button type="button" id="btn_add_lab" class="btn btn-success btn-block">+</button>
                </div>
            </div>

            <br>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama Tes Lab</th>
                        <th width="100">Jumlah</th>
                        <th>Catatan Dokter</th>
                    </tr>
                </thead>
                <tbody id="tbody_lab">
                    <tr class="empty-row">
                        <td colspan="3" class="text-center text-muted">Belum ada tes lab.</td>
                    </tr>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Kirim ke Lab</button>
        <?= form_close() ?>
    </div>
</div>

<script>
    const patientOptions = <?= json_encode($patientOptions, JSON_UNESCAPED_UNICODE) ?>;
    const labOptions = <?= json_encode($labOptions, JSON_UNESCAPED_UNICODE) ?>;

    function findOption(options, label) {
        return options.find(item => item.label.toLowerCase() === label.toLowerCase());
    }

    document.getElementById('search_pasien').addEventListener('input', function () {
        const selected = findOption(patientOptions, this.value.trim());

        document.getElementById('patient_id').value = selected ? selected.patient_id : '';
        document.getElementById('visit_id').value = selected ? selected.visit_id : '';
    });

    document.getElementById('btn_add_lab').addEventListener('click', function () {
        const input = document.getElementById('search_lab');
        const qtyInput = document.getElementById('qty_lab');
        const noteInput = document.getElementById('note_lab');
        const tbody = document.getElementById('tbody_lab');

        const selected = findOption(labOptions, input.value.trim());

        if (!selected) {
            alert('Pilih tes lab dari daftar yang muncul.');
            return;
        }

        const empty = tbody.querySelector('.empty-row');
        if (empty) empty.remove();

        const qty = qtyInput.value || 1;
        const note = noteInput.value.trim();

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                ${selected.label}
                <input type="hidden" name="item_ids[]" value="${selected.id}">
            </td>
            <td>
                ${qty}
                <input type="hidden" name="qty[]" value="${qty}">
            </td>
            <td>
                ${note}
                <input type="hidden" name="note[]" value="${note}">
            </td>
        `;

        tbody.appendChild(tr);

        input.value = '';
        qtyInput.value = 1;
        noteInput.value = '';
    });

    document.getElementById('formLab').addEventListener('submit', function (e) {
        if (document.getElementById('patient_id').value === '') {
            e.preventDefault();
            alert('Pilih pasien terlebih dahulu.');
            return;
        }

        if (document.querySelectorAll('input[name="item_ids[]"]').length === 0) {
            e.preventDefault();
            alert('Tambahkan minimal satu tes lab.');
            return;
        }
    });
</script>