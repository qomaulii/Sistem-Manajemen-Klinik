<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title">
      <a href="#collapsePatient" data-parent="#accordion" data-toggle="collapse">
        <span class="glyphicon glyphicon-user"></span> My Health Portal
      </a>
    </h4>
  </div>
  <div class="panel-collapse collapse" id="collapsePatient">
    <div class="panel-body">
      <table class="table" style="margin-bottom: 0px;">
        <tbody>
          <tr>
            <td style="padding: 10px 15px;">
              <span class="glyphicon glyphicon-folder-open text-info"></span> <a href="<?= base_url('patient/panel') ?>">My Medical Records</a>
            </td>
          </tr>
          <tr>
            <td style="padding: 10px 15px;">
              <span class="glyphicon glyphicon-print text-info"></span> <a href="<?= base_url('patient/ticket') ?>">Download Ticket</a>
            </td>
          </tr>
          <tr>
            <td style="padding: 10px 15px;">
              <span class="glyphicon glyphicon-comment text-info"></span> <a href="#">Message Doctor</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>