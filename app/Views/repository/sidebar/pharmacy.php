<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title">
      <a href="#collapseDrug" data-parent="#accordion" data-toggle="collapse">
        <span class="glyphicon glyphicon-leaf"></span> Menu Apoteker
      </a>
    </h4>
  </div>

  <div class="panel-collapse collapse in" id="collapseDrug">
    <div class="panel-body">
      <table class="table" style="margin-bottom: 0px;">
        <tbody>
          <tr>
            <td style="padding: 10px 15px;">
              <span class="glyphicon glyphicon-list-alt text-warning"></span>
              <a href="<?= base_url('drug/queue') ?>">Daftar Antrean Pasien</a>
            </td>
          </tr>

          <tr>
            <td style="padding: 10px 15px;">
              <span class="glyphicon glyphicon-shopping-cart text-warning"></span>
              <a href="<?= base_url('drug/transactions') ?>">Transaksi Pembelian Obat</a>
            </td>
          </tr>

          <tr>
            <td style="padding: 10px 15px;">
              <span class="glyphicon glyphicon-th-list text-warning"></span>
              <a href="<?= base_url('drug/stock') ?>">Melihat Stok Obat</a>
            </td>
          </tr>

          <tr>
            <td style="padding: 10px 15px;">
              <span class="glyphicon glyphicon-plus text-warning"></span>
              <a href="<?= base_url('drug/add_stock') ?>">Menambah Obat Baru</a>
            </td>
          </tr>

          <tr>
            <td style="padding: 10px 15px; border-top: 2px solid #eeeeee;">
              <span class="glyphicon glyphicon-log-out text-danger"></span>
              <a href="<?= base_url('account/logout') ?>" style="color: #d9534f; font-weight: bold;">Logout</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>