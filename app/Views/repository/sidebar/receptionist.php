<!-- File: receptionist.php -->
<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title">
      <a href="#collapseRecep" data-parent="#accordion" data-toggle="collapse">
        <span class="glyphicon glyphicon-edit"></span> Reception Desk
      </a>
    </h4>
  </div>
  <div class="panel-collapse collapse" id="collapseRecep">
    <div class="panel-body">
      <table class="table" style="margin-bottom: 0px;">
        <tbody>
          <tr><td><span class="glyphicon glyphicon-plus text-success"></span> <a href="<?= base_url('patient/register') ?>">Patient Entry</a></td></tr>
          <tr><td><span class="glyphicon glyphicon-list text-success"></span> <a href="<?= base_url('patient') ?>">Master List</a></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>