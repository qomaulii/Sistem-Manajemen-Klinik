<div class="panel-group" id="accordionDoctor" style="margin-top: 15px;">
  
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a href="#collapseDoctor" data-parent="#accordionDoctor" data-toggle="collapse" style="font-size: 14px; font-weight: bold; text-decoration: none;">
          <span class="glyphicon glyphicon-user" style="margin-right: 5px;"></span> Panel Dokter
        </a>
      </h4>
    </div>
    
    <div class="panel-collapse collapse in" id="collapseDoctor">
      <div class="panel-body" style="padding: 0;">
        <table class="table table-hover" style="margin-bottom: 0px; font-size: 14px;">
          <tbody>
            <tr>
              <td style="padding: 10px 15px;">
                <span class="glyphicon glyphicon-list-alt text-primary" style="margin-right: 8px;"></span> 
                <a href="<?= base_url('doctor/queue') ?>" style="text-decoration: none; color: #333333;">Lihat Daftar Antrean Pasien</a>
              </td>
            </tr>
            <tr>
              <td style="padding: 10px 15px;">
                <span class="glyphicon glyphicon-folder-open text-primary" style="margin-right: 8px;"></span> 
                <a href="<?= base_url('doctor/medical_history') ?>" style="text-decoration: none; color: #333333;">Melihat Riwayat Medis Pasien</a>
              </td>
            </tr>
            <tr>
              <td style="padding: 10px 15px;">
                <span class="glyphicon glyphicon-edit text-primary" style="margin-right: 8px;"></span> 
                <a href="<?= base_url('doctor/prescription') ?>" style="text-decoration: none; color: #333333;">Membuat Resep Obat</a>
              </td>
            </tr>
            <tr>
              <td style="padding: 10px 15px;">
                <span class="glyphicon glyphicon-tint text-primary" style="margin-right: 8px;"></span> 
                <a href="<?= base_url('doctor/lab_schedule') ?>" style="text-decoration: none; color: #333333;">Menjadwalkan Tes Lab</a>
              </td>
            </tr>
            <tr>
              <td style="padding: 10px 15px;">
                <span class="glyphicon glyphicon-picture text-primary" style="margin-right: 8px;"></span> 
                <a href="<?= base_url('doctor/xray_schedule') ?>" style="text-decoration: none; color: #333333;">Menjadwalkan X-Ray</a>
              </td>
            </tr>
            <tr>
              <td style="padding: 10px 15px; border-top: 2px solid #eeeeee;">
                <span class="glyphicon glyphicon-log-out text-danger" style="margin-right: 8px;"></span> 
                <a href="<?= base_url('account/logout') ?>" style="text-decoration: none; color: #d9534f; font-weight: bold;">Logout</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>