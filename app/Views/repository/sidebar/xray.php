<!-- File: xray.php -->
<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title">
      <a href="#collapseXray" data-parent="#accordion" data-toggle="collapse">
        <span class="glyphicon glyphicon-picture"></span> Radiology Dep.
      </a>
    </h4>
  </div>
  <div class="panel-collapse collapse" id="collapseXray">
    <div class="panel-body">
      <table class="table" style="margin-bottom: 0px;">
        <tbody>
          <tr><td><span class="glyphicon glyphicon-film text-info"></span> <a href="<?= base_url('xray') ?>">X-Ray Records</a></td></tr>
          <tr><td><span class="glyphicon glyphicon-camera text-info"></span> <a href="<?= base_url('xray/new_xray') ?>">New X-Ray Type</a></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>