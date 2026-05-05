<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title">
      <a href="#collapseAdmin" data-parent="#accordion" data-toggle="collapse">
        <span class="glyphicon glyphicon-cog"></span> Administration
      </a>
    </h4>
  </div>
  <div class="panel-collapse collapse" id="collapseAdmin">
    <div class="panel-body">
      <table class="table" style="margin-bottom: 0px;">
        <tbody>
          <tr><td><span class="glyphicon glyphicon-user text-primary" style="font-size: 14px;"></span> <a href="<?= base_url('account/users') ?>">Staff Directory</a></td></tr>
          <tr><td><span class="glyphicon glyphicon-plus text-primary" style="font-size: 14px;"></span> <a href="<?= base_url('account/signup') ?>">Add New Staff</a></td></tr>
          <tr><td><span class="glyphicon glyphicon-tags text-primary" style="font-size: 14px;"></span> <a href="<?= base_url('account/groups') ?>">User Groups</a></td></tr>
          <tr><td><span class="glyphicon glyphicon-folder-open text-primary" style="font-size: 14px;"></span> <a href="<?= base_url('account/add_group') ?>">Create Group</a></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>