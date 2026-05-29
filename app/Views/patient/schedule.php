<div class="container" style="margin-top:20px;">
    <legend>- Jadwal Praktik Dokter</legend>
    <div class="panel panel-default">
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nama Dokter</th>
                        <th>Spesialisasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($doctors)) : ?>
                        <?php foreach ($doctors as $d) : ?>
                        <tr>
                            <td><strong>Dr. <?= esc($d->first_name . ' ' . $d->last_name) ?></strong></td>
                            <td><?= esc($d->position) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="2" class="text-center">Jadwal tidak tersedia.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>