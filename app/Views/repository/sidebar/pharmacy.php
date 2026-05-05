<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title">
      <a href="#collapseDrug" data-parent="#accordion" data-toggle="collapse">
        <span class="glyphicon glyphicon-leaf"></span> Pharmacy Management
      </a>
    </h4>
  </div>
  <div class="panel-collapse collapse" id="collapseDrug">
    <div class="panel-body">
      <table class="table" style="margin-bottom: 0px;">
        <tbody>
          <tr><td><span class="glyphicon glyphicon-list-alt text-warning"></span> <a href="<?= base_url('drug') ?>">Drug Database</a></td></tr>
          <tr><td><span class="glyphicon glyphicon-plus text-warning"></span> <a href="<?= base_url('drug/new_drug') ?>">New Drug Entry</a></td></tr>
          <tr><td><span class="glyphicon glyphicon-import text-warning"></span> <a href="<?= base_url('drug/add_drug') ?>">Purchase Stocks</a></td></tr>
          <tr><td><span class="glyphicon glyphicon-export text-warning"></span> <a href="<?= base_url('drug/return_drug') ?>">Drug Returns</a></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>