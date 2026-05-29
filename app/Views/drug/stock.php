<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
<?php endif; ?>

<div class="panel panel-warning">
    <div class="panel-heading">
        Melihat Stok Obat
    </div>

    <div class="panel-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama Obat</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($drugs)) : ?>
                    <?php foreach ($drugs as $d) : ?>
                        <tr>
                            <td><?= esc($d->item_name) ?></td>
                            <td>Rp <?= number_format((float) $d->price, 0, ',', '.') ?></td>
                            <td><?= esc($d->stock ?? 0) ?></td>
                            <td>
                                <?= form_open('drug/delete_stock/' . $d->item_id, ['onsubmit' => "return confirm('Yakin ingin menghapus obat ini dari daftar stok?')"]) ?>
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                <?= form_close() ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada data obat.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <hr>

        <a href="<?= base_url('drug/add_stock') ?>" class="btn btn-success">
            <span class="glyphicon glyphicon-plus"></span> Tambah Obat / Tambah Stok
        </a>
    </div>
</div>