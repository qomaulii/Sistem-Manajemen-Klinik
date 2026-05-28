<div class="row">
    <div class="col-md-12">
        <div class="table-responsive" style="background-color: #ffffff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #f0f0f0; margin-top: 20px;">
            <table class="table table-hover table-striped" style="font-size: 14px; margin-bottom: 0;">
                <thead>
                    <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dddddd;">
                        <th style="width: 80px; text-align: center;">No. Antrean</th>
                        <th>Nama Pasien</th>
                        <th>Waktu Pendaftaran</th>
                        <th style="text-align: center; width: 120px;">Status</th>
                        <th class="text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($queues)) : ?>
                        <?php foreach ($queues as $q) : ?>
                            <tr>
                                <td style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 16px;">
                                    <?= str_pad($q->queue_number, 3, '0', STR_PAD_LEFT) ?>
                                </td>
                                <td style="vertical-align: middle;">
                                    <strong><?= esc($q->first_name . ' ' . $q->last_name) ?></strong><br>
                                    <small style="color: #777;">ID: P-<?= esc($q->patient_id) ?></small>
                                </td>
                                <td style="vertical-align: middle;">
                                    <?= date('H:i', $q->register_time) ?> WITA
                                </td>
                                
                                <td style="text-align: center; vertical-align: middle;">
                                    <?php if ($q->status == 'Menunggu') : ?>
                                        <span class="label label-warning" style="padding: 5px 10px;">Menunggu</span>
                                    <?php elseif ($q->status == 'Diperiksa') : ?>
                                        <span class="label label-info" style="padding: 5px 10px;">Diperiksa</span>
                                    <?php else : ?>
                                        <span class="label label-success" style="padding: 5px 10px;">Selesai</span>
                                    <?php endif; ?>
                                </td>

                                <td style="text-align: center; vertical-align: middle;">
                                    <?php if ($q->status == 'Menunggu') : ?>
                                        <a href="<?= base_url('doctor/update_status/' . $q->visit_id . '/Diperiksa') ?>" class="btn btn-sm btn-primary" style="font-weight: bold;">Periksa</a>
                                    <?php elseif ($q->status == 'Diperiksa') : ?>
                                        <a href="<?= base_url('doctor/update_status/' . $q->visit_id . '/Selesai') ?>" class="btn btn-sm btn-success" style="font-weight: bold;">Selesai</a>
                                    <?php else : ?>
                                        <a href="<?= base_url('doctor/medical_history/' . $q->patient_id) ?>" class="btn btn-sm btn-default">Riwayat Medis</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px; color: #888;">
                                Belum ada antrean masuk.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>