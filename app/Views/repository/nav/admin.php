<li class="dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <span class="glyphicon glyphicon-user"></span> User Management <b class="caret"></b>
  </a>
  <ul class="dropdown-menu" style="min-width: 220px; padding: 10px 0px;">
    <li style="padding: 5px 20px;"><strong>- Account Actions</strong></li>
    <li><a href="<?= base_url('account/users') ?>"><span class="glyphicon glyphicon-list-alt"></span> View All Users</a></li>
    <li><a href="<?= base_url('account/signup') ?>"><span class="glyphicon glyphicon-plus"></span> Register New Staff</a></li>
    <li class="divider"></li>
    <li style="padding: 5px 20px;"><strong>- Access Control</strong></li>
    <li><a href="<?= base_url('account/groups') ?>"><span class="glyphicon glyphicon-tags"></span> User Groups</a></li>
    <li><a href="<?= base_url('account/add_group') ?>"><span class="glyphicon glyphicon-lock"></span> Create New Group</a></li>
  </ul>
</li>
<li class="dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <span class="glyphicon glyphicon-cog"></span> System Settings <b class="caret"></b>
  </a>
  <ul class="dropdown-menu" style="min-width: 200px;">
    <li><a href="<?= base_url('setting') ?>"><span class="glyphicon glyphicon-wrench"></span> General Settings</a></li>
    <li><a href="<?= base_url('report_bug/add') ?>"><span class="glyphicon glyphicon-bullhorn"></span> Report a Bug</a></li>
  </ul>
</li>