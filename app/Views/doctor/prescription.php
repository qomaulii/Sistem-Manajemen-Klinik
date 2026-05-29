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

    $drugOptions = [];
    foreach ($drugs as $d) {
        $drugOptions[] = [
            'id' => $d->item_id,
            'label' => $d->item_name
        ];
    }
?>

<div class="panel panel-primary">
    <div class="panel-heading">Form Resep Obat</div>

    <div class="panel-body">
        <?= form_open('doctor/save_prescription', ['id' => 'formResep']) ?>
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
                    <label>Cari Obat:</label>
                    <input type="text" id="search_obat" class="form-control" list="list_obat" placeholder="Ketik nama obat..." autocomplete="off">

                    <datalist id="list_obat">
                        <?php foreach ($drugOptions as $d) : ?>
                            <option value="<?= esc($d['label']) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>

                <div class="col-md-2">
                    <label>Jumlah:</label>
                    <input type="number" id="qty_obat" class="form-control" value="1" min="1">
                </div>

                <div class="col-md-4">
                    <label>Dosis / Instruksi:</label>
                    <input type="text" id="note_obat" class="form-control" placeholder="Contoh: 3x1 setelah makan">
                </div>

                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <button type="button" id="btn_add_obat" class="btn btn-success btn-block">+</button>
                </div>
            </div>

            <br>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama Obat</th>
                        <th width="100">Jumlah</th>
                        <th>Dosis / Instruksi</th>
                    </tr>
                </thead>
                <tbody id="tbody_obat">
                    <tr class="empty-row">
                        <td colspan="3" class="text-center text-muted">Belum ada obat.</td>
                    </tr>
                </tbody>
            </table>

            <button type="submit" class="btn btn-success">Kirim ke Apotek</button>
        <?= form_close() ?>
    </div>
</div>

<script>
    const patientOptions = <?= json_encode($patientOptions, JSON_UNESCAPED_UNICODE) ?>;
    const drugOptions = <?= json_encode($drugOptions, JSON_UNESCAPED_UNICODE) ?>;

    function findOption(options, label) {
        return options.find(item => item.label.toLowerCase() === label.toLowerCase());
    }

    document.getElementById('search_pasien').addEventListener('input', function () {
        const selected = findOption(patientOptions, this.value.trim());

        document.getElementById('patient_id').value = selected ? selected.patient_id : '';
        document.getElementById('visit_id').value = selected ? selected.visit_id : '';
    });

    document.getElementById('btn_add_obat').addEventListener('click', function () {
        const input = document.getElementById('search_obat');
        const qtyInput = document.getElementById('qty_obat');
        const noteInput = document.getElementById('note_obat');
        const tbody = document.getElementById('tbody_obat');

        const selected = findOption(drugOptions, input.value.trim());

        if (!selected) {
            alert('Pilih obat dari daftar yang muncul.');
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

    document.getElementById('formResep').addEventListener('submit', function (e) {
        if (document.getElementById('patient_id').value === '') {
            e.preventDefault();
            alert('Pilih pasien terlebih dahulu.');
            return;
        }

        if (document.querySelectorAll('input[name="item_ids[]"]').length === 0) {
            e.preventDefault();
            alert('Tambahkan minimal satu obat.');
            return;
        }
    });
</script>