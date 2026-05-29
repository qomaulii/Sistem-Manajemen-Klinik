<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Form Pendaftaran Pasien</h3>
    </div>

    <div class="panel-body">
        <?= form_open('receptionist/save_registration', ['id' => 'formPendaftaran']) ?>
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Pilih Pasien dari Antrean:</label>

                <input type="text"
                       id="search_pasien"
                       class="form-control"
                       list="list_pasien"
                       placeholder="Ketik nomor antrean atau nama pasien..."
                       autocomplete="off">

                <input type="hidden" name="visit_id" id="visit_id">

                <datalist id="list_pasien">
                    <?php if (!empty($queues)) : ?>
                        <?php foreach ($queues as $q) : ?>
                            <?php
                                $namaPasien = trim(($q->first_name ?? '') . ' ' . ($q->last_name ?? ''));
                                $labelPasien = $q->queue_number . ' - ' . $namaPasien;
                            ?>
                            <option value="<?= esc($labelPasien) ?>" data-id="<?= esc($q->visit_id) ?>"></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </datalist>
            </div>

            <div class="form-group">
                <label>Pilih Dokter:</label>

                <input type="text"
                       id="search_dokter"
                       class="form-control"
                       list="list_dokter"
                       placeholder="Ketik nama dokter..."
                       autocomplete="off">

                <input type="hidden" name="doctor_id" id="doctor_id">

                <datalist id="list_dokter">
                    <?php if (!empty($doctors)) : ?>
                        <?php foreach ($doctors as $d) : ?>
                            <?php
                                $namaDokter = trim(($d->first_name ?? '') . ' ' . ($d->last_name ?? ''));
                                $labelDokter = 'Dr. ' . $namaDokter;
                            ?>
                            <option value="<?= esc($labelDokter) ?>" data-id="<?= esc($d->user_id) ?>"></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </datalist>
            </div>

            <button type="submit" class="btn btn-primary">
                Daftarkan ke Antrean
            </button>

        <?= form_close() ?>
    </div>
</div>

<script>
    function ambilIdDariDatalist(inputId, datalistId, hiddenId) {
        const input = document.getElementById(inputId);
        const datalist = document.getElementById(datalistId);
        const hidden = document.getElementById(hiddenId);

        hidden.value = '';

        for (let i = 0; i < datalist.options.length; i++) {
            if (datalist.options[i].value === input.value) {
                hidden.value = datalist.options[i].getAttribute('data-id');
                break;
            }
        }
    }

    document.getElementById('search_pasien').addEventListener('input', function () {
        ambilIdDariDatalist('search_pasien', 'list_pasien', 'visit_id');
    });

    document.getElementById('search_dokter').addEventListener('input', function () {
        ambilIdDariDatalist('search_dokter', 'list_dokter', 'doctor_id');
    });

    document.getElementById('formPendaftaran').addEventListener('submit', function (e) {
        ambilIdDariDatalist('search_pasien', 'list_pasien', 'visit_id');
        ambilIdDariDatalist('search_dokter', 'list_dokter', 'doctor_id');

        if (document.getElementById('visit_id').value === '') {
            e.preventDefault();
            alert('Pilih pasien dari daftar antrean.');
            return false;
        }

        if (document.getElementById('doctor_id').value === '') {
            e.preventDefault();
            alert('Pilih dokter dari daftar.');
            return false;
        }
    });
</script>