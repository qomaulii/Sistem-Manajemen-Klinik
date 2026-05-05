<!-- File: doctor.php -->
<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title">
      <a href="#collapseDoc" data-parent="#accordion" data-toggle="collapse">
        <span class="glyphicon glyphicon-briefcase"></span> Doctor Services
      </a>
    </h4>
  </div>
  <div class="panel-collapse collapse" id="collapseDoc">
    <div class="panel-body">
      <table class="table" style="margin-bottom: 0px;">
        <tbody>
          <tr><td><span class="glyphicon glyphicon-time text-info"></span> <a href="<?= base_url('patient/waiting') ?>">Patient Queue</a></td></tr>
          <tr><td><span class="glyphicon glyphicon-book text-info"></span> <a href="<?= base_url('patient') ?>">Patient Records</a></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>