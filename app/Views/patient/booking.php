<div class="container" style="margin-top:20px;">
    <?php if (session()->getFlashdata('message')) : ?>
        <div class="alert alert-info">
            <h3><strong><?= session()->getFlashdata('message') ?></strong></h3>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger">
            <h3><strong><?= session()->getFlashdata('error') ?></strong></h3>
        </div>
    <?php endif; ?>

    <div class="text-center" style="margin: 30px 0;">
        <?= form_open('patient/book_queue', ['onsubmit' => 'return confirm("Yakin ingin mengambil nomor antrean untuk hari ini?");']) ?>
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-primary btn-lg">
                <span class="glyphicon glyphicon-calendar"></span> Ambil Nomor Antrean Sekarang
            </button>
        <?= form_close() ?>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Daftar Antrean Anda</div>
        <div class="panel-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No. Antrean</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($my_bookings)) : ?>
                        <?php foreach ($my_bookings as $v) : ?>
                        <tr>
                            <td><strong><?= esc($v->queue_number) ?></strong></td>
                            
                            <td><?= date('d M Y', $v->register_time) ?></td>
                            <td>
                                <span class="label <?= ($v->status == 'Selesai') ? 'label-success' : 'label-info' ?>">
                                    <?= esc($v->status) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($v->status == 'Menunggu') : ?>
                                    <?= form_open('patient/cancel_booking/' . $v->visit_id, ['onsubmit' => 'return confirm("Batalkan antrean?");']) ?>
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger btn-xs">Batalkan</button>
                                    <?= form_close() ?>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="text-center">Belum ada antrean yang terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>