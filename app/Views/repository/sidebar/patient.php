<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title">
      <a href="#collapsePatient" data-parent="#accordion" data-toggle="collapse">
        <span class="glyphicon glyphicon-user"></span> Menu Pasien
      </a>
    </h4>
  </div>
  <div class="panel-collapse collapse in" id="collapsePatient">
    <div class="panel-body">
      <table class="table" style="margin-bottom: 0px;">
        <tbody>
          <tr>
            <td style="padding: 10px 15px;">
              <span class="glyphicon glyphicon-plus text-info"></span> 
              <a href="<?= base_url('patient/booking') ?>">Booking Antrean</a>
            </td>
          </tr>
          <tr>
            <td style="padding: 10px 15px;">
              <span class="glyphicon glyphicon-folder-open text-info"></span> 
              <a href="<?= base_url('patient/history') ?>">Riwayat & Hasil</a>
            </td>
          </tr>
          <tr>
            <td style="padding: 10px 15px; border-top: 2px solid #eeeeee;">
              <span class="glyphicon glyphicon-log-out text-danger"></span> 
              <a href="<?= base_url('account/logout') ?>" style="text-decoration: none; color: #d9534f; font-weight: bold;">
                Logout
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>