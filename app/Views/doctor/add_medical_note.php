<?php
    $namaPasien = trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? ''));
?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Tambah Catatan Medis</h3>
    </div>

    <div class="panel-body">
        <p>
            <strong>Nama Pasien:</strong> <?= esc($namaPasien) ?><br>
            <strong>No. Antrean:</strong> <?= esc($visit->queue_number ?? '-') ?>
        </p>

        <hr>

        <?= form_open(current_url(), ['id' => 'formCatatanMedis']) ?>
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Deskripsi / Catatan Umum</label>
                <textarea name="deskripsi_umum"
                          class="form-control"
                          rows="4"
                          placeholder="Opsional. Contoh: pasien kontrol ulang, keluhan membaik, atau catatan tambahan dokter."><?= old('deskripsi_umum') ?></textarea>
            </div>

            <hr>

            <h4>Tambah Pemeriksaan / Tindakan</h4>
            <p class="text-muted">
                Untuk obat, tes lab, dan x-ray gunakan menu masing-masing di Panel Dokter.
            </p>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Pemeriksaan / Tindakan</strong>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-5">
                            <label>Cari Pemeriksaan / Tindakan</label>

                            <input type="text"
                                   id="search_pemeriksaan"
                                   class="form-control"
                                   list="list_pemeriksaan"
                                   placeholder="Ketik nama pemeriksaan / tindakan..."
                                   autocomplete="off">

                            <datalist id="list_pemeriksaan">
                                <?php if (!empty($pemeriksaanItems)) : ?>
                                    <?php foreach ($pemeriksaanItems as $item) : ?>
                                        <option value="<?= esc($item->item_name) ?>"></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </datalist>
                        </div>

                        <div class="col-md-2">
                            <label>Jumlah</label>
                            <input type="number"
                                   id="qty_pemeriksaan"
                                   class="form-control"
                                   value="1"
                                   min="1">
                        </div>

                        <div class="col-md-4">
                            <label>Hasil / Catatan</label>
                            <input type="text"
                                   id="note_pemeriksaan"
                                   class="form-control"
                                   placeholder="Contoh: TD 120/80, suhu 37°C, hasil normal">
                        </div>

                        <div class="col-md-1">
                            <label>&nbsp;</label>
                            <button type="button"
                                    id="btn_add_pemeriksaan"
                                    class="btn btn-success btn-block">
                                +
                            </button>
                        </div>
                    </div>

                    <br>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nama Pemeriksaan / Tindakan</th>
                                <th width="100">Jumlah</th>
                                <th>Hasil / Catatan</th>
                            </tr>
                        </thead>

                        <tbody id="tbody_pemeriksaan">
                            <tr class="empty-row">
                                <td colspan="4" class="text-center text-muted">
                                    Belum ada pemeriksaan / tindakan.
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
    const pemeriksaanData = [
        <?php if (!empty($pemeriksaanItems)) : ?>
            <?php foreach ($pemeriksaanItems as $item) : ?>
                {
                    id: "<?= esc($item->item_id) ?>",
                    name: "<?= esc($item->item_name) ?>"
                },
            <?php endforeach; ?>
        <?php endif; ?>
    ];

    function findPemeriksaanByName(name) {
        return pemeriksaanData.find(function(item) {
            return item.name.toLowerCase() === name.toLowerCase();
        });
    }

    document.getElementById('btn_add_pemeriksaan').addEventListener('click', function() {
        const searchInput = document.getElementById('search_pemeriksaan');
        const qtyInput = document.getElementById('qty_pemeriksaan');
        const noteInput = document.getElementById('note_pemeriksaan');
        const tbody = document.getElementById('tbody_pemeriksaan');

        const itemName = searchInput.value.trim();
        const qty = qtyInput.value || 1;
        const note = noteInput.value.trim();

        if (itemName === '') {
            alert('Pilih pemeriksaan / tindakan terlebih dahulu.');
            return;
        }

        const item = findPemeriksaanByName(itemName);

        if (!item) {
            alert('Item tidak ditemukan. Pilih dari daftar yang muncul.');
            return;
        }

        const emptyRow = tbody.querySelector('.empty-row');

        if (emptyRow) {
            emptyRow.remove();
        }

        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td>
                ${item.name}
                <input type="hidden" name="item_ids[]" value="${item.id}">
            </td>
            <td>
                ${qty}
                <input type="hidden" name="qty[]" value="${qty}">
            </td>
            <td>
                ${note}
                <input type="hidden" name="result_note[]" value="${note}">
            </td>
        `;

        tbody.appendChild(tr);

        searchInput.value = '';
        qtyInput.value = 1;
        noteInput.value = '';
    });
</script>