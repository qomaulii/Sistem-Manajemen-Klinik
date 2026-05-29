<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title">
      <a href="#collapseLab" data-parent="#accordion" data-toggle="collapse">
        <span class="glyphicon glyphicon-tint"></span> Menu Analis Lab
      </a>
    </h4>
  </div>

  <div class="panel-collapse collapse in" id="collapseLab">
    <div class="panel-body">
      <table class="table" style="margin-bottom: 0px;">
        <tbody>
          <tr>
            <td style="padding: 10px 15px;">
              <span class="glyphicon glyphicon-list-alt text-info"></span>
              <a href="<?= base_url('test/queue') ?>">Daftar Antrean Pasien</a>
            </td>
          </tr>

          <tr>
            <td style="padding: 10px 15px;">
              <span class="glyphicon glyphicon-edit text-info"></span>
              <a href="<?= base_url('test/queue') ?>">Input Hasil Lab</a>
            </td>
          </tr>

          <tr>
            <td style="padding: 10px 15px;">
              <span class="glyphicon glyphicon-folder-open text-info"></span>
              <a href="<?= base_url('test/results') ?>">Lihat Daftar Hasil Lab</a>
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