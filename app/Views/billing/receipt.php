<?php
    $namaPasien = trim(($billing->patient_first_name ?? '') . ' ' . ($billing->patient_last_name ?? ''));
    $namaKasir = trim(($billing->cashier_first_name ?? '') . ' ' . ($billing->cashier_last_name ?? ''));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <link rel="stylesheet" href="<?= base_url('content/css/bootstrap.min.css') ?>">
    <style>
        body {
            font-family: Tahoma, sans-serif;
            font-size: 14px;
            padding: 20px;
        }

        .receipt-box {
            max-width: 750px;
            margin: auto;
            border: 1px solid #ddd;
            padding: 25px;
        }

        .text-center {
            text-align: center;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 0;
            }

            .receipt-box {
                border: none;
            }
        }
    </style>
</head>

<body>
    <div class="receipt-box">
        <div class="text-center">
            <h3>SISTEM MANAJEMEN KLINIK</h3>
            <p>Struk Pembayaran Pasien</p>
        </div>

        <hr>

        <table class="table table-condensed">
            <tr>
                <th width="180">No. Struk</th>
                <td>#<?= esc($billing->bill_id) ?></td>
            </tr>
            <tr>
                <th>No. Antrean</th>
                <td><?= esc($billing->queue_number ?? '-') ?></td>
            </tr>
            <tr>
                <th>Nama Pasien</th>
                <td><?= esc($namaPasien ?: '-') ?></td>
            </tr>
            <tr>
                <th>Kasir / Resepsionis</th>
                <td><?= esc($namaKasir ?: '-') ?></td>
            </tr>
            <tr>
                <th>Metode Pembayaran</th>
                <td><?= esc($billing->payment_method ?? '-') ?></td>
            </tr>
            <tr>
                <th>Tanggal Bayar</th>
                <td><?= !empty($billing->paid_date) ? date('d-m-Y H:i', $billing->paid_date) : '-' ?></td>
            </tr>
        </table>

        <h4>Rincian Biaya</h4>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Jenis</th>
                    <th>Nama Item</th>
                    <th width="120">Harga</th>
                    <th width="80">Jumlah</th>
                    <th width="130">Subtotal</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($items as $item) : ?>
                    <tr>
                        <td><?= esc($item->item_type) ?></td>
                        <td><?= esc($item->item_name) ?></td>
                        <td>Rp <?= number_format((float) $item->price, 0, ',', '.') ?></td>
                        <td><?= esc($item->qty) ?></td>
                        <td><strong>Rp <?= number_format((float) $item->subtotal, 0, ',', '.') ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

            <tfoot>
                <tr>
                    <th colspan="4" class="text-right">Total Pembayaran</th>
                    <th>Rp <?= number_format((float) $billing->total_amount, 0, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>

        <p>
            <strong>Status:</strong>
            <span class="label label-success">Sudah Bayar</span>
        </p>

        <hr>

        <div class="text-center">
            <p>Terima kasih. Semoga lekas sehat.</p>
        </div>

        <div class="no-print text-center">
            <button onclick="window.print()" class="btn btn-primary">
                Cetak Struk
            </button>

            <a href="<?= base_url('billing') ?>" class="btn btn-default">
                Kembali
            </a>
        </div>
    </div>
</body>
</html>