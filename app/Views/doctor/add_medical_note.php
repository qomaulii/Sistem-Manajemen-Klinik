<?php
    $namaPasien = trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? ''));

    $procedureOptions = [];

    foreach ($procedures as $p) {
        $procedureOptions[] = [
            'id' => $p->item_id,
            'name' => $p->item_name,
            'type' => $p->item_type,
            'price' => $p->price ?? 0,
            'label' => $p->item_name . ' - Rp ' . number_format((float) ($p->price ?? 0), 0, ',', '.')
        ];
    }
?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="panel panel-primary">
    <div class="panel-heading">
        Tambah Catatan Medis
    </div>

    <div class="panel-body">
        <p>
            <strong>Nama Pasien:</strong> <?= esc($namaPasien ?: '-') ?><br>
            <strong>No. Antrean:</strong> <?= esc($visit->queue_number ?? '-') ?>
        </p>

        <hr>

        <?= form_open(current_url(), ['id' => 'formCatatanMedis']) ?>
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Keluhan / Gejala</label>
                <textarea name="keluhan" class="form-control" rows="3" required placeholder="Contoh: demam, batuk, nyeri perut, pusing, dll"><?= old('keluhan') ?></textarea>
            </div>

            <div class="form-group">
                <label>Diagnosis</label>
                <textarea name="diagnosis" class="form-control" rows="3" required placeholder="Contoh: infeksi saluran pernapasan atas"><?= old('diagnosis') ?></textarea>
            </div>

            <div class="form-group">
                <label>Hasil Pemeriksaan</label>
                <textarea name="hasil_pemeriksaan" class="form-control" rows="3" placeholder="Contoh: suhu 38°C, tekanan darah normal, tenggorokan hiperemis"><?= old('hasil_pemeriksaan') ?></textarea>
            </div>

            <div class="form-group">
                <label>Catatan / Tindakan</label>
                <textarea name="catatan_tindakan" class="form-control" rows="3" placeholder="Contoh: disarankan istirahat, minum air putih, kontrol ulang jika tidak membaik"><?= old('catatan_tindakan') ?></textarea>
            </div>

            <hr>

            <h4>Tambah Pemeriksaan / Tindakan</h4>
            <p class="text-muted">
                Bagian ini hanya untuk pemeriksaan atau tindakan medis. Obat, lab, dan x-ray tetap lewat menu masing-masing.
            </p>

            <div class="panel panel-default">
                <div class="panel-heading">
                    Pemeriksaan / Tindakan
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-5">
                            <label>Cari Pemeriksaan / Tindakan</label>
                            <input type="text"
                                   id="search_item"
                                   class="form-control"
                                   list="list_item"
                                   placeholder="Ketik nama pemeriksaan / tindakan..."
                                   autocomplete="off">

                            <datalist id="list_item">
                                <?php foreach ($procedureOptions as $p) : ?>
                                    <option value="<?= esc($p['label']) ?>"></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>

                        <div class="col-md-2">
                            <label>Jumlah</label>
                            <input type="number" id="qty_item" class="form-control" value="1" min="1">
                        </div>

                        <div class="col-md-4">
                            <label>Hasil / Catatan</label>
                            <input type="text" id="note_item" class="form-control" placeholder="Contoh: hasil normal">
                        </div>

                        <div class="col-md-1">
                            <label>&nbsp;</label>
                            <button type="button" id="btnAddItem" class="btn btn-success btn-block">
                                +
                            </button>
                        </div>
                    </div>

                    <br>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nama Item</th>
                                <th width="90">Jumlah</th>
                                <th>Hasil / Catatan</th>
                                <th width="80">Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="tbody_item">
                            <tr id="emptyRow">
                                <td colspan="4" class="text-center text-muted">
                                    Belum ada item.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                Simpan Catatan Medis
            </button>

            <a href="<?= base_url('doctor/medical_history_detail/' . $patient->user_id) ?>" class="btn btn-default">
                Kembali
            </a>
        <?= form_close() ?>
    </div>
</div>

<script>
    const procedureOptions = <?= json_encode($procedureOptions, JSON_UNESCAPED_UNICODE) ?>;

    function findItem(label) {
        return procedureOptions.find(function(item) {
            return item.label.toLowerCase() === label.toLowerCase();
        });
    }

    document.getElementById('btnAddItem').addEventListener('click', function () {
        const searchInput = document.getElementById('search_item');
        const qtyInput = document.getElementById('qty_item');
        const noteInput = document.getElementById('note_item');
        const tbody = document.getElementById('tbody_item');

        const selected = findItem(searchInput.value.trim());

        if (!selected) {
            alert('Pilih pemeriksaan / tindakan dari hasil pencarian.');
            return;
        }

        let qty = parseInt(qtyInput.value || '1');

        if (qty <= 0) {
            qty = 1;
        }

        const note = noteInput.value.trim();

        const emptyRow = document.getElementById('emptyRow');

        if (emptyRow) {
            emptyRow.remove();
        }

        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td>
                ${selected.name}
                <input type="hidden" name="item_id[]" value="${selected.id}">
            </td>
            <td>
                ${qty}
                <input type="hidden" name="qty[]" value="${qty}">
            </td>
            <td>
                ${note || '-'}
                <input type="hidden" name="result_note[]" value="${note}">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-xs btnRemove">
                    Hapus
                </button>
            </td>
        `;

        tbody.appendChild(tr);

        searchInput.value = '';
        qtyInput.value = 1;
        noteInput.value = '';
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btnRemove')) {
            e.target.closest('tr').remove();

            const tbody = document.getElementById('tbody_item');

            if (tbody.children.length === 0) {
                tbody.innerHTML = `
                    <tr id="emptyRow">
                        <td colspan="4" class="text-center text-muted">
                            Belum ada item.
                        </td>
                    </tr>
                `;
            }
        }
    });
</script>