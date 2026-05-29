<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title">
      <a href="#collapseRecep" data-parent="#accordion" data-toggle="collapse" style="font-size: 14px; font-weight: bold; text-decoration: none;">
        <span class="glyphicon glyphicon-edit" style="margin-right: 5px;"></span> Resepsionis
      </a>
    </h4>
  </div>

  <div class="panel-collapse collapse in" id="collapseRecep">
    <div class="panel-body" style="padding: 0;">
      <table class="table table-hover" style="margin-bottom: 0px; font-size: 14px;">
        <tbody>

          <tr>
            <td style="padding: 10px 15px;">
              <span class="glyphicon glyphicon-list-alt text-primary" style="margin-right: 8px;"></span>
              <a href="<?= base_url('receptionist/patient_doctor_list') ?>" style="text-decoration: none; color: #333333;">
                Daftar Pasien-Dokter
              </a>
            </td>
          </tr>

          <tr>
            <td style="padding: 10px 15px;">
              <span class="glyphicon glyphicon-plus text-primary" style="margin-right: 8px;"></span>
              <a href="<?= base_url('patient/register') ?>" style="text-decoration: none; color: #333333;">
                Pendaftaran Pasien
              </a>
            </td>
          </tr>

          <tr>
            <td style="padding: 10px 15px;">
              <span class="glyphicon glyphicon-time text-primary" style="margin-right: 8px;"></span>
              <a href="<?= base_url('patient/waiting') ?>" style="text-decoration: none; color: #333333;">
                Antrean & Status
              </a>
            </td>
          </tr>

          <tr>
            <td style="padding: 10px 15px;">
              <span class="glyphicon glyphicon-usd text-primary" style="margin-right: 8px;"></span>
              <a href="<?= base_url('billing') ?>" style="text-decoration: none; color: #333333;">
                Tagihan Pembayaran
              </a>
            </td>
          </tr>

          <tr>
            <td style="padding: 10px 15px; border-top: 2px solid #eeeeee;">
              <span class="glyphicon glyphicon-log-out text-danger" style="margin-right: 8px;"></span>
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