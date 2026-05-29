<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<?php
    $drugNames = [];
    foreach ($drugOptions as $d) {
        $drugNames[] = $d->item_name;
    }
?>

<div class="panel panel-success">
    <div class="panel-heading">
        Menambah Obat Baru / Tambah Stok Obat
    </div>

    <div class="panel-body">
        <?= form_open('drug/add_stock') ?>
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Nama Obat:</label>
                <input 
                    type="text" 
                    name="item_name" 
                    class="form-control" 
                    list="list_obat" 
                    placeholder="Ketik nama obat lama atau nama obat baru..." 
                    required
                    autocomplete="off"
                >

                <datalist id="list_obat">
                    <?php foreach ($drugNames as $name) : ?>
                        <option value="<?= esc($name) ?>"></option>
                    <?php endforeach; ?>
                </datalist>

                <small class="text-muted">
                    Kalau nama obat sudah ada, sistem akan menambah stok obat tersebut.
                    Kalau nama belum ada, sistem akan membuat data obat baru.
                </small>
            </div>

            <div class="form-group">
                <label>Harga Obat:</label>
                <input type="number" name="price" class="form-control" placeholder="Contoh: 15000" required>
            </div>

            <div class="form-group">
                <label>Jumlah Stok yang Ditambahkan:</label>
                <input type="number" name="stock" class="form-control" min="1" placeholder="Contoh: 20" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Obat</button>
            <a href="<?= base_url('drug/stock') ?>" class="btn btn-default">Kembali</a>
        <?= form_close() ?>
    </div>
</div>